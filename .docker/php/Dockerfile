FROM php:8.3-fpm
USER root

RUN apt-get update && apt-get install -y \
    libicu-dev \
    apt-utils \
    libpq-dev \
    curl \
    iptables-persistent \
    libzip-dev \
    libfreetype6-dev \
    zip \
    unzip \
    iputils-ping \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install intl \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install exif

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www/rcapp
