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

RUN sed -i 's#/var/www/html#/var/www/html/misc/cms/public#g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80

RUN apache2ctl -t

CMD ["apache2-foreground"]
