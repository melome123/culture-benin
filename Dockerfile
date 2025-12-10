# Utiliser Apache au lieu de Nginx
FROM php:8.3-apache

# Installer extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        gd \
        zip \
        opcache \
    && apt-get clean

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Configurer Apache pour Laravel
RUN echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf && \
    echo '  DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '  <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '  </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Changer le port d'écoute d'Apache
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

WORKDIR /var/www/html
COPY . .

# Supprimer artisan
RUN rm -f artisan

RUN composer install --optimize-autoloader --no-scripts --no-interaction
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Démarrer Apache
CMD ["apache2-foreground"]