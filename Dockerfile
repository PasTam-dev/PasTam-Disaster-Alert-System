FROM php:8.2-cli

# Install system dependencies (PHP ext deps + Node for Vite build)
RUN apt-get update && apt-get install -y \
    zlib1g-dev libjpeg-dev libpng-dev libfreetype6-dev libwebp-dev \
    zip unzip git curl nodejs npm && rm -rf /var/lib/apt/lists/*

# Enable required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install pdo pdo_mysql gd

WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Install Composer and PHP dependencies (skip artisan scripts during build)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader --no-scripts 2>&1 || true

# Build frontend assets
RUN npm install && npm run build || true

# Fix permissions for Laravel
RUN mkdir -p /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel's built-in server on the port provided by Railway
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
EXPOSE 8000
