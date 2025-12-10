FROM php:8.3-fpm-alpine

# Installer les dépendances
RUN apk update && apk add --no-cache \
    bash git curl libpng-dev libjpeg-turbo-dev \
    freetype-dev libxml2-dev zip unzip postgresql-dev \
    libzip-dev oniguruma-dev nginx supervisor

# Installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration PHP
RUN echo 'memory_limit = 256M' > /usr/local/etc/php/conf.d/memory.ini && \
    echo 'upload_max_filesize = 20M' >> /usr/local/etc/php/conf.d/memory.ini && \
    echo 'post_max_size = 20M' >> /usr/local/etc/php/conf.d/memory.ini && \
    echo 'error_log = /proc/self/fd/2' >> /usr/local/etc/php/conf.d/memory.ini

# Configuration PHP-FPM pour Railway
RUN echo '[global]' > /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'error_log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo '' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'user = www-data' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'group = www-data' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'listen = 127.0.0.1:9000' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'listen.owner = www-data' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'listen.group = www-data' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'pm.max_children = 5' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'pm.start_servers = 2' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'pm.min_spare_servers = 1' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'pm.max_spare_servers = 3' >> /usr/local/etc/php-fpm.d/railway.conf && \
    echo 'catch_workers_output = yes' >> /usr/local/etc/php-fpm.d/railway.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# **SUPPRIMER ARTISAN POUR EMPÊCHER RAILWAY DE L'EXÉCUTER**
RUN rm -f artisan

# Installer les dépendances Composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Configuration Nginx (avec variable PORT)
RUN echo 'server {' > /etc/nginx/http.d/default.conf && \
    echo '    listen ${PORT:-8080};' >> /etc/nginx/http.d/default.conf && \
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

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# Configuration Supervisor pour gérer les processus
RUN echo '[supervisord]' > /etc/supervisor/conf.d/supervisord.conf && \
    echo 'nodaemon=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'logfile=/var/log/supervisor/supervisord.log' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'pidfile=/var/run/supervisord.pid' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '[program:php-fpm]' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'command=php-fpm' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autostart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autorestart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stdout_logfile=/dev/stdout' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stdout_logfile_maxbytes=0' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stderr_logfile=/dev/stderr' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stderr_logfile_maxbytes=0' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo '[program:nginx]' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'command=nginx -g "daemon off;"' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autostart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'autorestart=true' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stdout_logfile=/dev/stdout' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stdout_logfile_maxbytes=0' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stderr_logfile=/dev/stderr' >> /etc/supervisor/conf.d/supervisord.conf && \
    echo 'stderr_logfile_maxbytes=0' >> /etc/supervisor/conf.d/supervisord.conf

# Créer un fichier de santé
RUN echo '#!/bin/sh' > /health.sh && \
    echo 'exit 0' >> /health.sh && \
    chmod +x /health.sh

# Exposer le port
EXPOSE ${PORT:-8080}

# Commande de démarrage avec Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]