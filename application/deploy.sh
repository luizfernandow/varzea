#!/usr/bin/env bash

echo ""
echo "Setting up folder permissions..."
echo "chown -R www-data:www-data storage"
chown -R www-data:www-data storage
echo "chown -R www-data:www-data bootstrap/cache"
chown -R www-data:www-data bootstrap/cache

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear

# Clear expired password reset tokens
php artisan auth:clear-resets

# Clear and cache routes
php artisan route:cache

# Clear and cache config
php artisan config:cache

echo ""
echo "Setup completed!"
echo ""