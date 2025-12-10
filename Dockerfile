# Utiliser l'image PHP officielle avec FPM pour production
FROM php:8.3-fpm

# 1. Installer les dépendances SYSTEME
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    git \
    curl \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Configurer et installer extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    mbstring \
    gd \
    zip \
    exif \
    pcntl \
    bcmath \
    xml

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Répertoire de travail
WORKDIR /var/www/html

# 5. Copier les fichiers de configuration d'abord (pour meilleur cache Docker)
COPY composer.json composer.lock ./

# 6. Installer dépendances Composer (sans dev pour production)
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# 7. Copier le reste de l'application
COPY . .

# ... (toutes les étapes précédentes restent les mêmes jusqu'à l'étape 7)

# 8. Créer un script de démarrage
RUN echo '#!/bin/bash\n\
\n\
# Désactiver le lancement automatique de Laravel Serve\n\
export LARAVEL_SAIL=0\n\
\n\
# Générer la clé si nécessaire\n\
if [ ! -f ".env" ]; then\n\
    cp .env.example .env\n\
    php artisan key:generate\n\
fi\n\
\n\
# Démarrer le serveur PHP\n\
exec php -S 0.0.0.0:8080 -t public\n\
' > /usr/local/bin/start.sh && \
chmod +x /usr/local/bin/start.sh

# 9. Exposer le port
EXPOSE 8080

# 10. Utiliser le script de démarrage
CMD ["/usr/local/bin/start.sh"]