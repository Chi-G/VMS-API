FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy startup script
COPY start.sh ./start.sh
RUN chmod +x ./start.sh

# Set permissions
RUN chown -R www-data:www-data /var/www

# Install dependencies and setup
RUN composer install --no-interaction --no-scripts

# Prepare environment file (if needed during build)
# RUN cp .env.example .env
# RUN php artisan key:generate

# Expose port 9000 for PHP-FPM
# EXPOSE 9000

# Start with the startup script
CMD ["sh", "./start.sh"]