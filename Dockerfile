# Dockerfile simplifié pour Railway

FROM php:8.3-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Installer les extensions PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances Composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Exposer le port
EXPOSE 8080

# Commande de démarrage pour Railway
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]