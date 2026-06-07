# Google OAuth - Quick Fix Guide

## 🚀 Fast Solution (5 Minutes)

### The Problem
Error: "Missing required parameter: client_id"

### The Cause
Your Google Cloud Console OAuth client needs to be properly configured.

### The Fix

**1. Go to Google Cloud Console**
https://console.cloud.google.com/apis/credentials

**2. Create New OAuth Client**
- Click "CREATE CREDENTIALS" > "OAuth client ID"
- Type: Web application
- Name: InternGrowth

**3. Add These Redirect URIs**
```
http://localhost:8000/auth/google/callback
http://127.0.0.1:8000/auth/google/callback
```

**4. Copy the Credentials**
After creating, copy the Client ID and Client Secret

**5. Update .env File**
```env
GOOGLE_CLIENT_ID=paste-your-new-client-id-here
GOOGLE_CLIENT_SECRET=paste-your-new-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**6. Clear Cache & Restart**
```bash
cd InternGrowth
php artisan config:clear
php artisan serve
```

**7. Test**
- Go to: http://localhost:8000/login
- Click "Continue with Google"
- Should work now! ✅

---

## ⚠️ Important Notes

1. **OAuth Consent Screen**: If you haven't set it up, do it first:
   - Go to "OAuth consent screen"
   - Select "External"
   - Add your email as test user

2. **Test Users**: Add ravalruchit999@gmail.com as a test user

3. **Exact Match**: Redirect URI must match EXACTLY (no trailing slash)

4. **Browser Cache**: Clear browser cache or use incognito mode

---

## 🔍 Still Not Working?

Run this test:
```bash
php test-google-config.php
```

Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

Read full guide:
```
GOOGLE_OAUTH_FIX.md
```

---

## ✅ Success Indicators

When it works, you'll see:
1. Redirected to Google login page
2. Can select your Google account
3. Redirected back to InternGrowth
4. Asked to select role (Student/Startup)
5. Logged in successfully

---

## 🆘 Common Errors

**"Access blocked: Authorization Error"**
→ Add your email as test user in OAuth consent screen

**"Redirect URI mismatch"**
→ Check redirect URI matches exactly in both places

**"This app isn't verified"**
→ Normal for development, click "Advanced" > "Continue"

---

Need detailed help? See `GOOGLE_OAUTH_FIX.md`
