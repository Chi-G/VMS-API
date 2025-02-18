#!/bin/bash

# Prepare environment if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Wait for database to be ready
echo "Waiting for database connection..."
while ! php -r "try { new PDO('mysql:host=db;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'connected'; } catch (PDOException \$e) { echo \$e->getMessage(); exit(1); }" > /dev/null 2>&1; do
    echo "Waiting for database connection..."
    sleep 5
done

# Run migrations and seeds
echo "Running migrations..."
php artisan migrate --force
echo "Running seeders..."
php artisan db:seed --force

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm