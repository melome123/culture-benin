FROM php:8.3-fpm-alpine

# Installer les dépendances
RUN apk update && apk add --no-cache \
    bash git curl libpng-dev libjpeg-turbo-dev \
    freetype-dev libxml2-dev zip unzip postgresql-dev \
    libzip-dev oniguruma-dev nginx

# Installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration PHP
RUN echo 'memory_limit = 256M' > /usr/local/etc/php/conf.d/memory.ini && \
    echo 'upload_max_filesize = 20M' >> /usr/local/etc/php/conf.d/memory.ini && \
    echo 'post_max_size = 20M' >> /usr/local/etc/php/conf.d/memory.ini

# Configuration Nginx (directement dans le Dockerfile)
RUN echo 'server {' > /etc/nginx/http.d/default.conf && \
    echo '    listen 8080;' >> /etc/nginx/http.d/default.conf && \
    echo '    server_name _;' >> /etc/nginx/http.d/default.conf && \
    echo '    root /var/www/html/public;' >> /etc/nginx/http.d/default.conf && \
    echo '    index index.php index.html;' >> /etc/nginx/http.d/default.conf && \
    echo '' >> /etc/nginx/http.d/default.conf && \
    echo '    location / {' >> /etc/nginx/http.d/default.conf && \
    echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/http.d/default.conf && \
    echo '    }' >> /etc/nginx/http.d/default.conf && \
    echo '' >> /etc/nginx/http.d/default.conf && \
    echo '    location ~ \.php$ {' >> /etc/nginx/http.d/default.conf && \
    echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/http.d/default.conf && \
    echo '        fastcgi_index index.php;' >> /etc/nginx/http.d/default.conf && \
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/http.d/default.conf && \
    echo '        include fastcgi_params;' >> /etc/nginx/http.d/default.conf && \
    echo '    }' >> /etc/nginx/http.d/default.conf && \
    echo '' >> /etc/nginx/http.d/default.conf && \
    echo '    location ~ /\.ht {' >> /etc/nginx/http.d/default.conf && \
    echo '        deny all;' >> /etc/nginx/http.d/default.conf && \
    echo '    }' >> /etc/nginx/http.d/default.conf && \
    echo '' >> /etc/nginx/http.d/default.conf && \
    echo '    client_max_body_size 20M;' >> /etc/nginx/http.d/default.conf && \
    echo '}' >> /etc/nginx/http.d/default.conf

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

# Script de démarrage
RUN echo '#!/bin/sh' > /start.sh && \
    echo '# Démarrer PHP-FPM en arrière-plan' >> /start.sh && \
    echo 'php-fpm &' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Attendre un peu que PHP-FPM démarre' >> /start.sh && \
    echo 'sleep 2' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Démarrer Nginx en premier plan' >> /start.sh && \
    echo 'nginx -g "daemon off;"' >> /start.sh && \
    chmod +x /start.sh

# Exposer le port
EXPOSE 8080

# Commande de démarrage
CMD ["/start.sh"]