FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli

RUN a2enmod rewrite

COPY ../../App/Home/ /App/Home/
RUN chown -R www-data:www-data /App/Home/ \
    && chmod -R 755 /App/Home/

EXPOSE 80
