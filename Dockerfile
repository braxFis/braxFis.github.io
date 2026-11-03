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

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN apache2ctl -t

EXPOSE 80

CMD ["apache2-foreground"]
