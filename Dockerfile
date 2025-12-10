FROM php:8.3-fpm-alpine

# ===== ÉTAPE 1: INSTALLATION DES DÉPENDANCES =====
RUN apk update && apk add --no-cache \
    bash git curl libpng-dev libjpeg-turbo-dev \
    freetype-dev libxml2-dev zip unzip postgresql-dev \
    libzip-dev oniguruma-dev nginx

# ===== ÉTAPE 2: EXTENSIONS PHP =====
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

# ===== ÉTAPE 3: COMPOSER =====
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ===== ÉTAPE 4: CONFIGURATION PHP =====
RUN echo 'memory_limit = 256M' > /usr/local/etc/php/conf.d/custom.ini && \
    echo 'upload_max_filesize = 20M' >> /usr/local/etc/php/conf.d/custom.ini && \
    echo 'post_max_size = 20M' >> /usr/local/etc/php/conf.d/custom.ini && \
    echo 'max_execution_time = 300' >> /usr/local/etc/php/conf.d/custom.ini

# ===== ÉTAPE 5: CONFIGURATION PHP-FPM =====
RUN echo '[global]' > /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'daemonize = no' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'error_log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo '' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'user = www-data' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'group = www-data' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'pm.max_children = 5' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'pm.start_servers = 2' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'pm.min_spare_servers = 1' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'pm.max_spare_servers = 3' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'catch_workers_output = yes' >> /usr/local/etc/php-fpm.d/docker.conf

# ===== ÉTAPE 6: COPIER LES FICHIERS DE DÉMARRAGE =====
# Copier le script start.sh depuis le dossier docker/
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# ===== ÉTAPE 7: RÉPERTOIRE DE TRAVAIL =====
WORKDIR /var/www/html

# ===== ÉTAPE 8: COPIER L'APPLICATION =====
COPY . .

# ===== ÉTAPE 9: CRÉER UN ARTISAN FACTICE =====
RUN echo '#!/bin/sh' > artisan && \
    echo 'echo "========================================="' >> artisan && \
    echo 'echo "🚀 Laravel App via Nginx + PHP-FPM"' >> artisan && \
    echo 'echo "========================================="' >> artisan && \
    echo 'echo ""' >> artisan && \
    echo 'echo "✅ This is a fake artisan file"' >> artisan && \
    echo 'echo "🌐 Your application is running on Nginx + PHP-FPM"' >> artisan && \
    echo 'echo "========================================="' >> artisan && \
    echo 'exit 0' >> artisan && \
    chmod +x artisan

# ===== ÉTAPE 10: INSTALLER LES DÉPENDANCES COMPOSER =====
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# ===== ÉTAPE 11: CONFIGURATION NGINX =====
RUN echo 'server {' > /etc/nginx/http.d/default.conf && \
    echo '    listen ${PORT:-8080} default_server;' >> /etc/nginx/http.d/default.conf && \
    echo '    listen [::]:${PORT:-8080} default_server;' >> /etc/nginx/http.d/default.conf && \
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

# ===== ÉTAPE 12: PERMISSIONS =====
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# ===== ÉTAPE 13: EXPOSER LE PORT =====
EXPOSE ${PORT:-8080}

# ===== ÉTAPE 14: COMMANDE DE DÉMARRAGE =====
CMD ["/usr/local/bin/start.sh"]