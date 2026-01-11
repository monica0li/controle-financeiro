#!/bin/bash
# scripts/init.sh

echo "Running database migrations..."
php artisan migrate --force

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear