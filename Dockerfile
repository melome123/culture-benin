FROM php:8.3-fpm-alpine

# Installer Nginx et extensions
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev

# Configurer et installer extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
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
RUN echo 'memory_limit = 256M' > /usr/local/etc/php/conf.d/custom.ini

# Configuration PHP-FPM
RUN echo '[global]' > /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'daemonize = no' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo '' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Configuration Nginx
RUN echo 'server {' > /etc/nginx/http.d/default.conf && \
    echo '    listen ${PORT:-8080};' >> /etc/nginx/http.d/default.conf && \
    echo '    root /var/www/html/public;' >> /etc/nginx/http.d/default.conf && \
    echo '    index index.php;' >> /etc/nginx/http.d/default.conf && \
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
    echo '}' >> /etc/nginx/http.d/default.conf

# Configuration Supervisor
RUN echo '[supervisord]' > /etc/supervisor.conf && \
    echo 'nodaemon=true' >> /etc/supervisor.conf && \
    echo '' >> /etc/supervisor.conf && \
    echo '[program:php-fpm]' >> /etc/supervisor.conf && \
    echo 'command=php-fpm' >> /etc/supervisor.conf && \
    echo 'autostart=true' >> /etc/supervisor.conf && \
    echo 'autorestart=true' >> /etc/supervisor.conf && \
    echo '' >> /etc/supervisor.conf && \
    echo '[program:nginx]' >> /etc/supervisor.conf && \
    echo 'command=nginx -g "daemon off;"' >> /etc/supervisor.conf && \
    echo 'autostart=true' >> /etc/supervisor.conf && \
    echo 'autorestart=true' >> /etc/supervisor.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier l'application
COPY . .

# Supprimer artisan pour empêcher Railway de l'exécuter
RUN rm -f artisan

# Installer les dépendances
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Exposer le port
EXPOSE ${PORT:-8080}

# Démarrer avec Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor.conf"]