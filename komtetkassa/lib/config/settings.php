<?php
return array(
    'komtet_api_url'  => array(
        'title'        => "URL API КОМТЕТ Касса",
        'value'        => 'https://kassa.komtet.ru',
        'description'  => array(
            "Значение по умолчанию: <b>https://kassa.komtet.ru</b><br><br>"
        ),
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_shop_id'  => array(
        'title'        => "Идентификатор магазина",
        'description'  => array(
            "Идентификатор вы найдете в личном кабинете КОМТЕТ: <a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>"
        ),
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_secret_key'  => array(
        'title'        => "Секретный ключ магазина",
        'description'  => array(
            "Ключ вы найдете в личном кабинете КОМТЕТ, в настройках выбранного магазина: " .
            "<a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>"
        ),
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_queue_id'  => array(
        'title'        => "Идентификатор очереди",
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_tax_type'  => array(
        'title'        => "Система налогообложения по умолчанию",
        'description'  => array(
            "Систему налогообложения можно задать отдельно для каждого способа оплаты (см. ниже). ".
            "Данная настройка будет учитываться для новых способов оплаты, добавленных ПОСЛЕ сохранения настроек ".
            "данного плагина.<br><br>"
        ),
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => array('shopKomtetkassa', 'taxTypesValues')
    ),

    'komtet_payment_types' => array(
        'title'        => "Способы оплаты",
        'description' => array(
            "Выбирете способы оплаты, для которых будет проводиться фискализация платежей, ".
            "какому средству оплаты соответствует выбранный способ, какой требуется чек и система налогообложения<br><br>"
        ),
        'control_type' => waHtmlControl::CUSTOM . ' shopKomtetkassa::getPaymentTypes'
    ),

    'komtet_complete_action'  => array(
        'title'        => "Включить фискализацию по событию Заказ выполнен",
        'description'  => array(
            "В некоторых случаях, когда статусы заказа меняются внешними системами, заказ может миновать статус Оплачен. ".
            "Используя данный выключатель, можно использовать для фискализации событие Заказ выполнен<br><br>"
        ),
        'value'        => 0, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),

    'komtet_use_item_discount'  => array(
        'title'        => "Учитывать скидки в позициях",
        'description'  => array(
            "Включить если используются сторонние плагины, применяющие скидки к позициям, а не ко всему чеку."
        ),
        'value'        => 0, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),

    'komtet_delivery_tax'  => array(
        'title'        => "Ставка налога услуги \"Доставка\"",
        'value'        => 'no', // значение по умолчанию
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => array('shopKomtetkassa', 'vatValues')
    ),

    'komtet_alert'  => array(
        'title'        => "Уведомлять об ошибках",
        'description'  => array(
            "В случае, если сервер KOMTET не ответил на запрос, вернул ошибку или в процессе фискализации " .
            "произошла ошибка, вы получите уведомление об этом по электронной почте<br><br>"
        ),
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),

    'komtet_alert_email'  => array(
        'title'        => "Email для уведомлений",
        'description'  => array(
            "По умолчанию используется основной e-mail магазин (см. основные настройки магазина). Настоятельно рекомендуем ".
            "использовать отдельный адрес и настроить на нем сигнализацию о новых сообщениях.<br><br>"
        ),
        'value'        => '', // значение по умолчанию
        'control_type' => waHtmlControl::INPUT,
    ),

    'komtet_success_url'  => array(
        'title'        => "Success url",
        'description'  => array(
            "Скопируйте значение данного поля и вставьте его в личном кабинете КОМТЕТ: " .
            "<a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>"
        ),
        'control_type' => waHtmlControl::CUSTOM.' shopKomtetkassa::getSuccessUrl',
    ),

    'komtet_failure_url'  => array(
        'title'        => "Failure url",
        'description'  => array(
            "Скопируйте значение данного поля и вставьте его в личном кабинете КОМТЕТ: " .
            "<a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>"
        ),
        'control_type' => waHtmlControl::CUSTOM.' shopKomtetkassa::getFailureUrl',
    ),

    'komtet_log'  => array(
        'title'        => "Включить логирование",
        'description'  => array(
            "Если логирование Включено, параметры запросов и результаты ответа сервера KOMTET будут записаны в лог: ".
            "<b>shop/plugins/komtetkassa/fiscalization.log</b>"
        ),
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),

);
//EOF
