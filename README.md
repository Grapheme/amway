==============================
Требования к серверу для проектов
==============================

PHP 5.4+
php.ini: short_open_tag=On
Mcrypt PHP Extension
OpenSSL PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
JSON PHP extension

MySQL 5.5+

Веб-сервер Apache 2.2+ с активным модулем mod_rewrite

Установленные на сервере приложения:
tar, gzip, zip
curl, git, mc, nano

==============================
Установка
==============================
Все команды выполняются из корня приложения!

0) Клонируем проект из репозитория на GitHub или BitBucket
git clone https://github.com/........git
Точный адрес репозитория уточнить у разработчика!

1) Устанавливаем менеджер зависимостей Composer:
curl -sS https://getcomposer.org/installer | php
или
php -r "readfile('https://getcomposer.org/installer');" | php

2) Устанавливаем зависимости:
php composer.phar install

3) Выполняем из корня проекта следующую команду:
chmod -R 777 app/storage/ && chmod -R 777 public/uploads/

4) Создаем базу данных MySQL, в директории /app/config/ переименовываем файл database-sample.php в database.php и вписываем данные для доступа к БД вместо __DBNAME__, __UDBSER__, __DBPASS__.

5) Заливаем имеющийся дамп БД
ИЛИ
выполняем команду
php artisan migrate --seed
ДЛЯ ВЫБОРА НУЖНОГО ВАРИАНТА ПОСОВЕТУЙТЕСЬ С РАЗРАБОТЧИКАМИ!