# Multi-stage Dockerfile: Frontend asset builder + Production PHP Apache
# Stage 1: Build frontend assets with Node
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources resources
COPY vite.config.js ./
RUN npm run build

# Stage 2: Production PHP Apache
FROM php:8.4-apache

# Install system dependencies & PHP extensions (GD WebP/JPEG, PDO MySQL, Zip, Redis, OPcache)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure Production OPcache
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=32'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Production PHP settings
RUN { \
    echo 'upload_max_filesize = 32M'; \
    echo 'post_max_size = 32M'; \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 60'; \
    echo 'expose_php = Off'; \
} > /usr/local/etc/php/conf.d/custom-php.ini

# Apache Configuration
RUN a2enmod rewrite headers \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "HostnameLookups Off" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first for layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# Copy application source
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1
COPY --from=frontend /app/public/build /var/www/html/public/build

# Finish composer autoload
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev --no-scripts

# Setup entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
