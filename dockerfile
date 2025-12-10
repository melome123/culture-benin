# -----------------------------------------
# Stage 1 : Build Frontend (Vite)
# -----------------------------------------
FROM node:20 AS node-builder
WORKDIR /app

COPY package*.json vite.config.js ./
RUN npm install

COPY resources ./resources
RUN npm run build


# -----------------------------------------
# Stage 2 : Install PHP dependencies
# -----------------------------------------
FROM php:8.3-fpm AS php-builder

RUN apt-get update && apt-get install -y \
    zip unzip git curl libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
COPY composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader || true


# -----------------------------------------
# Stage 3 : Final Image (Laravel + Nginx)
# -----------------------------------------
FROM nginx:alpine

# Copier la config Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Installer PHP-FPM 8.3
RUN apk add --no-cache php83 php83-fpm php83-opcache php83-pdo_pgsql php83-tokenizer php83-xml php83-mbstring php83-zip

# Copier Laravel
WORKDIR /var/www/html
COPY . .
COPY --from=node-builder /app/resources ./resources
COPY --from=node-builder /app/dist ./public/build
COPY --from=php-builder /var/www/html/vendor ./vendor

# Droits
RUN chown -R nginx:nginx /var/www/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
