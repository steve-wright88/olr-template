FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libsqlite3-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd \
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

# Create SQLite database and set permissions
RUN mkdir -p database storage/framework/{sessions,views,cache/data} storage/logs storage/app/public \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

# Generate key, run migrations, and seed
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan migrate --force --seed \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
