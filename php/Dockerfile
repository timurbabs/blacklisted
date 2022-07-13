FROM php:7.4-fpm

WORKDIR /var/www/html

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql

COPY ./src /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER www-data

RUN composer install

USER root

CMD [ "php", "-S", "0.0.0.0:8000", "index.php" ]
