#!/bin/bash
# start-render.sh

# Run migrations
php artisan migrate --force

# Set permissions
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Start PHP server
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}