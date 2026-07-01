#!/bin/bash
set -e
echo ">> Starting frontend (production)..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm -D
exec nginx -g "daemon off;"
