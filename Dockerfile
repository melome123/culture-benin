FROM php:8.3-cli

# Installer uniquement les dépendances ESSENTIELLES
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd zip \
    && apt-get clean

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Installer dépendances
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Démarrer le serveur PHP intégré (LE PLUS SIMPLE!)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]