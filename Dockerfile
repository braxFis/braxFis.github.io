FROM php:8.3-apache

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html/misc/cms

EXPOSE 80

CMD ["apache2-foreground"]
