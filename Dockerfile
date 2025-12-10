FROM php:8.3-cli

# 1. Installer Nginx et PHP-FPM DEBIAN (plus stable)
RUN apt-get update && apt-get install -y \
    nginx \
    php8.3-fpm \
    php8.3-pgsql \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-zip \
    curl \
    && apt-get clean

# 2. Configurer PHP-FPM pour utiliser TCP au lieu de socket
RUN echo '[www]' > /etc/php/8.3/fpm/pool.d/railway.conf && \
    echo 'listen = 127.0.0.1:9000' >> /etc/php/8.3/fpm/pool.d/railway.conf && \
    echo 'listen.owner = www-data' >> /etc/php/8.3/fpm/pool.d/railway.conf && \
    echo 'listen.group = www-data' >> /etc/php/8.3/fpm/pool.d/railway.conf && \
    echo 'user = www-data' >> /etc/php/8.3/fpm/pool.d/railway.conf && \
    echo 'group = www-data' >> /etc/php/8.3/fpm/pool.d/railway.conf

# 3. Configurer Nginx POUR RAILWAY
RUN rm -f /etc/nginx/sites-enabled/default && \
    echo 'server {' > /etc/nginx/sites-available/railway && \
    echo '    listen 8080;' >> /etc/nginx/sites-available/railway && \
    echo '    server_name _;' >> /etc/nginx/sites-available/railway && \
    echo '    root /var/www/html/public;' >> /etc/nginx/sites-available/railway && \
    echo '    index index.php index.html;' >> /etc/nginx/sites-available/railway && \
    echo '' >> /etc/nginx/sites-available/railway && \
    echo '    location / {' >> /etc/nginx/sites-available/railway && \
    echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/sites-available/railway && \
    echo '    }' >> /etc/nginx/sites-available/railway && \
    echo '' >> /etc/nginx/sites-available/railway && \
    echo '    location ~ \.php$ {' >> /etc/nginx/sites-available/railway && \
    echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/sites-available/railway && \
    echo '        fastcgi_index index.php;' >> /etc/nginx/sites-available/railway && \
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/sites-available/railway && \
    echo '        include fastcgi_params;' >> /etc/nginx/sites-available/railway && \
    echo '    }' >> /etc/nginx/sites-available/railway && \
    echo '}' >> /etc/nginx/sites-available/railway && \
    ln -s /etc/nginx/sites-available/railway /etc/nginx/sites-enabled/

# 4. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Répertoire de travail
WORKDIR /var/www/html

# 6. Copier l'application
COPY . .

# 7. SUPPRIMER ARTISAN (TRÈS IMPORTANT)
RUN rm -f artisan

# 8. Installer dépendances Composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# 9. Permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# 10. Exposer le port 8080
EXPOSE 8080
# À ajouter avant le CMD pour vérifier que tout est bon
RUN echo '#!/bin/bash' > /check.sh && \
    echo 'echo "=== VÉRIFICATION ==="' >> /check.sh && \
    echo 'echo "1. PHP-FPM est-il installé?"' >> /check.sh && \
    echo 'which php-fpm8.3 && echo "✅ OUI" || echo "❌ NON"' >> /check.sh && \
    echo '' >> /check.sh && \
    echo 'echo "2. Nginx est-il installé?"' >> /check.sh && \
    echo 'which nginx && echo "✅ OUI" || echo "❌ NON"' >> /check.sh && \
    echo '' >> /check.sh && \
    echo 'echo "3. Fichier de config Nginx existe?"' >> /check.sh && \
    echo 'ls -la /etc/nginx/sites-available/' >> /check.sh && \
    echo '' >> /check.sh && \
    echo 'echo "4. Port 8080 est-il libre?"' >> /check.sh && \
    echo 'netstat -tuln | grep :8080 && echo "❌ PORT OCCUPÉ" || echo "✅ PORT LIBRE"' >> /check.sh && \
    chmod +x /check.sh
    
# 11. SCRIPT DE DÉMARRAGE ROBUSTE
CMD ["sh", "-c", "service php8.3-fpm start && nginx -g 'daemon off;'"]