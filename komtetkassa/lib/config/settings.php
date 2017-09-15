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

    'komtet_print_check'  => array(
        'title'        => "Печатать бумажный чек?",
        'description'  => array(
            "Вы можете включить или выключить печать бумажного чека."
        ),
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::CHECKBOX,
    ),

    'komtet_queue_id'  => array(
        'title'        => "Идентификатор очереди",
        'value'        => '', // значение по умолчанию
        'control_type'=> waHtmlControl::INPUT,
    ),

    'komtet_tax_type'  => array(
        'title'        => "Система налогооблажения",
        'value'        => 1, // значение по умолчанию
        'control_type' => waHtmlControl::SELECT,
        'options' => array(
            array(
                'value' => 0,
                'title' => 'ОСН',
            ),
            array(
                'value' => 1,
                'title' => 'УСН доход',
            ),
            array(
                'value' => 2,
                'title' => 'УСН доход - расход',
            ),
            array(
                'value' => 3,
                'title' => 'ЕНВД',
            ),
            array(
                'value' => 4,
                'title' => 'ЕСН',
            ),
            array(
                'value' => 5,
                'title' => 'Патент',
            ),
        ),
    ),

    'komtet_delivery_tax'  => array(
        'title'        => "Ставка налога услуги Доставка, %",
        'value'        => 0, // значение по умолчанию
        'control_type' => waHtmlControl::INPUT,
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
