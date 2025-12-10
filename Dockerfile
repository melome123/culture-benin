FROM php:8.3-fpm-alpine

# Install everything
RUN apk update && apk add --no-cache \
    bash git curl libpng-dev libjpeg-turbo-dev \
    freetype-dev libxml2-dev zip unzip postgresql-dev \
    libzip-dev oniguruma-dev nginx

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# DELETE ARTISAN COMPLETELY - FORCE RAILWAY TO USE OUR CMD
RUN rm -f artisan

RUN composer install --optimize-autoloader --no-scripts --no-interaction

# PHP-FPM config
RUN echo '[global]' > /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'daemonize = no' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/docker.conf && \
    echo 'listen = 9000' >> /usr/local/etc/php-fpm.d/docker.conf

# Nginx config with PORT variable
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
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/http.d/default.conf && \
    echo '        include fastcgi_params;' >> /etc/nginx/http.d/default.conf && \
    echo '    }' >> /etc/nginx/http.d/default.conf && \
    echo '}' >> /etc/nginx/http.d/default.conf

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

EXPOSE ${PORT:-8080}

# Start both services
CMD sh -c "php-fpm && nginx -g 'daemon off;'"