FROM php:8.3-fpm
USER root

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      curl \
      libpng-dev \
      iptables-persistent \
      libzip-dev \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      zip unzip \
      iputils-ping \
      git \
      nodejs \
      npm && \
      docker-php-ext-install pdo_pgsql pgsql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-configure gd --with-freetype --with-jpeg && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      docker-php-ext-install exif && \
      pecl install xdebug && \
      docker-php-ext-enable xdebug && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www/rcapp
