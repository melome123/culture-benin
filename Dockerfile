FROM php:8.3-cli

# 1. Dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# 2. Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    mbstring \
    gd \
    zip

# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Workdir
WORKDIR /var/www/html

# 5. App
COPY . .

# 6. Composer install
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Permissions
RUN chmod -R 775 storage bootstrap/cache

# 8. Port
EXPOSE 8080

# 9. Serveur
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]

