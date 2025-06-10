FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    nano \
    && docker-php-ext-install pdo pdo_mysql mysqli

RUN a2enmod rewrite

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier le code source
COPY . /var/www/html/
COPY docker/web/apache.conf /etc/apache2/sites-available/000-default.conf

# Fixer les droits
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Pour éviter les warnings root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer les dépendances PHP
WORKDIR /var/www/html
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

EXPOSE 80
