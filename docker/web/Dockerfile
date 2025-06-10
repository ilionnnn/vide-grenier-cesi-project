FROM php:8.2-apache

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

# Copie d'abord composer.json et composer.lock pour le cache Docker
COPY composer.json composer.lock /var/www/html/

WORKDIR /var/www/html

# Installer les dépendances PHP AVANT de copier tout le code source (meilleur cache)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copier le reste du code source
COPY . /var/www/html/

COPY docker/web/apache.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

EXPOSE 80
