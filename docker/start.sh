#!/bin/sh
set -e

echo "🚀 Starting PHP-FPM..."
php-fpm &

echo "⏳ Waiting for PHP-FPM to start..."
sleep 3

echo "🌐 Starting Nginx on port ${PORT:-8080}..."
echo "✅ Application is ready!"

exec nginx -g "daemon off;"