
FROM php:8.3-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install PHP ZIP dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application
WORKDIR /var/www/html
COPY . .

# Install Composer dependencies in the CMS directory
WORKDIR /var/www/html/misc/cms

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/misc/cms/public

RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
