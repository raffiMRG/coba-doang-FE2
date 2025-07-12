#!/bin/bash
echo ">> Running Laravel Entrypoint..."

# # Pastikan permission storage dan cache benar
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Jalankan migrasi jika file .env sudah ada
if [ -f /var/www/.env ]; then
  echo "Running migrations..."
  docker-php-ext-install pdo pdo_mysql
  php artisan migrate --force
else
  echo ".env not found, skipping migration."
fi

# Start PHP-FPM
exec php-fpm
