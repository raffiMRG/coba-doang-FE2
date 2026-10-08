#!/bin/bash
set -e
echo ">> Starting frontend (production)..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

# nginx.production.conf proxies /status/events straight to the Go backend.
: "${API_BASEURL:?API_BASEURL must be set}"
sed -i "s|\${API_BASEURL}|${API_BASEURL%/}|g" /etc/nginx/sites-enabled/default

php-fpm -D
exec nginx -g "daemon off;"
