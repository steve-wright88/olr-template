FROM php:8.4-cli

# Install system dependencies (SQLite + PostgreSQL support)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    libsqlite3-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy everything first
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend assets
RUN npm ci && npm run build && rm -rf node_modules

# Set permissions
RUN mkdir -p storage/framework/{sessions,views,cache/data} storage/logs storage/app/public \
    && chmod -R 775 storage bootstrap/cache database

# Configure for production
RUN cp .env.example .env \
    && sed -i 's/APP_ENV=local/APP_ENV=production/' .env \
    && sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env \
    && php artisan key:generate \
    && php artisan storage:link \
    && php artisan route:cache \
    && php artisan view:cache

# Start script: run migrations + seed if needed, then serve
RUN printf '#!/bin/bash\nphp artisan migrate --force --seed 2>/dev/null || php artisan migrate --force\nphp artisan serve --host=0.0.0.0 --port=8080\n' > /start.sh \
    && chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
