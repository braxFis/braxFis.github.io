FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN docker-php-ext-install zip \
 && apt-get update \
 && apt-get install -y libcurl4-openssl-dev \
 && docker-php-ext-install curl

RUN sed -ri -e 's!/var/www/html!/var/www/html/misc/cms/public!g' \
    /etc/apache2/sites-available/*.conf

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
