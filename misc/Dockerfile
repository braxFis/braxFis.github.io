FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev libxml2-dev libcurl4-openssl-dev unzip \
    && docker-php-ext-install zip curl pdo_mysql dom \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/misc
COPY . /var/www/html/misc/
RUN composer install --working-dir=/var/www/html/misc/cms \
      --no-dev --no-interaction --no-scripts --no-plugins --prefer-dist --optimize-autoloader \
    && mkdir -p /var/www/html/misc/cms/public/uploads /var/www/html/misc/cms/var/wordpress \
    && touch /var/www/html/misc/cms/php_error.log \
    && chown -R www-data:www-data /var/www/html/misc/cms/public/uploads /var/www/html/misc/cms/var \
       /var/www/html/misc/cms/php_error.log

COPY docker-news.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 80
CMD ["apache2-foreground"]
