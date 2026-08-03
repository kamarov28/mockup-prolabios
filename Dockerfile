FROM php:8.4-apache

# Install system dependencies & PHP extensions (GD with WebP support, PDO MySQL, Zip, Redis, OPcache)
RUN apt-get update && apt-get install -y \
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
    && docker-php-ext-install pdo pdo_mysql gd zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

# Configure OPcache for Windows Docker performance (with 2s timestamp revalidation for dev edits)
RUN echo "opcache.enable=1\n\
opcache.enable_cli=1\n\
opcache.memory_consumption=256\n\
opcache.interned_strings_buffer=32\n\
opcache.max_accelerated_files=20000\n\
opcache.revalidate_freq=2\n\
opcache.validate_timestamps=1\n\
opcache.fast_shutdown=1" > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Enable Apache Mod_Rewrite for Laravel routing & disable DNS HostnameLookups
RUN a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "HostnameLookups Off" >> /etc/apache2/apache2.conf

# Set Apache DocumentRoot to Laravel /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run optimized autoloader inside image
RUN composer dump-autoload --optimize --no-interaction

# Set permissions for storage & bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
