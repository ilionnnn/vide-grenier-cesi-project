FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Définir le bon DocumentRoot (Apache doit servir /var/www/public)
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Ajouter un accès Apache autorisé pour tous (utile pour les .htaccess et public/)
RUN echo '<Directory "/var/www/public">\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>' > /etc/apache2/conf-available/docker-allow.conf \
    && a2enconf docker-allow

# Copier le code source dans le conteneur (tout le projet)
COPY . /var/www/

# Donner les bons droits (optionnel mais conseillé)
RUN chown -R www-data:www-data /var/www

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

EXPOSE 80