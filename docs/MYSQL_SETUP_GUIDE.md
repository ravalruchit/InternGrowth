# 🚀 Quick MySQL Setup Guide for InternGrowth

## ✅ Prerequisites

1. **XAMPP installed** (Download from: https://www.apachefriends.org/)
2. **InternGrowth application** (already done!)

## 📋 Step-by-Step Setup

### Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Both should show green "Running" status

![XAMPP Running](https://i.imgur.com/example.png)

---

### Step 2: Create Database in phpMyAdmin

1. Click **Admin** button next to MySQL in XAMPP
   - OR open browser: http://localhost/phpmyadmin

2. Click **"New"** in the left sidebar

3. Enter database name: `interngrowth`

4. Select Collation: `utf8mb4_unicode_ci`

5. Click **"Create"** button

✅ You should see "interngrowth" database in the left sidebar!

---

### Step 3: Configure Laravel (Already Done!)

The `.env` file has been updated to use MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=interngrowth
DB_USERNAME=root
DB_PASSWORD=
```

✅ Configuration is ready!

---

### Step 4: Run Setup Script

**Option A: Using Batch File (Easy)**

1. Double-click: `setup-mysql.bat`
2. Press `y` when asked to confirm
3. Wait for completion

**Option B: Using Commands (Manual)**

Open Command Prompt in InternGrowth folder:

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Create tables and seed data
php artisan migrate:fresh --seed
```

---

### Step 5: Verify in phpMyAdmin

1. Go to: http://localhost/phpmyadmin
2. Click **"interngrowth"** database in left sidebar
3. You should see **19 tables**:

```
✅ applications
✅ cache
✅ cache_locks
✅ certificates
✅ conversations
✅ failed_jobs
✅ job_batches
✅ jobs
✅ messages
✅ migrations
✅ notifications
✅ points_transactions
✅ points_wallets
✅ ratings
✅ sessions
✅ skill_task
✅ skills
✅ startup_profiles
✅ student_profiles
✅ student_skill
✅ tasks
✅ users
```

---

### Step 6: View Your Data

Click on any table to see the data:

**Example: View Users**
1. Click **"users"** table
2. Click **"Browse"** tab
3. See all users (admin, student, startup)

**Example: View Tasks**
1. Click **"tasks"** table
2. Click **"Browse"** tab
3. See all posted tasks

**Example: View Messages**
1. Click **"messages"** table
2. Click **"Browse"** tab
3. See all chat messages

---

## 🎉 Success!

Your InternGrowth application is now using MySQL!

### What You Can Do Now:

✅ **Browse Data Visually**
- Open phpMyAdmin
- Click tables to view data
- No command line needed!

✅ **Run SQL Queries**
- Click "SQL" tab in phpMyAdmin
- Write custom queries
- Example: `SELECT * FROM users WHERE role = 'student'`

✅ **Export Data**
- Click "Export" tab
- Choose format (SQL, CSV, Excel)
- Download backup

✅ **Import Data**
- Click "Import" tab
- Upload SQL file
- Restore backup

✅ **Edit Data Directly**
- Click "Edit" icon next to any row
- Modify values
- Save changes

---

## 🔧 Troubleshooting

### Problem: "Access denied for user 'root'"

**Solution:** Your MySQL has a password. Update `.env`:
```env
DB_PASSWORD=your_password_here
```

Then run:
```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

---

### Problem: "Database 'interngrowth' doesn't exist"

**Solution:** Create the database first:
1. Open phpMyAdmin
2. Click "New"
3. Name: `interngrowth`
4. Click "Create"

Then run setup again.

---

### Problem: "SQLSTATE[HY000] [2002] No connection"

**Solution:** MySQL is not running:
1. Open XAMPP Control Panel
2. Click "Start" next to MySQL
3. Wait for green "Running" status
4. Try again

---

### Problem: Tables already exist

**Solution:** Drop and recreate:
```bash
php artisan migrate:fresh --seed
```

This will delete all data and start fresh!

---

## 📊 Default Login Credentials

After seeding, you can login with:

**Admin:**
- Email: `admin@interngrowth.com`
- Password: `password`

**Student:**
- Email: `student@example.com`
- Password: `password`

**Startup:**
- Email: `startup@example.com`
- Password: `password`

---

## 🎯 Quick Access Links

- **phpMyAdmin:** http://localhost/phpmyadmin
- **InternGrowth App:** http://localhost:8000
- **Database:** interngrowth

---

## 💡 Tips

1. **Always start XAMPP** before running the app
2. **Backup regularly** using phpMyAdmin Export
3. **Use SQL tab** for custom queries
4. **Browse tables** to see real-time data
5. **Edit carefully** - changes are immediate!

---

## 🆘 Need Help?

If you encounter any issues:

1. Check XAMPP is running (Apache + MySQL green)
2. Verify database exists in phpMyAdmin
3. Check `.env` file has correct settings
4. Run `php artisan config:clear`
5. Try setup again

---

## ✅ Verification Checklist

- [ ] XAMPP installed
- [ ] Apache running (green in XAMPP)
- [ ] MySQL running (green in XAMPP)
- [ ] Database "interngrowth" created in phpMyAdmin
- [ ] `.env` file updated with MySQL settings
- [ ] Ran `php artisan migrate:fresh --seed`
- [ ] Can see 19 tables in phpMyAdmin
- [ ] Can login to application
- [ ] Can view data in phpMyAdmin

---

## 🎊 You're All Set!

Your InternGrowth application is now using MySQL with phpMyAdmin!

Enjoy managing your data visually! 🚀
