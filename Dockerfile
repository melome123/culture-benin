FROM php:8.3-cli

# Installer extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        gd \
        zip \
    && apt-get clean

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# SUPPRIMER ARTISAN et le remplacer par notre propre script
RUN rm -f artisan && \
    echo '#!/bin/sh' > artisan && \
    echo 'echo "Starting Laravel on port 8080..."' >> artisan && \
    echo 'php artisan serve --host=0.0.0.0 --port=8080' >> artisan && \
    chmod +x artisan

RUN composer install --optimize-autoloader --no-scripts --no-interaction
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Démarrer le serveur
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]