#!/bin/sh

# Ensure required storage subdirectories exist (Render uses ephemeral filesystem)
echo "Creating required storage directories..."
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/private/id-cards
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Cache Laravel configurations, routes, and views for maximum performance
echo "Optimizing Laravel configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (force run in production environment)
echo "Running database migrations..."
php artisan migrate --force

# Fix permissions for files created by artisan commands during startup
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start Supervisor to run both PHP-FPM and Nginx
echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
