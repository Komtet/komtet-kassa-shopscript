<?php
return array(
    'komtet_shop_id'  => array(
        'title'        => "ID магазина",
        'description'  => "Идентификатор вы найдете в личном кабинете КОМТЕТ: <a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>",
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_secret_key'  => array(
        'title'        => "Секретный ключ магазина",
        'description'  => "Ключ вы найдете в личном кабинете КОМТЕТ: " .
                          "<a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>",
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_queue_id'  => array(
        'title'        => "ID очереди",
        'description'  => "Идентификатор вы найдете в личном кабинете КОМТЕТ: <a href='https://kassa.komtet.ru/manage/poses'>Кассы</a><br><br>",
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_tax_type'  => array(
        'title'        => "Система налогообложения по умолчанию",
        'description'  => "Систему налогообложения можно задать отдельно для каждого способа оплаты (см. ниже). ".
                          "Данная настройка будет учитываться для новых способов оплаты, добавленных ПОСЛЕ сохранения настроек ".
                          "данного плагина.<br><br>",
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => array('shopKomtetkassa', 'taxTypesValues')
    ),

    'status_check_prepayment'  => array(
        'title'        => "Чек предоплаты",
        'value'        => 'dontgive', // значение по умолчанию
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => array('shopKomtetkassa', 'getPrepaymentStates'),
        'description'  => "Статусы заказа, на которые формируется чек 100% предоплаты. Детальнее о порядке выдачи двух".
                           "чеков читайте в нашей <b><a href='https://kassa.komtet.ru/blog/predoplata_i_polniy_rasschet'>статье</a></b>.<br><br>",
    ),

    'status_check_fullpayment'  => array(
        'title'        => "Чек полного расчета",
        'value'        => 'pay', // значение по умолчанию
        'control_type' => waHtmlControl::SELECT,
        'options_callback' => array('shopKomtetkassa', 'getFullpaymentStates'),
        'description'  => "Статусы заказа, на которые формируется чек полной оплаты.<br><br>",
    ),

    'komtet_payment_types' => array(
        'title'        => "Способы оплаты",
        'description' => "Выбирете способы оплаты, для которых будет проводиться фискализация платежей, ".
                         "какому средству оплаты соответствует выбранный способ, какой требуется чек и система налогообложения<br><br>",
        'control_type' => waHtmlControl::CUSTOM . ' shopKomtetkassa::getPaymentTypes'
    ),

    'komtet_use_item_discount'  => array(
        'title'        => "Учитывать скидки в позициях",
        'description'  => "Включить если используются сторонние плагины, применяющие скидки к позициям, а не ко всему чеку.",
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
        'description'  => "В случае, если сервер KOMTET не ответил на запрос, вернул ошибку или в процессе фискализации " .
                          "произошла ошибка, вы получите уведомление об этом по электронной почте<br><br>",
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),

    'komtet_alert_email'  => array(
        'title'        => "Email для уведомлений",
        'description'  => "По умолчанию используется основной e-mail магазин (см. основные настройки магазина). Настоятельно рекомендуем ".
                          "использовать отдельный адрес и настроить на нем сигнализацию о новых сообщениях.<br><br>",
        'value'        => '', // значение по умолчанию
        'control_type' => waHtmlControl::INPUT,
    ),

    'komtet_success_url'  => array(
        'title'        => "Success url",
        'description'  => "Скопируйте значение данного поля и вставьте его в личном кабинете КОМТЕТ: " .
                          "<a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>",
        'control_type' => waHtmlControl::CUSTOM.' shopKomtetkassa::getSuccessUrl',
    ),

    'komtet_failure_url'  => array(
        'title'        => "Failure url",
        'description'  => "Скопируйте значение данного поля и вставьте его в личном кабинете КОМТЕТ: " .
                          "<a href='https://kassa.komtet.ru/manage/shops'>Магазины</a><br><br>",
        'control_type' => waHtmlControl::CUSTOM.' shopKomtetkassa::getFailureUrl',
    ),

    'komtet_log'  => array(
        'title'        => "Включить логирование",
        'description'  => "Если логирование Включено, параметры запросов и результаты ответа сервера KOMTET будут записаны в лог: ".
                          "<b>shop/plugins/komtetkassa/fiscalization.log</b><p>",
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),
);
//EOF
