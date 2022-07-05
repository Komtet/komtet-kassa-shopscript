<?php
$model = new waModel();

try {
    $sql = "UPDATE shop_product_code IF EXISTS SET code = 'chestnyznak' 
            WHERE code = 'nomenclature_code'";
    $model->exec($sql);
} catch (waDbException $e) {
    waLog::log("Error when trying to rename code field\n",  'db.log');
}
