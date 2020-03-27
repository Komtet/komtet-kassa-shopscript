<?php

return array(
    'name' => 'КОМТЕТ Касса',
    'description' => 'Фискализация платежей с помощью сервиса КОМТЕТ Касса',
    'version' => '1.2.13',
    'vendor' => 1087963,
    'frontend' => true,
    'handlers' => array(
        'order_action.pay' => 'order_action',
        'order_action.complete' => 'order_action',
        'order_action.ship' => 'order_action',
        'order_action.refund' => 'order_action',
        'backend_orders' => 'backend_orders'
    ),
    'img' => 'img/icon_16x16.png'
);
//EOF
