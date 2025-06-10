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

COPY . /var/www/html/
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN ls -la /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

EXPOSE 81
