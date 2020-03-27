<?php
$model = new waModel();

try {
	$sql = "SELECT `fiscalised` FROM `shop_order` WHERE 0";
	$model->query($sql);
} catch (waDbException $ex) {
	$sql = "ALTER TABLE `shop_order` ADD COLUMN `fiscalised` TINYINT(1) NOT NULL DEFAULT '0' AFTER `state_id`";
	$model->query($sql);
}

try {
    $res = $model->query("SELECT * FROM shop_product_code WHERE code='nomenclature_code'");
    if ($res->count() === 0) {
        throw new waDbException('Нет записей');
    }
} catch (waDbException $e) {
    $model->exec("INSERT INTO shop_product_code (code, name) VALUES ('nomenclature_code', 'test')");
}

try {
    $model->query('SELECT check_type FROM shop_order WHERE 0');
} catch (waDbException $e) {
    $sql = 'ALTER TABLE shop_order ADD check_type VARCHAR(25) AFTER fiscalised';
    $model->exec($sql);
}

// Скрытый (внутренний) экшн только для добавления записи в историю по заказу.
$ACTION_ID = 'fiscalise_internal_action';

$wCfg = shopWorkflow::getConfig();
if(!isset($wCfg['actions'][$ACTION_ID])) {
    $wCfg['actions'][$ACTION_ID] = array(
        'name' => 'Фискализировать',
        'options' => array(
            'position' => '',
            'button_class' => '',
            'log_record' => 'Чек по заказу фискализирован',
        ),
        'state' => null,
        'classname' => 'shopWorkflowAction',
        'internal' => true,
        'id' => $ACTION_ID
    );

    $enabled_state_ids = array('paid', 'completed');
    foreach ($enabled_state_ids as $state_id) {
        if (isset($wCfg['states'][$state_id]['available_actions'])
            && !in_array($ACTION_ID, $wCfg['states'][$state_id]['available_actions'])) {
            $wCfg['states'][$state_id]['available_actions'][] = $ACTION_ID;
        }
    }
    shopWorkflow::setConfig($wCfg);
}
