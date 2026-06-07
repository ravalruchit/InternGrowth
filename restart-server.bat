@echo off
echo Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo Starting Laravel server...
echo Press Ctrl+C to stop the server
echo.
php artisan serve
