komtet_kassa_shopscript
===============================

Чтобы развернуть окружение и потестировать плагин в docker контейнере:
1. создать в дирректории docker_env папку php, скопировать в нее текущий код CMS Shop-Script
2. создать дирректорию для хранения данных БД /srv/docker_volumes/mysql_data/komtet_kassa_shopscript
3. выполнить docker-compose up -d
4. перейти в браузере на localhost:8100 и выполнить установку CMS
5. установить и настроить плагин через инсталлер