# -----------------------
# Stage 1 : build frontend (Vite)
# -----------------------
FROM node:20 AS node-builder

WORKDIR /app

# Copier package.json et package-lock.json
COPY package*.json ./

# Installer les dépendances frontend
RUN npm install

# Copier le reste des fichiers frontend (resources/js, etc.)
COPY resources/ resources/

# Builder les assets Vite
RUN npm run build

# -----------------------
# Stage 2 : backend Laravel
# -----------------------
FROM php:8.3-fpm

# Installer extensions PHP et utilitaires
RUN apt-get update && apt-get install -y \
    git unzip curl libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier tout le projet Laravel
COPY . .

# Copier les assets frontend buildés depuis l'étape Node
COPY --from=node-builder /app/dist public/

# Installer dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Générer clé Laravel et créer storage link
RUN php artisan key:generate
RUN php artisan storage:link

# Exposer le port PHP-FPM
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]
