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

COPY App/ /var/www/html/
COPY App/ /var/www/html/
COPY Core/ /var/www/Core/
 
RUN chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/


EXPOSE 80
