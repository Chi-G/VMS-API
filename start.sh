#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until nc -z -v -w30 $DB_HOST $DB_PORT; do
    echo "Waiting for database connection..."
    sleep 2
done
echo "Database is ready!"

# Run migrations and seeds
echo "Running migrations..."
php artisan migrate --force
echo "Running seeders..."
php artisan db:seed --force

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm
