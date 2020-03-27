<?php
$model = new waModel();

try {
    $res = $model->query("SELECT * FROM shop_product_code WHERE code='nomenclature_code'");
    if ($res->count() === 0) {
        throw new waDbException('Нет записей');
    }
} catch (waDbException $e) {
    $model->exec("INSERT INTO shop_product_code (code, name) VALUES ('nomenclature_code', 'Код номенклатуры')");
}

try {
    $model->query('SELECT check_type FROM shop_order WHERE 0');
} catch (waDbException $e) {
    $sql = 'ALTER TABLE shop_order ADD check_type VARCHAR(25) AFTER fiscalised';
    $model->exec($sql);
}