FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

WORKDIR /var/www/html/misc/cms

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

EXPOSE 10000

WORKDIR /var/www/html/misc/cms

CMD ["sh", "-c", "pwd && ls -la && ls -la public && php -S 0.0.0.0:${PORT} -t public"]
