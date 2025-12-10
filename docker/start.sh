#!/bin/sh

# Démarrer PHP-FPM en arrière-plan
php-fpm &

# Démarrer Nginx en premier plan
nginx -g 'daemon off;'