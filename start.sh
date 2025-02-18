#!/bin/bash

set -e

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate APP_KEY if not set
if ! grep -q '^APP_KEY=.*' .env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Wait for MySQL to be ready
export MYSQL_PWD="${DB_PASSWORD}"
echo "Waiting for MySQL at ${DB_HOST}..."
while ! mysqladmin ping -h"${DB_HOST}" -u"${DB_USERNAME}" --silent; do
    sleep 1
done

# Run migrations and seeding
echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

# Start Apache in the foreground
exec apache2-foreground
