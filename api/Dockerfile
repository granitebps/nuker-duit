FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    libzip-dev \
    zlib1g-dev \
    libpq-dev

# Install supervisor
RUN apt-get install -y supervisor

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install from PECL
RUN pecl install redis-5.3.7

# Install PHP extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd intl sockets zip

# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl sockets zip

# Enable installed extension
RUN docker-php-ext-enable redis

# Copy www.conf custom to override default settings
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy code to /var/www
COPY . /var/www

RUN chown -R www-data:www-data /var/www

RUN cp docker/supervisord.conf /etc/supervisord.conf
RUN cp docker/php.ini /usr/local/etc/php/conf.d/php.ini
RUN cp docker/nginx.conf /etc/nginx/nginx.conf

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
RUN composer install --optimize-autoloader --no-dev

EXPOSE 80

# RUN php artisan db:seed

ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]