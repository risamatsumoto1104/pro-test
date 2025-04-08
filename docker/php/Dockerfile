FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

# 必要なパッケージをインストール
RUN apt-get update \
    && apt-get install -y wget curl gnupg unzip \
    && apt-get install -y default-mysql-client zlib1g-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd

# Composer のインストール
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

WORKDIR /var/www