# Stage 1 : build frontend (Vite)
FROM node:20 AS node-builder
WORKDIR /app
COPY package*.json vite.config.js ./
RUN npm install
COPY resources ./resources
COPY src ./src
RUN npm run build

# Stage 2 : backend Laravel
FROM php:8.3-fpm
RUN apt-get update && apt-get install -y \
    git unzip curl libonig-dev libzip-dev zip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
COPY --from=node-builder /app/public/build ./public/build
RUN composer install --optimize-autoloader --no-dev
RUN php artisan key:generate
RUN php artisan storage:link
EXPOSE 9000
CMD ["php-fpm"]
