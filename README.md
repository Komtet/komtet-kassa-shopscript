## Запуск проекта

* Склонируйте репозиторий включая подмодули для подтягивания SDK - git clone --recurse-submodules
* Скачать установщик Shopscript CMS - https://developers.webasyst.ru/download/

* Cоздать в корневом каталоге папку php
* Распаковать архив Shopscript CMS в папку php
* В файл /php/.htaccess добавить строчку: *php_value date.timezone 'Europe/Moscow'*

* Запустить сборку проекта
```sh
make build
```

## Установка CMS
* Запустить контейнер
```sh
make start_web7
```
* Установить права на папку php
```sh
sudo chmod -R 777 php
```
* Проект будет доступен по адресу: localhost:8110;
* Админ-панель будет доступна по адресу: http://localhost:8110/index.php/webasyst/site/#/pages/
* Настройки плагина будут доступны по адресу: http://localhost:8110/index.php/webasyst/shop/?module=plugins#/komtetkassa
  или можно зайти нажав кнопку "Shop-Script" в верхнем меню, далее "Плагины"->"Установлено" в левом меню, далее выбрать "КОМТЕТ Касса"

* Настройки подключения к бд MySQL:
```sh
Сервер: mysql
Пользователь: devuser
Пароль: devpass
БД: test_db
```
## Доступные комманды из Makefile

* Собрать проект
```sh
make build

* Запустить проект на php7.4
```sh
make start_web7
```

* Запустить проект на php8.2
```sh
make start_web8
```

* Остановить проект
```sh
make stop
```

* Установить/Обновить модуль в cms
```sh
make update
```

* Подготовить архив для загрузки в маркет
```sh
make release
```
