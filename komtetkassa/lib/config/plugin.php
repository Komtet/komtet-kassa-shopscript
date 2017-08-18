<?php

return array(
    'name' => 'КОМТЕТ Касса',
    'description' => 'Фискализация платежей с помощью сервиса КОМТЕТ Касса',
    'version' => '1.0.0',
    'vendor' => 1087963,
    'frontend' => true,
    'handlers' => array(
        'order_action.pay' => 'fiscalize',
        'order_action.refund' => 'refund',
    ),
    'img' => 'img/icon_16x16.png'
);
//EOF
