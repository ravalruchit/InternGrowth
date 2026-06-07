# InternGrowth - Demo Deployment Guide for Judges

This guide will help you deploy InternGrowth online so judges can access it via a web URL.

---

## Quick Deployment Options (Free & Fast)

### Option 1: InfinityFree (Recommended - 100% Free)
**Best for:** Quick demo, no credit card needed
**Time:** 15-20 minutes

#### Steps:
1. **Sign up at InfinityFree**
   - Go to: https://infinityfree.net
   - Create free account
   - Create new hosting account

2. **Prepare Your Project**
   ```bash
   # In your project directory
   cd D:\InternGrowth\InternGrowth
   
   # Create a zip of your project (exclude node_modules, vendor)
   # Manually zip these folders:
   # - app/
   # - bootstrap/
   # - config/
   # - database/
   # - public/
   # - resources/
   # - routes/
   # - storage/
   # - .env (update for production)
   # - artisan
   # - composer.json
   # - composer.lock
   ```

3. **Upload via FTP**
   - Use FileZilla (download from: https://filezilla-project.org/)
   - Connect using credentials from InfinityFree
   - Upload to `htdocs` folder
   - Move contents of `public` folder to `htdocs`
   - Move other folders one level up

4. **Setup Database**
   - Create MySQL database in InfinityFree control panel
   - Note database name, username, password
   - Import your database using phpMyAdmin

5. **Configure .env**
   ```env
   APP_URL=http://yoursite.infinityfreeapp.com
   DB_HOST=sqlXXX.infinityfree.com
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_pass
   ```

6. **Run Setup Commands** (via SSH if available, or use online terminal)
   ```bash
   composer install --no-dev
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --class=DemoDataSeeder
   php artisan storage:link
   ```

---

### Option 2: 000webhost (Alternative Free Option)
**Best for:** Easy setup with cPanel
**Time:** 15-20 minutes

#### Steps:
1. Sign up at: https://www.000webhost.com
2. Create website
3. Upload files via File Manager
4. Create MySQL database
5. Update .env file
6. Run migrations

---

### Option 3: Railway.app (Modern & Fast)
**Best for:** Professional deployment
**Time:** 10 minutes
**Note:** Free tier available, credit card required

#### Steps:
1. **Sign up at Railway**
   - Go to: https://railway.app
   - Sign up with GitHub

2. **Push to GitHub**
   ```bash
   cd D:\InternGrowth\InternGrowth
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin YOUR_GITHUB_REPO_URL
   git push -u origin main
   ```

3. **Deploy on Railway**
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose your InternGrowth repo
   - Add MySQL database service
   - Set environment variables

4. **Environment Variables**
   ```
   APP_KEY=base64:YOUR_KEY
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=mysql
   DB_HOST=${{MYSQL.HOST}}
   DB_PORT=${{MYSQL.PORT}}
   DB_DATABASE=${{MYSQL.DATABASE}}
   DB_USERNAME=${{MYSQL.USER}}
   DB_PASSWORD=${{MYSQL.PASSWORD}}
   ```

5. **Deploy**
   - Railway will auto-deploy
   - Run migrations via Railway CLI or dashboard

---

### Option 4: Vercel + PlanetScale (Serverless)
**Best for:** Fast global deployment
**Time:** 15 minutes

#### Steps:
1. Sign up at Vercel.com
2. Create PlanetScale database (free tier)
3. Connect GitHub repo
4. Configure build settings
5. Deploy

---

## Local Network Demo (Fastest - 2 minutes)

If judges are in the same location, you can demo from your laptop:

### Steps:
1. **Find Your Local IP**
   ```bash
   ipconfig
   # Look for IPv4 Address (e.g., 192.168.1.100)
   ```

2. **Update .env**
   ```env
   APP_URL=http://192.168.1.100:8000
   ```

3. **Start Server**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

4. **Share URL**
   - Give judges: `http://192.168.1.100:8000`
   - They must be on same WiFi network

---

## Using Ngrok (Tunnel Your Local Server)

**Best for:** Quick demo without deployment
**Time:** 5 minutes

### Steps:
1. **Download Ngrok**
   - Go to: https://ngrok.com
   - Sign up and download

2. **Start Your Laravel Server**
   ```bash
   cd D:\InternGrowth\InternGrowth
   php artisan serve
   ```

3. **Start Ngrok**
   ```bash
   ngrok http 8000
   ```

4. **Get Public URL**
   - Ngrok will show: `https://xxxx-xx-xx-xx.ngrok-free.app`
   - Share this URL with judges

5. **Update .env**
   ```env
   APP_URL=https://xxxx-xx-xx-xx.ngrok-free.app
   ```

---

## Pre-Deployment Checklist

### 1. Seed Demo Data
```bash
php artisan db:seed --class=DemoDataSeeder
```

**Demo Accounts Created:**
- Admin: `admin@interngrowth.com` / `password123`
- Student: `rahul@student.com` / `password123`
- Startup: `contact@techvision.com` / `password123`

### 2. Update .env for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Disable Google OAuth if not configured
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

### 3. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 4. Test All Features
- ✅ Student registration and login
- ✅ Startup registration and login
- ✅ Admin login
- ✅ Task creation
- ✅ Application submission
- ✅ AI recommendations
- ✅ Messaging system
- ✅ Certificate generation

---

## Recommended: Ngrok for Quick Demo

For your presentation to judges, I recommend **Ngrok** because:
- ✅ Takes only 5 minutes
- ✅ No deployment needed
- ✅ Works with your local setup
- ✅ Free tier available
- ✅ HTTPS included
- ✅ Can be deleted immediately after

### Quick Ngrok Setup:

1. **Download & Install**
   ```bash
   # Download from: https://ngrok.com/download
   # Extract and run
   ```

2. **Authenticate**
   ```bash
   ngrok config add-authtoken YOUR_TOKEN
   ```

3. **Start Laravel**
   ```bash
   cd D:\InternGrowth\InternGrowth
   php artisan serve
   ```

4. **Start Ngrok** (in new terminal)
   ```bash
   ngrok http 8000
   ```

5. **Share URL**
   - Copy the HTTPS URL from ngrok
   - Share with judges
   - Demo your project!

6. **After Presentation**
   - Press Ctrl+C to stop ngrok
   - URL becomes invalid automatically

---

## Troubleshooting

### 404 Error on Startup Registration
**Fixed!** The issue was missing `is_verified` field. Now resolved.

### Database Connection Error
```bash
# Check .env database credentials
# Test connection:
php artisan migrate:status
```

### Storage Permission Error
```bash
# Fix permissions:
chmod -R 775 storage bootstrap/cache
```

### Route Not Found
```bash
# Clear cache:
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

## After Demo - Cleanup

### If using Ngrok:
- Just stop the ngrok process (Ctrl+C)
- URL becomes invalid automatically

### If using free hosting:
- Delete the hosting account
- Or keep it for portfolio!

### Reset Database:
```bash
php artisan migrate:fresh
php artisan db:seed --class=DemoDataSeeder
```

---

## Support During Presentation

### Have Ready:
1. ✅ Demo accounts list
2. ✅ Feature checklist
3. ✅ Backup local server
4. ✅ Screenshots (if internet fails)
5. ✅ This guide printed

### Demo Flow:
1. Show landing page
2. Register as student
3. Browse AI recommendations
4. Show startup dashboard
5. Demonstrate task workflow
6. Show admin panel
7. Display certificates

---

## Contact & Resources

- **Ngrok:** https://ngrok.com
- **InfinityFree:** https://infinityfree.net
- **Railway:** https://railway.app
- **FileZilla:** https://filezilla-project.org

---

**Good luck with your presentation! 🚀**

Your project is fully functional and ready to impress the judges!
