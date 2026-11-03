FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install \
    --working-dir=/var/www/html/misc/cms \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

ENV APACHE_DOCUMENT_ROOT=/var/www/html/misc/cms/public

RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]
