#!/bin/sh

# Cache Laravel configurations, routes, and views for maximum performance
echo "Optimizing Laravel configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (force run in production environment)
echo "Running database migrations..."
php artisan migrate --force

# Start Supervisor to run both PHP-FPM and Nginx
echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
