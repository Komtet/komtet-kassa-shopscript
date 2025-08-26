<?php

use Komtet\KassaSdk\v1\Payment;
use Komtet\KassaSdk\v1\TaxSystem;
use Komtet\KassaSdk\v1\Vat;

class shopKomtetkassa {

    const PLUGIN_ID = 'komtetkassa';

    public static function taxTypesValues() {

    $data = array(
        array(
            'value' => TaxSystem::COMMON,
            'title' => 'ОСН',
            ),
        array(
            'value' => TaxSystem::SIMPLIFIED_IN,
            'title' => 'УСН доход',
            ),
        array(
            'value' => TaxSystem::SIMPLIFIED_IN_OUT,
            'title' => 'УСН доход - расход',
            ),
        array(
            'value' => TaxSystem::UST,
            'title' => 'ЕСХН',
            ),
        array(
            'value' => TaxSystem::PATENT,
            'title' => 'Патент',
            )
	    );
        return $data;
    }

    public static function vatValues() {
        $data = array(
            array(
                'value' => Vat::RATE_NO,
                'title' => 'Без НДС',
            ),
            array(
                'value' => Vat::RATE_0,
                'title' => 'НДС 0%',
            ),
            array(
                'value' => Vat::RATE_5,
                'title' => 'НДС 5%',
            ),
            array(
                'value' => Vat::RATE_7,
                'title' => 'НДС 7%',
            ),
            array(
                'value' => Vat::RATE_10,
                'title' => 'НДС 10%',
            ),
            array(
                'value' => Vat::RATE_20,
                'title' => 'НДС 20%',
            )
	);
        return $data;
    }

    public static function getPaymentTypes() {
        $plugin_id = self::PLUGIN_ID;
        $settings_name = 'komtet_payment_types';
        $spm = new shopPluginModel();
        $methods = $spm->listPlugins('payment');
        $plugin = waSystem::getInstance()->getPlugin($plugin_id, true);
        $namespace = wa()->getApp().'_'.$plugin_id;
        $settings = $plugin->getSettings($settings_name);
        $def_ns = $plugin->getSettings("komtet_tax_type");

        $row_tpl = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';
        $controls = <<<HTML
            <table class="zebra">
                <tr>
                    <th>Способ оплаты</th>
                    <th>Средство платежа</th>
                    <th>Чек</th>
                    <th>Система налогообложения</th>
                </tr>
        HTML;

        foreach($methods as $k => $v) {
            $params = array(
                'namespace' => $namespace,
                'title_wrapper' => '&nbsp;%s',
                'description_wrapper' => '<span class="hint">%s</span>',
                'control_wrapper' => '%2$s'."\n".'%1$s'."\n".'%3$s'."\n",
            );

            waHtmlControl::addNamespace($params, array($settings_name, $v['id']));
            $payment_type = waHtmlControl::getControl(
                waHtmlControl::CHECKBOX,
                'payment_plugin_id',
                array_merge(
                    $params,
                    array(
                        'class' => 'komtet_payment_types_class',
                        'checked' => isset($settings[$v['id']]),
                        'value' => $v['id'],
                        'title' => $v['name'],
                    )
                )
            );

            $selected_type = isset($settings[$v['id']]) ? $settings[$v['id']]['fisc_payment_type'] : Payment::TYPE_CARD;
            $payment_method = waHtmlControl::getControl(
                waHtmlControl::SELECT,
                'fisc_payment_type',
                array_merge(
                    $params,
                    array(
                        'value' => $selected_type,
                        'options' => array(
                            array(
                                'value' => Payment::TYPE_CARD,
                                'title' => 'Электронный платеж'
                            ),
                            array(
                                'value' => Payment::TYPE_CASH,
                                'title' => 'Наличные'
                            ),
                        )
                    )
                )
            );

            $selected_receipt_type = isset($settings[$v['id']]) ? $settings[$v['id']]['fisc_receipt_type'] : 'print_email';
            $receipt_type = waHtmlControl::getControl(
                waHtmlControl::SELECT,
                'fisc_receipt_type',
                array_merge(
                    $params,
                    array(
                        'value' => $selected_receipt_type,
                        'options' => array(
                            array(
                                'value' => 'email',
                                'title' => 'Только электронный чек'
                            ),
                            array(
                                'value' => 'print_email',
                                'title' => 'Печать + электронный чек'
                            ),
                        )
                    )
                )
            );

            $selected_tax_type = isset($settings[$v['id']]) ? $settings[$v['id']]['tax_type'] : $def_ns;
            $tax_type = waHtmlControl::getControl(
                waHtmlControl::SELECT,
                'tax_type',
                array_merge(
                    $params,
                    array(
                        'value' => $selected_tax_type,
                        'options' => self::taxTypesValues()
                    )
                )
            );

            $controls .= sprintf($row_tpl, $payment_type, $payment_method, $receipt_type, $tax_type);
        }

        $controls .= "</table><script type='text/javascript'>";
        $controls .= <<<JS
            $(function() {
                var enable_disable_select = function() {
                var _t = $(this);
                _t.parents('tr').find('select')
                .prop('disabled', !_t.is(':checked'));
            }
            $('input[type="checkbox"].komtet_payment_types_class')
                .on('change', enable_disable_select)
                .each(enable_disable_select)
            });
        JS;
        $controls .= '</script>';
        return $controls;
    }

