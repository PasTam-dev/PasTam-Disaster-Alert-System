FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev libjpeg-dev libpng-dev libfreetype6-dev libwebp-dev \
    zip unzip git curl nodejs npm && rm -rf /var/lib/apt/lists/*

# Enable extensions and Apache modules
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install pdo pdo_mysql gd && \
    rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_worker.load && \
    a2enmod mpm_prefork rewrite

WORKDIR /var/www/html

# Copy everything
COPY . /var/www/html

# Fix Apache document root
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader --no-scripts 2>&1 || true

# Build frontend
RUN npm install && npm run build || true

# Fix permissions
RUN mkdir -p /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]
EXPOSE 80
