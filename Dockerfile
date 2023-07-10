FROM php:5.6.38-apache as php5
RUN docker-php-ext-install mysql

WORKDIR /var/www/html
COPY php .


FROM php:7.4.24-apache as php7
RUN docker-php-ext-install mysqli

WORKDIR /var/www/html
COPY php .


FROM php:8.0-apache as php8
RUN docker-php-ext-install mysqli

WORKDIR /var/www/html
COPY php .
