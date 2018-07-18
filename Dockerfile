FROM php:5.6-apache

COPY ./public /var/www/html
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update -y && apt-get install -y \
	libmcrypt-dev \
	libpng-dev

RUN docker-php-ext-install \
	mbstring \
	pdo \
	pdo_mysql \
	gd \
	mcrypt \
	zip \
	&& a2enmod rewrite

RUN chown -R www-data:www-data /var/www

# # Install php dependencies
COPY --from=composer:1.5 /usr/bin/composer /usr/bin/composer
# Install app dependencies
COPY composer.json /var/www/composer.json
COPY database /var/www/database
COPY tests/TestCase.php /var/www/tests/TestCase.php

WORKDIR /var/www
RUN composer install --no-plugins --no-scripts --no-interaction
# todo: composer stuff can be moved to 'build' docker file for local dev
# keep this as base dockerfile without composer stuff
