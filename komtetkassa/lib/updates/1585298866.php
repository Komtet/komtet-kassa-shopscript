<?php
$model = new waModel();

try {
     $res = $model->query("INSERT INTO shop_product_code (code, name)
     SELECT * FROM (SELECT 'nomenclature_code', 'Код номенклатуры') AS tmp
     WHERE NOT EXISTS (
        SELECT name FROM shop_product_code WHERE code='nomenclature_code' and name='Код номенклатуры' ) LIMIT 1");
} catch (waDbException $e) {
    waLog::log("Table shop_product_code not found\n",  'db.log');
}

try {
    $model->query('SELECT check_type FROM shop_order WHERE 0');
} catch (waDbException $e) {
    $sql = 'ALTER TABLE shop_order ADD check_type VARCHAR(25) AFTER fiscalised';
    $model->exec($sql);
}
