# Utiliser l'image PHP officielle avec FPM pour production
FROM php:8.3-fpm

# 1. Installer les dépendances SYSTEME
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    git \
    curl \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Configurer et installer extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    mbstring \
    gd \
    zip \
    exif \
    pcntl \
    bcmath \
    xml

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Répertoire de travail
WORKDIR /var/www/html

# 5. Copier les fichiers de configuration d'abord (pour meilleur cache Docker)
COPY composer.json composer.lock ./

# 6. Installer dépendances Composer (sans dev pour production)
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# 7. Copier le reste de l'application
COPY . .

# 8. Configurer les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 9. Copier la configuration PHP pour production
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# 10. Exposer le port (Railway utilise le port 8080 par défaut)
EXPOSE 8080

# 11. Script de démarrage pour Railway
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]