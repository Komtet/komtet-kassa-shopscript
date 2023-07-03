<?php
$model = new waModel();

try {
    $sql = "
        UPDATE wa_app_settings AS was
            SET value = (CASE
                            WHEN was.value = 'complete'
                                THEN 'completed'
                            WHEN was.value = 'pay'
                                THEN 'paid'
                            WHEN was.value = 'ship'
                                THEN 'shipped'
                         END)
            WHERE was.name = 'status_check_fullpayment'
                AND was.app_id = 'shop.komtetkassa';

        UPDATE wa_app_settings AS was
        SET value = 'paid'
        WHERE was.name = 'status_check_prepayment'
            AND was.app_id = 'shop.komtetkassa'
            AND was.value = 'pay';
        ";
    $model->exec($sql);
} catch (waDbException $e) {
    waLog::log("Error while updating triggering statuses\n",  'db.log');
}
