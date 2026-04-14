#!/bin/sh
set -e
cd /var/www
composer install --no-interaction --optimize-autoloader
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    php artisan key:generate --force
fi
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan serve --host=0.0.0.0 --port=8000
