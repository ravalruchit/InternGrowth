# InternGrowth - Deployment Guide

## Pre-Deployment Checklist

### 1. Environment Configuration
```bash
# Copy and configure environment file
cp .env.example .env

# Update these values in .env:
APP_NAME=InternGrowth
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database configuration
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=interngrowth
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Mail configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@interngrowth.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies (if using Vite)
npm install
npm run build
```

### 3. Application Setup
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Link storage
php artisan storage:link
```

### 4. File Permissions
```bash
# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (adjust user/group as needed)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## Server Requirements

### Minimum Requirements
- PHP 8.2 or higher
- MySQL 5.7+ / PostgreSQL 9.6+ / SQLite 3.35+
- Composer
- Web server (Apache/Nginx)

### PHP Extensions Required
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

## Web Server Configuration

### Apache (.htaccess included)
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/InternGrowth/public

    <Directory /path/to/InternGrowth/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/interngrowth-error.log
    CustomLog ${APACHE_LOG_DIR}/interngrowth-access.log combined
</VirtualHost>
```

### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/InternGrowth/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## SSL Configuration (Recommended)

### Using Let's Encrypt (Certbot)
```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-apache

# Or for Nginx
sudo apt-get install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --apache -d your-domain.com
# Or for Nginx
sudo certbot --nginx -d your-domain.com

# Auto-renewal is configured automatically
```

## Database Setup

### MySQL
```sql
CREATE DATABASE interngrowth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'interngrowth_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON interngrowth.* TO 'interngrowth_user'@'localhost';
FLUSH PRIVILEGES;
```

### PostgreSQL
```sql
CREATE DATABASE interngrowth;
CREATE USER interngrowth_user WITH PASSWORD 'secure_password';
GRANT ALL PRIVILEGES ON DATABASE interngrowth TO interngrowth_user;
```

## Queue Configuration (Optional)

If implementing background jobs:

```bash
# Install supervisor
sudo apt-get install supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/interngrowth-worker.conf
```

```ini
[program:interngrowth-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/InternGrowth/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/InternGrowth/storage/logs/worker.log
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start interngrowth-worker:*
```

## Scheduled Tasks (Cron)

Add to crontab:
```bash
crontab -e
```

Add this line:
```
* * * * * cd /path/to/InternGrowth && php artisan schedule:run >> /dev/null 2>&1
```

## Security Hardening

### 1. Environment File
```bash
# Secure .env file
chmod 600 .env
```

### 2. Disable Directory Listing
Already configured in public/.htaccess

### 3. Hide Laravel Version
Remove or customize error pages

### 4. Rate Limiting
Already configured in Laravel

### 5. HTTPS Only
Update .env:
```
APP_URL=https://your-domain.com
SESSION_SECURE_COOKIE=true
```

## Monitoring & Logging

### Log Files Location
```
storage/logs/laravel.log
```

### Log Rotation
```bash
# Install logrotate
sudo apt-get install logrotate

# Create config
sudo nano /etc/logrotate.d/interngrowth
```

```
/path/to/InternGrowth/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

## Backup Strategy

### Database Backup
```bash
# MySQL
mysqldump -u username -p interngrowth > backup_$(date +%Y%m%d).sql

# PostgreSQL
pg_dump interngrowth > backup_$(date +%Y%m%d).sql
```

### Application Backup
```bash
# Backup entire application
tar -czf interngrowth_backup_$(date +%Y%m%d).tar.gz /path/to/InternGrowth

# Exclude vendor and node_modules
tar -czf interngrowth_backup_$(date +%Y%m%d).tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    /path/to/InternGrowth
```

## Performance Optimization

### 1. OPcache Configuration
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 2. Laravel Optimizations
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear all caches if needed
php artisan optimize:clear
```

### 3. Database Indexing
Already configured in migrations

## Troubleshooting

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Issues
- Check .env database credentials
- Verify database server is running
- Check firewall rules

### 500 Error
- Check storage/logs/laravel.log
- Verify file permissions
- Check .env configuration

## Post-Deployment

### 1. Create Admin Account
If not using seeder:
```bash
php artisan tinker
>>> $user = App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin', 'is_verified' => true]);
```

### 2. Test All Features
- [ ] User registration (student/startup)
- [ ] Login/logout
- [ ] Student profile creation
- [ ] Task posting
- [ ] Application workflow
- [ ] Submission workflow
- [ ] Points system
- [ ] Certificate generation
- [ ] Leaderboard

### 3. Monitor Logs
```bash
tail -f storage/logs/laravel.log
```

## Maintenance Mode

### Enable Maintenance Mode
```bash
php artisan down --secret="maintenance-token"
```

Access site with: `https://your-domain.com/maintenance-token`

### Disable Maintenance Mode
```bash
php artisan up
```

## Scaling Considerations

### Database
- Use read replicas for heavy read operations
- Implement database connection pooling
- Consider Redis for caching

### Application
- Use load balancer for multiple app servers
- Implement Redis for session storage
- Use CDN for static assets

### Queue Workers
- Scale queue workers based on load
- Use Redis for queue driver
- Monitor queue metrics

## Support & Updates

### Updating Application
```bash
# Backup first!
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Security Updates
```bash
composer update --with-dependencies
```

## Conclusion

Your InternGrowth application is now deployed and ready for production use. Monitor logs regularly and keep the application updated for security and performance.

For issues, check:
1. Application logs: `storage/logs/laravel.log`
2. Web server logs
3. Database logs
4. PHP error logs
