#!/bin/bash

# Run migrations and seeds
echo "Running migrations..."
php artisan migrate --force
echo "Running seeders..."
php artisan db:seed --force

# Start the application
exec "$@"