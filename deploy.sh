#!/usr/bin/env bash
set -e

# Ensure all required Laravel storage directories exist
echo "Creating required storage directories..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/testing
mkdir -p storage/logs
mkdir -p storage/app/private
mkdir -p storage/app/private/id-cards
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# Set correct permissions
chmod -R 775 storage bootstrap/cache

# Cache configuration, routes, and views for production performance
echo "Caching Laravel bootstrap files..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (force runs in production)
echo "Running database migrations..."
php artisan migrate --force

# Start Apache web server using Heroku buildpack binaries pointing to the public directory
echo "Starting Apache web server..."
vendor/bin/heroku-php-apache2 public/
