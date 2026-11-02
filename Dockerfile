FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install \
    --working-dir=/var/www/html/misc/cms \
    --no-dev \
    --prefer-dist \
    --no-interaction

RUN sed -ri -e 's!/var/www/html!/var/www/html/misc/cms/public!g' \
    /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite

EXPOSE 10000

CMD ["apache2-foreground"]
