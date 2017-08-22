<?php

require __DIR__.'/vendors/komtet-kassa-php-sdk/autoload.php';

use Komtet\KassaSdk\Client;
use Komtet\KassaSdk\QueueManager;
use Komtet\KassaSdk\Check;
use Komtet\KassaSdk\Payment;
use Komtet\KassaSdk\Position;
use Komtet\KassaSdk\Vat;
use Komtet\KassaSdk\Exception\ClientException;
use Komtet\KassaSdk\Exception\SdkException;

class shopKomtetkassaPlugin extends shopPlugin {

    const LOG_FILE_NAME = 'shop/plugins/komtetkassa/fiscalization.log';
    const API_KEY_REGEXP = "/^[a-z0-9]{16,}$/";
    const PHONE_REGEXP = "/^(8|\+?7)?(\d{3}?\d{7,10})$/";
    const REQUIRED_PROPERTY_ERROR = 0;
    const REQUIRED_URL_ERROR = 1;
    const KOMTET_ERROR = 2;
    const INT_MULTIPLICATOR = 100;

    private $komtet_api_url;
    private $komtet_shop_id;
    private $komtet_secret_key;
    private $komtet_print_check;
    private $komtet_queue_id;
    private $komtet_tax_type;
    private $komtet_delivery_tax;
    private $komtet_alert;
    private $komtet_alert_email;
    private $komtet_log;

    private function init() {
        $this->komtet_log = (bool) $this->getSettings('komtet_log');

        $this->komtet_api_url = filter_var($this->getSettings('komtet_api_url'), FILTER_VALIDATE_URL);
        $this->komtet_shop_id = $this->getSettings('komtet_shop_id');
        $this->komtet_secret_key = $this->getSettings('komtet_secret_key');
        $this->komtet_print_check = (bool) $this->getSettings('komtet_print_check');
        $this->komtet_queue_id = $this->getSettings('komtet_queue_id');
        $this->komtet_tax_type = (int) $this->getSettings('komtet_tax_type');
        $this->komtet_delivery_tax = (int) $this->getSettings('komtet_delivery_tax');
        $this->komtet_alert = (bool) $this->getSettings('komtet_alert');

        $main_shop_email = $this->validateEmail(wa('shop')->getConfig()->getGeneralSettings('email'));
        $this->komtet_alert_email = $this->validateEmail($this->getSettings('komtet_alert_email'));

        if(!$this->komtet_alert_email) {
            $this->komtet_alert_email = $main_shop_email;
        }
    }

    /**
     * Необходимо для совместимости интерфейса при вызове shopPayment::getOrderData
     */
    public function allowedCurrency() {
        return array('RUB');
    }

    public function fiscalize($params) {
        $this->processReceipt($params, 'payment');
    }

    public function refund($params) {
         $this->processReceipt($params, 'refund');
    }

    private function processReceipt($params, $operation = 'payment') {

        $this->init();

        if(!$this->komtet_api_url) {
            $this->pluginError(self::REQUIRED_URL_ERROR);
            return false;
        }

        if(!$this->komtet_shop_id || !$this->komtet_secret_key || !$this->komtet_queue_id) {
            $this->pluginError(self::REQUIRED_PROPERTY_ERROR);
            return false;
        }

		$client = new Client($this->komtet_shop_id, $this->komtet_secret_key);
		$client->setHost($this->komtet_api_url);
		$manager = new QueueManager($client);
		$manager->registerQueue('ss-queue', $this->komtet_queue_id);

        $order_id = $params['order_id'];
        $order = shopPayment::getOrderData($order_id, $this);
        $this->extendItems($order);

        $payment_id = $order->params['payment_id'];

        if($this->komtet_log) {
            $this->writeLog($params);
            $this->writeLog($order->items);
        }

		// В случае использования на сервере кирилической локали, например ru_RU.UTF-8,
		// возникает проблема с форматированием json
        $cur_local = setlocale(LC_NUMERIC, 0);
        $local_changed = false;
        if($cur_local != "en_US.UTF-8") {
            setlocale(LC_NUMERIC, "en_US.UTF-8");
            $local_changed = true;
        }

		$user = $this->komtet_alert_email;
        $customer_email = $order->getContactField('email', 'default');
        $customer_phone = $order->getContactField('phone', 'default');
        if(ifset($customer_email)) {
            $user = $customer_email;
        } else {
            if(ifset($customer_phone)) {
                $user = $customer_phone;
            }
        }

		if($operation == 'payment') {
			$check = Check::createSell($order_id, $user, $this->komtet_tax_type);
		} else {
			$check = Check::createSellReturn($order_id, $user, $this->komtet_tax_type);
		}
		$check->setShouldPrint($this->komtet_print_check);

        foreach($order->items as $item) {

            if(!isset($item['tax_included']) || $item['tax_included'] == 0) {
                $vat = new Vat(Vat::RATE_NO);
            } else {
                try {
				    $vat = new Vat($item['tax_percent']);
				} catch (SdkException $e) {
				    $this->writeLog($e);
				    $vat = new Vat(Vat::RATE_NO);
				}
            }

			$discount = $item['base_price'] * $item['quantity'] - $item['total'];

			$position = new Position(
				$item['name'] . ($item['sku'] != '' ? ", " . $item['sku'] : ''),
				round($item['base_price'] / self::INT_MULTIPLICATOR, 2),
				intval($item['quantity']),
				round($item['total'] / self::INT_MULTIPLICATOR, 2),
				$discount,
				$vat
			);
			$check->addPosition($position);

        }

        if(intval($order['shipping']) > 0) {

            try {
			    $vat = new Vat($this->komtet_delivery_tax);
			} catch (SdkException $e) {
			    $this->writeLog($e);
			    $vat = new Vat(Vat::RATE_NO);
			}

            $position = new Position(
				"Доставка: " . $order["shipping_name"],
				round($order['shipping'], 2),
				1,
				round($order['shipping'], 2),
				0,
				$vat
			);
			$check->addPosition($position);

	    }

        // Итоговая сумма расчёта
		$payment = Payment::createCard(round($order->total, 2)); // или createCash при оплате наличными
		$check->addPayment($payment);

		if($this->komtet_log) {
			$this->writeLog($check->asArray());
		}

        // Добавляем чек в очередь.
		try {
		    $manager->putCheck($check, 'ss-queue');
		} catch (SdkException $e) {
			$this->pluginError(self::KOMTET_ERROR, $e);
		}

        if($local_changed) {
            setlocale(LC_NUMERIC, $cur_local);
        }

        if($result['code'] == 0) {
            if($operation == 'payment') {
                $this->writeLog("Receipt for an order $order_id accepted");
            } else {
                $this->writeLog("Receipt for an order $order_id refunded");
            }
        } else {
            $this->pluginError(self::KOMTET_ERROR, $result);
        }

    }

