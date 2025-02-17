#!/bin/bash

# Wait for database to be ready
echo "Waiting for database connection..."
while ! mysqladmin ping -h"db" --silent; do
    sleep 1
done

# Run migrations and seeds
cd /var/www
php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM
php-fpm