    public static function getSuccessUrl() {
        $plugin_id = self::PLUGIN_ID;
        $settings_name = 'komtet_success_url';
        $plugin = waSystem::getInstance()->getPlugin($plugin_id, true);
        $namespace = wa()->getApp().'_'.$plugin_id;
        $params = array(
            'namespace' => $namespace,
            'title_wrapper' => '&nbsp;%s',
            'description_wrapper' => '<span class="hint">%s</span>',
            'control_wrapper' => '%2$s'."\n".'%1$s'."\n".'%3$s'."\n",
        );
        $success_url = waHtmlControl::getControl(
            waHtmlControl::INPUT,
            'success_url',
            array_merge(
                $params,
                array(
                    'value' => $plugin->getCallbackUrl("success", true)
                )
            )
        );
        return $success_url;
    }

    public static function getFailureUrl() {
         $plugin_id = self::PLUGIN_ID;
         $settings_name = 'komtet_failure_url';
         $plugin = waSystem::getInstance()->getPlugin($plugin_id, true);
         $namespace = wa()->getApp().'_'.$plugin_id;
         $params = array(
            'namespace' => $namespace,
            'title_wrapper' => '&nbsp;%s',
            'description_wrapper' => '<span class="hint">%s</span>',
            'control_wrapper' => '%2$s'."\n".'%1$s'."\n".'%3$s'."\n",
        );
        $failure_url = waHtmlControl::getControl(
            waHtmlControl::INPUT,
            'failure_url',
            array_merge(
                $params,
                array(
                    'value' => $plugin->getCallbackUrl("failure", true)
                )
            )
        );
        return $failure_url;
    }

    public static function getPrepaymentStates() {
        $workflow = new shopWorkflow();
        $actions = $workflow::getConfig();
        $prepaid_statuses[] = array(
            'value' => 'dontgive',
            'title' => 'Не выдавать',
        );
        foreach ($actions['states'] as $key => $v) {
            $prepaid_statuses[] = array(
                'value' => $key,
                'title' => $v['name']
            );
        }
        return $prepaid_statuses;
    }

    public static function getFullpaymentStates() {
        $workflow = new shopWorkflow();
        $actions = $workflow::getConfig();

        foreach ($actions['states'] as $key => $v) {
            $fullpayment_statuses[] = array(
                'value' => $key,
                'title' => $v['name']
            );
        }

        return $fullpayment_statuses;
    }
}
