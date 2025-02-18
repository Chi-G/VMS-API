#!/bin/bash

# Prepare environment if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Wait for database to be ready
echo "Waiting for database connection..."
until nc -z -v -w30 db 3306; do
  echo "Waiting for database connection..."
  # wait for 5 seconds before check again
  sleep 5
done

# Run migrations and seeds
echo "Running migrations..."
ls -a
php artisan migrate --force
echo "Running seeders..."
php artisan db:seed --force

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm