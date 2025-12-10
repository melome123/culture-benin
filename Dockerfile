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

WORKDIR /var/www/html
COPY . .

# **CRÉER UN ARTISAN FACTICE POUR TRUMPER RAILWAY**
RUN echo '#!/bin/sh' > artisan && \
    echo 'echo "This is a fake artisan file to trick Railway"' >> artisan && \
    echo 'echo "Real server is running on Nginx + PHP-FPM"' >> artisan && \
    echo 'echo "Your app should be available at http://localhost:${PORT:-8080}"' >> artisan && \
    echo 'exit 0' >> artisan && \
    chmod +x artisan

# Installer les dépendances Composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Configuration PHP-FPM
RUN echo '[global]' > /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'daemonize = no' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/docker.conf

# Configuration Nginx
RUN echo 'server {' > /etc/nginx/http.d/default.conf && \
    echo '    listen ${PORT:-8080};' >> /etc/nginx/http.d/default.conf && \
    echo '    root /var/www/html/public;' >> /etc/nginx/http.d/default.conf && \
    echo '    index index.php;' >> /etc/nginx/http.d/default.conf && \
    echo '    location / {' >> /etc/nginx/http.d/default.conf && \
    echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/http.d/default.conf && \
    echo '    }' >> /etc/nginx/http.d/default.conf && \
    echo '    location ~ \.php$ {' >> /etc/nginx/http.d/default.conf && \
    echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/http.d/default.conf && \
    echo '        fastcgi_index index.php;' >> /etc/nginx/http.d/default.conf && \
    echo '        include fastcgi_params;' >> /etc/nginx/http.d/default.conf && \
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/http.d/default.conf && \
    echo '    }' >> /etc/nginx/http.d/default.conf && \
    echo '}' >> /etc/nginx/http.d/default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Script de démarrage
RUN echo '#!/bin/sh' > /start.sh && \
    echo '# Start PHP-FPM' >> /start.sh && \
    echo 'php-fpm &' >> /start.sh && \
    echo '# Start Nginx' >> /start.sh && \
    echo 'nginx -g "daemon off;"' >> /start.sh && \
    chmod +x /start.sh

EXPOSE ${PORT:-8080}
CMD ["/start.sh"]