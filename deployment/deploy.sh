#!/bin/bash

# Deployment Script for KPUM Production Server

echo "🚀 Starting Deployment Process..."

# 1. Pull latest code
# git pull origin main 

# 2. Install Dependencies (Optimized)
echo "📦 Installing Dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction
npm ci && npm run build

# 3. Cache Configuration & Routes (Critical for Speed)
echo "⚡ Caching Configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Run Migrations (Force)
echo "🗄️ Migrating Database..."
php artisan migrate --force

# 5. Optimize Images (Optional if needed)
# php artisan image:optimize

# 6. Restart Queue Worker
echo "🔄 Restarting Queue Workers..."
php artisan queue:restart

# 7. Permissions Fix
echo "🔒 Fixing Permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment Completed Successfully!"
