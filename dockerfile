# -----------------------
# Stage 1 : build frontend (Vite)
# -----------------------
FROM node:20 AS node-builder

WORKDIR /app

# Copier package.json, package-lock.json et vite.config.js
COPY package*.json vite.config.js ./

# Copier les dossiers frontend
COPY resources/js ./resources/js
COPY resources/css ./resources/css

# Installer dépendances Node
RUN npm install

# Builder les assets Vite pour Laravel
RUN npm run build

# -----------------------
# Stage 2 : backend Laravel
# -----------------------
FROM php:8.3-fpm

# Installer extensions PHP et utilitaires
RUN apt-get update && apt-get install -y \
    git unzip curl libonig-dev libzip-dev zip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier tout le projet Laravel
COPY . .

# Copier les assets buildés depuis l'étape Node
COPY --from=node-builder /app/public/build ./public/build

# Installer dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Générer clé Laravel et créer storage link
RUN php artisan key:generate
RUN php artisan storage:link

# Exposer le port PHP-FPM
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]
