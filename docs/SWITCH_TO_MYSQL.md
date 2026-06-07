# Switch InternGrowth to MySQL (XAMPP/phpMyAdmin)

## Step 1: Start XAMPP

1. Open XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**
4. Click **Admin** button next to MySQL (opens phpMyAdmin)

## Step 2: Create Database in phpMyAdmin

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "New" in the left sidebar
3. Database name: `interngrowth`
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"

## Step 3: Update Laravel Configuration

Open the `.env` file in your InternGrowth folder and change these lines:

**FROM (SQLite):**
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

**TO (MySQL):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=interngrowth
DB_USERNAME=root
DB_PASSWORD=
```

## Step 4: Clear Configuration Cache

```bash
cd InternGrowth
php artisan config:clear
php artisan cache:clear
```

## Step 5: Run Migrations

This will create all tables in MySQL:

```bash
php artisan migrate:fresh --seed
```

## Step 6: Verify in phpMyAdmin

1. Go to phpMyAdmin: http://localhost/phpmyadmin
2. Click on `interngrowth` database in left sidebar
3. You should see all 19 tables!

## ✅ Done!

Now all your data will be stored in MySQL and you can:
- View data in phpMyAdmin
- Run SQL queries
- Export/Import easily
- Manage visually

## 📊 View Your Data in phpMyAdmin

1. Open: http://localhost/phpmyadmin
2. Click: `interngrowth` database
3. Click any table to view data:
   - `users` - See all users
   - `tasks` - See all tasks
   - `messages` - See all messages
   - `applications` - See all applications
   - etc.

## 🔧 Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"
**Solution:** Your MySQL has a password. Update `.env`:
```env
DB_PASSWORD=your_mysql_password
```

### Error: "Database 'interngrowth' doesn't exist"
**Solution:** Create the database in phpMyAdmin first (Step 2)

### Error: "SQLSTATE[HY000] [2002] No connection"
**Solution:** Make sure MySQL is running in XAMPP

## 🎉 Benefits of MySQL

- ✅ Visual interface (phpMyAdmin)
- ✅ Easy to browse data
- ✅ Run SQL queries directly
- ✅ Export/Import with one click
- ✅ Better for production
- ✅ More powerful than SQLite
