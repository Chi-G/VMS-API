#!/bin/bash

# Make sure we're in the Laravel project root directory
cd /var/www

# Prepare environment if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Wait for database to be ready
echo "Waiting for database connection..."
while ! php -r "try { \$dbHost = getenv('DB_HOST') ?: 'db'; \$dbName = getenv('DB_DATABASE') ?: 'vms_api_DB'; \$dbUser = getenv('DB_USERNAME') ?: 'vmsDBUser88'; \$dbPass = getenv('DB_PASSWORD') ?: 'secret'; new PDO('mysql:host=' . \$dbHost . ';dbname=' . \$dbName, \$dbUser, \$dbPass); echo 'connected'; } catch (PDOException \$e) { exit(1); }" > /dev/null 2>&1; do
    echo "Waiting for database connection..."
    sleep 5
done

# List directory contents to debug
echo "Contents of current directory:"
ls -la

# Check if artisan file exists
if [ ! -f artisan ]; then
    echo "Error: artisan file not found!"
    exit 1
fi

# Run migrations and seeds
echo "Running migrations..."
php artisan migrate --force
echo "Running seeders..."
php artisan db:seed --force

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm