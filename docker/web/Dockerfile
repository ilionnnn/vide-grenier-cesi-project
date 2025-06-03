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

# COPY . /var/www/html/
COPY . /var/www/app/

 
RUN chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/


EXPOSE 80
