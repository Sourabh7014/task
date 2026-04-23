#!/bin/sh

# Install composer
echo "Installing composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader
echo "✅ Composer installed"

# Run migrations
echo "Running migrations..."
php artisan migrate:fresh --force
echo "✅ Migrations run"

# Run seeds
echo "Running seeds..."
php artisan db:seed --force
echo "✅ Seeds run"

# give permission to storage and bootstrap/cache
echo "Giving permission to storage and bootstrap/cache..."
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache
echo "✅ Permission given to storage and bootstrap/cache"

# Cache configuration
echo "Configuring cache..."
php artisan config:cache
echo "✅ Cache configured"

# add custom commands