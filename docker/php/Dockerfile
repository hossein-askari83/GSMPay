FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libssl-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip


# Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Kafka extension
RUN apt-get install -y librdkafka-dev \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www