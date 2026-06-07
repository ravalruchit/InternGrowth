@echo off
echo ========================================
echo InternGrowth - MySQL Setup
echo ========================================
echo.

echo Step 1: Clearing configuration cache...
php artisan config:clear
echo Done!
echo.

echo Step 2: Creating database tables...
echo WARNING: This will delete all existing data!
echo.
set /p confirm="Continue? (y/n): "
if /i "%confirm%"=="y" (
    php artisan migrate:fresh --seed
    echo.
    echo ========================================
    echo SUCCESS! Database setup complete!
    echo ========================================
    echo.
    echo You can now view your data at:
    echo http://localhost/phpmyadmin
    echo Database: interngrowth
    echo.
    echo Default Login Credentials:
    echo Admin: admin@interngrowth.com / password
    echo Student: student@example.com / password
    echo Startup: startup@example.com / password
    echo.
) else (
    echo Setup cancelled.
)

pause
