#!/bin/bash

# Générer la clé d'application Laravel si elle n'existe pas
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Configurer le cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Démarrer le serveur PHP pour Railway
php -S 0.0.0.0:8080 -t public