FROM php:8.2-apache as php8
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install mysqli zip

WORKDIR /var/www/html
COPY php .
