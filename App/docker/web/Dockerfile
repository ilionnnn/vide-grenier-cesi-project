FROM php:8.2-apache

# Installer les extensions PHP nécessaires (pdo, mysqli, etc.)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Activer mod_rewrite pour Apache (utile pour les frameworks MVC)
RUN a2enmod rewrite

# Copier le code source dans le conteneur
COPY . /var/www/html/

# Donner les bons droits (optionnel)
RUN chown -R www-data:www-data /var/www/html

# Configurer le DocumentRoot si besoin (optionnel)
# ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

EXPOSE 80