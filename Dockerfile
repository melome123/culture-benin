# Utiliser l'image PHP officielle
FROM php:8.3-cli

# 1. Installer les dépendances SYSTEME
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Configurer et installer extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    gd \
    zip

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Répertoire de travail
WORKDIR /var/www/html

# 5. Copier l'application
COPY . .

# 6. Installer dépendances Composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# 7. Permissions
RUN chmod -R 775 storage bootstrap/cache

# 8. Exposer le port
EXPOSE 8080

# 9. Démarrer DIRECTEMENT le serveur PHP (PAS de script)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]