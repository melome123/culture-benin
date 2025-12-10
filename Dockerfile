FROM php:8.3-fpm-alpine

# ===== 1. INSTALLER NGINX ET DÉPENDANCES =====
RUN apk update && apk add --no-cache \
    nginx \
    curl

# ===== 2. INSTALLER EXTENSIONS PHP =====
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    gd \
    zip \
    opcache

# ===== 3. INSTALLER COMPOSER =====
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ===== 4. CONFIGURATION PHP-FPM =====
# Forcer PHP-FPM à écouter sur un socket Unix (plus stable)
RUN echo '[global]' > /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'error_log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo '' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'user = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'group = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'listen = /var/run/php-fpm.sock' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'listen.owner = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'listen.group = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'pm.max_children = 5' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'pm.start_servers = 2' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'pm.min_spare_servers = 1' >> /usr/local/etc/php-fpm.d/zz-railway.conf && \
    echo 'pm.max_spare_servers = 3' >> /usr/local/etc/php-fpm.d/zz-railway.conf

# ===== 5. CONFIGURATION NGINX =====
# Créer le répertoire pour les sockets Nginx
RUN mkdir -p /run/nginx

# Configuration Nginx SIMPLE avec port 8080 fixe
RUN echo 'events {' > /etc/nginx/nginx.conf && \
    echo '    worker_connections 1024;' >> /etc/nginx/nginx.conf && \
    echo '}' >> /etc/nginx/nginx.conf && \
    echo '' >> /etc/nginx/nginx.conf && \
    echo 'http {' >> /etc/nginx/nginx.conf && \
    echo '    include /etc/nginx/mime.types;' >> /etc/nginx/nginx.conf && \
    echo '    default_type application/octet-stream;' >> /etc/nginx/nginx.conf && \
    echo '' >> /etc/nginx/nginx.conf && \
    echo '    server {' >> /etc/nginx/nginx.conf && \
    echo '        listen 8080;' >> /etc/nginx/nginx.conf && \
    echo '        server_name _;' >> /etc/nginx/nginx.conf && \
    echo '        root /var/www/html/public;' >> /etc/nginx/nginx.conf && \
    echo '        index index.php index.html;' >> /etc/nginx/nginx.conf && \
    echo '' >> /etc/nginx/nginx.conf && \
    echo '        location / {' >> /etc/nginx/nginx.conf && \
    echo '            try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/nginx.conf && \
    echo '        }' >> /etc/nginx/nginx.conf && \
    echo '' >> /etc/nginx/nginx.conf && \
    echo '        location ~ \.php$ {' >> /etc/nginx/nginx.conf && \
    echo '            fastcgi_pass unix:/var/run/php-fpm.sock;' >> /etc/nginx/nginx.conf && \
    echo '            fastcgi_index index.php;' >> /etc/nginx/nginx.conf && \
    echo '            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/nginx.conf && \
    echo '            include fastcgi_params;' >> /etc/nginx/nginx.conf && \
    echo '        }' >> /etc/nginx/nginx.conf && \
    echo '    }' >> /etc/nginx/nginx.conf && \
    echo '}' >> /etc/nginx/nginx.conf

# ===== 6. RÉPERTOIRE DE TRAVAIL =====
WORKDIR /var/www/html

# ===== 7. COPIER L'APPLICATION =====
COPY . .

# ===== 8. SUPPRIMER ARTISAN (IMPORTANT!) =====
RUN rm -f artisan

# ===== 9. INSTALLER DÉPENDANCES COMPOSER =====
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# ===== 10. PERMISSIONS =====
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache && \
    chown www-data:www-data /var/run

# ===== 11. SCRIPT DE DÉMARRAGE =====
RUN echo '#!/bin/sh' > /start.sh && \
    echo 'set -e' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Créer le socket directory' >> /start.sh && \
    echo 'mkdir -p /var/run' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Démarrer PHP-FPM' >> /start.sh && \
    echo 'echo "🚀 Starting PHP-FPM..."' >> /start.sh && \
    echo 'php-fpm &' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Attendre que PHP-FPM démarre' >> /start.sh && \
    echo 'echo "⏳ Waiting for PHP-FPM..."' >> /start.sh && \
    echo 'sleep 5' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Démarrer Nginx' >> /start.sh && \
    echo 'echo "🌐 Starting Nginx on port 8080..."' >> /start.sh && \
    echo 'nginx -g "daemon off;"' >> /start.sh && \
    chmod +x /start.sh

# ===== 12. EXPOSER LE PORT =====
EXPOSE 8080

# ===== 13. COMMANDE DE DÉMARRAGE =====
CMD ["/start.sh"]