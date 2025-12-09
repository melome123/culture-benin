# Utiliser PHP 8.3 avec Apache
FROM php:8.3-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le projet dans le conteneur
COPY . .

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances Laravel (sans les dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Générer les assets frontend si tu utilises Vite
RUN npm install && npm run build

# Exposer le port Railway
EXPOSE 80

# Commande de démarrage
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
