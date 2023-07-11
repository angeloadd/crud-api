ARG PHP_VERSION=8.1

# Use base php-fpm image with verson specified by argument
FROM php:${PHP_VERSION}-fpm

ARG PHP_VERSION

# Install necessaries deps including composer
RUN apt-get update && \
    apt-get install -y \
    git \
    libzip-dev \
    libpq-dev \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && docker-php-ext-install pgsql pdo_pgsql zip pdo

# Set working dir
WORKDIR /var/www/html
# Copy app code into container working dir
COPY . .

# Set entry point for application
CMD ["php", "-S", "0.0.0.0:8001", "-t", "public"]
