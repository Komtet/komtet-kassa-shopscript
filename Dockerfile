FROM php:7.4.24-apache as php7
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install mysqli zip

WORKDIR /var/www/html
COPY php .


FROM php:8.0-apache as php8
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install mysqli zip

WORKDIR /var/www/html
COPY php .
