FROM php:8.3-fpm

# Extensions nécessaires Laravel 12 + PostgreSQL
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier le projet
COPY . .

# Installer les dépendances Laravel 12
RUN composer install --no-dev --optimize-autoloader

# Optimisations
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

EXPOSE 9000
CMD ["php-fpm"]
