# Stage 1: Build
FROM php:8.2-fpm as builder

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

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
WORKDIR /var/www
COPY . .

# Install dependencies
RUN composer install --no-interaction --no-scripts

# Stage 2: Runtime
FROM php:8.2-fpm

# Copy application files from the builder stage
COPY --from=builder /var/www /var/www

# Set working directory
WORKDIR /var/www

# Copy startup script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Start with the startup script
CMD ["sh", "/usr/local/bin/start.sh"]