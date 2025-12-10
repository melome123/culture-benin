FROM php:8.3-fpm-alpine

# Installer les dépendances
RUN apk update && apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    nginx \
    supervisor

# Installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration PHP
RUN echo 'memory_limit = 256M' > /usr/local/etc/php/conf.d/memory.ini && \
    echo 'upload_max_filesize = 20M' >> /usr/local/etc/php/conf.d/memory.ini && \
    echo 'post_max_size = 20M' >> /usr/local/etc/php/conf.d/memory.ini

# Configuration Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Configuration Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances Composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# Nettoyer
RUN rm -rf /var/www/html/docker

# Exposer le port
EXPOSE 8080

# Commande de démarrage
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]