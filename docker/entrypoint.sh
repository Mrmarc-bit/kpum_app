#!/bin/bash

# Exit on error
set -e

# Run standard deployment steps
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking storage..."
php artisan storage:link || true

echo "Starting PHP-FPM..."
php-fpm
