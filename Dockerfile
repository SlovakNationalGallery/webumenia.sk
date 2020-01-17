FROM php:7.2-fpm

RUN apt-get update -y && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    git

RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install \
    pdo_mysql \
    gd \
    zip \
    exif

RUN chown -R www-data:www-data /var/www

# # Install php dependencies
COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer
# Install app dependencies
COPY composer.json /var/www/composer.json
COPY database /var/www/database
COPY tests/TestCase.php /var/www/tests/TestCase.php

WORKDIR /var/www