    /**
     * Скопировано с shopPrintformPlugin для расчета скидок и налогов по каждому товару
     * @param waOrder $order
     * @return array
     */
    private function extendItems(&$order) {
        $items = $order->items;
        $product_model = new shopProductModel();

        $discount = intval(self::INT_MULTIPLICATOR * $order->discount);
        $shipping = intval(self::INT_MULTIPLICATOR * $order->shipping);
        $total = intval(self::INT_MULTIPLICATOR * $order->total);
        foreach ($items as & $item) {
            $data = $product_model->getById($item['product_id']);
            $item['currency'] = $order->currency;
            $item['base_price'] = intval(self::INT_MULTIPLICATOR * $item['price']);

            if (!empty($item['total_discount'])) {
                $discount -= intval(self::INT_MULTIPLICATOR * $item['total_discount']);
                $item['total'] -= $item['total_discount'];
            }
            $item['total'] = intval(self::INT_MULTIPLICATOR * $item['total']);
        }

        unset($item);
        $taxes_params = array(
            'billing'  => $order->billing_address,
            'shipping' => $order->shipping_address,
        );
        shopTaxes::apply($items, $taxes_params, $order['currency']);

        if ($discount) {
            #calculate discount as part of price
            if ($total + $discount - $shipping > 0) {
                $k = 1.0 - $discount / ($total + $discount - $shipping);
            } else {
                $k = 0.0;
            }

            $summ_total = 0;
            $max_total = 0;
            $max_total_item_index = 0;
            foreach ($items as $idx => & $item) {
                $item['total'] = intval($k * $item['total']);
                $summ_total += $item['total'];
                if ($item['total'] > $max_total) {
                    $max_total = $item['total'];
                    $max_total_item_index = $idx;
                }
            }
            unset($item);

            $diff = $total - $shipping - $summ_total;
            if ($diff != 0) {
                $items[$max_total_item_index]['total'] += $diff;
            }

        }
        $order->items = $items;
    }

    /**
     * Life-Pay принимает номера телефонов только в формате 7хххххххххх
     * и ругается, если номер не соответствует формату и, соответственно, не принимает чек,
     * что недопустимо.
     * Валидатор пропускает номера телефонов МОпС РФ вида:
     *  +71234567890
     *   71234567890
     *   81234567890
     *    1234567890
     * Все остальные номера игнорируются.
     * Валидатор проверяет номер телефона на соответствие формату и заменяет код страны
     * на 7 в соответствии с форматом, который принимает Life-Pay
     */
    private function validatePhone($phone) {
        if(preg_match(self::PHONE_REGEXP, $phone, $matches)) {
            return "7" . $matches[2];
        } else {
            return null;
        }
    }

    /**
     * Life-Pay ругается, если email не соответствует формату и не принимает чек, что недопустимо.
     */
    private function validateEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        } else {
            return null;
        }
    }

    public function pluginError($error_type, $data = null) {
        $subj = "Ошибка плагина";
        switch ($error_type) {
            case self::REQUIRED_URL_ERROR:
                $message = "Отстутствуют необходимые реквизиты: API URL";
                if($this->komtet_log) {
                    $this->writeLog($message);
                }
                if($this->komtet_alert) {
                    $this->emailNotification($subj, $message);
                }
                break;

            case self::REQUIRED_PROPERTY_ERROR:
                $message = "Отстутствуют необходимые реквизиты: Идентификатор магазина, Секретный ключ магазина или " .
                    "Идентификатор очереди";
                if($this->komtet_log) {
                    $this->writeLog($message);
                }
                if($this->komtet_alert) {
                    $this->emailNotification($subj, $message);
                }
                break;

            case self::KOMTET_ERROR:
                if($this->komtet_log) {
                    $this->writeLog($data);
                }
                if($this->komtet_alert) {
                    $this->emailNotification("Ошибка в системе КОМТЕТ Касса", print_r($data, true));
                }
                break;
        }
    }

    public function writeLog($message) {
        if(is_string($message)) {
            waLog::log($message, self::LOG_FILE_NAME);
        } else {
            waLog::dump($message, self::LOG_FILE_NAME);
        }
    }

}
//EOF
