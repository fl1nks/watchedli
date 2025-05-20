FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install zip mysqli pdo pdo_mysql && \
    docker-php-ext-enable zip

EXPOSE 80