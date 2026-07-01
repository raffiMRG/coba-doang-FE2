#!/bin/bash
echo ">> Running Laravel Entrypoint..."

# # Pastikan permission storage dan cache benar
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy root .env into the app mount if needed or when target file is empty
if [ -f /tmp/docker-env ] && { [ ! -f /var/www/.env ] || [ ! -s /var/www/.env ]; }; then
  echo "Copying root .env to /var/www/.env"
  cp /tmp/docker-env /var/www/.env
fi

# Jalankan migrasi jika file .env sudah ada
if [ -f /var/www/.env ]; then
  echo "Running migrations..."
  # docker-php-ext-install pdo pdo_mysql
  # php artisan migrate --force
  php artisan optimize:clear
else
  echo ".env not found, skipping migration."
fi

# Start PHP-FPM
exec php-fpm
