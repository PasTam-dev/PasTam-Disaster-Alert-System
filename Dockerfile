FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libwebp-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Enable required extensions and Apache modules
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo pdo_mysql gd
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy application files in stages to ensure everything is there
COPY bootstrap/ /var/www/html/bootstrap/
COPY app/ /var/www/html/app/
COPY config/ /var/www/html/config/
COPY database/ /var/www/html/database/
COPY public/ /var/www/html/public/
COPY resources/ /var/www/html/resources/
COPY routes/ /var/www/html/routes/
COPY storage/ /var/www/html/storage/
COPY vendor/ /var/www/html/vendor/ 2>/dev/null || true
COPY artisan /var/www/html/
COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/
COPY package.json /var/www/html/
COPY package-lock.json /var/www/html/
COPY vite.config.js /var/www/html/
COPY tailwind.config.js /var/www/html/

# Fix Apache document root to point to public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies (skip artisan)
RUN composer install --no-dev --optimize-autoloader --no-scripts 2>&1 || true

# Install Node dependencies
RUN apt-get update && apt-get install -y nodejs npm && rm -rf /var/lib/apt/lists/*
RUN npm install && npm run build || true

# Fix permissions
RUN mkdir -p /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Just run Apache - nothing else
CMD ["apache2-foreground"]

EXPOSE 80
