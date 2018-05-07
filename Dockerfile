FROM php:5.6-apache

COPY . /var/www
COPY ./public /var/www/html
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www

RUN apt-get update -y && apt-get -y install libpng-dev

RUN docker-php-ext-install mbstring pdo pdo_mysql gd \
	&& a2enmod rewrite


RUN chown -R www-data:www-data /var/www


# Install php dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# COPY --from=composer:1.5 /usr/bin/composer /usr/bin/composer
# Install app dependencies
RUN cd /var/www && \
    composer install --no-plugins --no-scripts --no-interaction

# todo: composer stuff can be moved to 'build' docker file for local dev
# keep this as base dockerfile without composer stuff