FROM php:7.2-fpm

ARG WITH_XDEBUG=false

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

RUN echo "memory_limit=-1" > $PHP_INI_DIR/conf.d/memory-limit.ini


RUN if [ $WITH_XDEBUG = "true" ] ; then \
        pecl install xdebug; \
        docker-php-ext-enable xdebug; \
        echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_startup_errors=On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_errors=On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi ;

# Install php dependencies
COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Install app dependencies
COPY composer.json composer.lock ./
RUN composer install --no-autoloader --no-scripts

COPY . .

# Re-run composer, this time with autoloader & scripts
RUN composer install --optimize-autoloader
