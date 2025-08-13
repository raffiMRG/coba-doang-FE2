# FROM php:8.3-fpm

# # Install extension
# RUN docker-php-ext-install pdo pdo_mysql

# # Set ownership dan permission untuk Laravel
# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
#   chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# WORKDIR /var/www
# # # Copy the application files
# # COPY . .


# ==============================
FROM php:8.3-fpm


# Install PHP extensions
# RUN docker-php-ext-install pdo pdo_mysql

# # Set ownership dan permission untuk Laravel
# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
#   chmod -R 775 /var/www/storage /var/www/bootstrap/cache


# Salin entrypoint.sh ke container
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Beri permission executable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set workdir dan permission awal (bisa diulang di entrypoint juga)
WORKDIR /var/www

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

