FROM php:8.3-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install system dependencies and PHP ZIP extension
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/misc/cms/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application
WORKDIR /var/www/html
COPY . .

WORKDIR /var/www/html/misc/cms

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

RUN echo "CHECKING AUTOLOAD" \
    && pwd \
    && ls -la /var/www/html/misc/cms/ \
    && ls -la /var/www/html/misc/cms/vendor/ \
    && test -f /var/www/html/misc/cms/vendor/autoload.php \
    && echo "AUTOLOAD EXISTS"
    
# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Configure Apache to use Render's port
EXPOSE 10000

CMD ["sh", "-c", "\
PORT=${PORT:-10000}; \
sed -ri \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf; \
sed -ri \"s/<VirtualHost \\*:80>/<VirtualHost *:${PORT}>/\" /etc/apache2/sites-available/000-default.conf; \
echo \"Apache starting on port ${PORT}\"; \
exec apache2-foreground"]

