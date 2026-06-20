#!/usr/bin/env bash

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
