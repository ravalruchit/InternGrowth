# Google OAuth Error Fix - COMPLETE GUIDE

## ✅ Status: Configuration Verified
Your Laravel application is correctly configured! The issue is in Google Cloud Console.

## Problem
Getting "Missing required parameter: client_id" error when trying to sign in with Google.

## ✅ What We've Done
1. ✓ Cleared all Laravel caches
2. ✓ Verified `.env` has correct credentials
3. ✓ Confirmed `config/services.php` is correct
4. ✓ Laravel Socialite is installed
5. ✓ Routes are properly configured

## 🔧 THE FIX - Google Cloud Console Setup

The error "Missing required parameter: client_id" typically means Google isn't recognizing your OAuth client. Here's how to fix it:

### Step 1: Go to Google Cloud Console
1. Visit: https://console.cloud.google.com/
2. Sign in with: ravalruchit999@gmail.com

### Step 2: Select or Create Project
- If you have an existing project, select it
- If not, create a new project:
  - Click "Select a project" at the top
  - Click "NEW PROJECT"
  - Name it "InternGrowth" or similar
  - Click "CREATE"

### Step 3: Enable Google+ API
1. Go to "APIs & Services" > "Library"
2. Search for "Google+ API"
3. Click on it and click "ENABLE"
4. Also enable "Google Identity Toolkit API"

### Step 4: Configure OAuth Consent Screen
1. Go to "APIs & Services" > "OAuth consent screen"
2. Select "External" (for testing)
3. Click "CREATE"
4. Fill in required fields:
   - App name: InternGrowth
   - User support email: ravalruchit999@gmail.com
   - Developer contact: ravalruchit999@gmail.com
5. Click "SAVE AND CONTINUE"
6. Skip "Scopes" (click "SAVE AND CONTINUE")
7. Add test users:
   - Click "ADD USERS"
   - Add: ravalruchit999@gmail.com
   - Click "ADD"
8. Click "SAVE AND CONTINUE"

### Step 5: Create OAuth 2.0 Credentials
1. Go to "APIs & Services" > "Credentials"
2. Click "CREATE CREDENTIALS" > "OAuth client ID"
3. Application type: "Web application"
4. Name: "InternGrowth Web Client"
5. Under "Authorized JavaScript origins", add:
   - `http://localhost:8000`
   - `http://127.0.0.1:8000`
6. Under "Authorized redirect URIs", add:
   - `http://localhost:8000/auth/google/callback`
   - `http://127.0.0.1:8000/auth/google/callback`
7. Click "CREATE"
8. **IMPORTANT**: Copy the Client ID and Client Secret

### Step 6: Update Your .env File
Replace the credentials in your `.env` file with the NEW ones from Step 5:

```env
GOOGLE_CLIENT_ID=your-new-client-id-here
GOOGLE_CLIENT_SECRET=your-new-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Step 7: Clear Cache and Restart
```bash
cd InternGrowth
php artisan config:clear
php artisan cache:clear
php artisan serve
```

### Step 8: Test
1. Open: http://localhost:8000/login
2. Click "Continue with Google"
3. You should see Google's login page
4. Sign in with: ravalruchit999@gmail.com
5. Select your role (Student or Startup)
6. Complete registration

## 🚨 Common Issues & Solutions

### Issue 1: "Access blocked: Authorization Error"
**Solution**: Make sure you added your email as a test user in OAuth consent screen (Step 4.7)

### Issue 2: "Redirect URI mismatch"
**Solution**: The redirect URI in Google Console must EXACTLY match your .env:
- Google Console: `http://localhost:8000/auth/google/callback`
- .env: `http://localhost:8000/auth/google/callback`
- No trailing slashes, exact match!

### Issue 3: Still getting "Missing required parameter: client_id"
**Solution**: 
1. Make sure you're using the NEW credentials from Step 5
2. Clear browser cache and cookies
3. Try in incognito/private mode
4. Verify the OAuth client is not disabled in Google Console

### Issue 4: "This app isn't verified"
**Solution**: This is normal for development. Click "Advanced" > "Go to InternGrowth (unsafe)" to continue.

## 📝 Quick Test Commands

Test if configuration is loaded:
```bash
php test-google-config.php
```

Clear all caches:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 🎯 Current Configuration Status

Your current `.env` has:
```
GOOGLE_CLIENT_ID=721740348068-3rh6rr4l3h2re4mp3cn6s31r3o1bha01.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**If these credentials don't work**, follow Steps 1-6 above to create NEW credentials.

## 🔍 Debugging

If you still have issues, check:

1. **Browser Console**: Press F12 and check for JavaScript errors
2. **Laravel Logs**: Check `storage/logs/laravel.log`
3. **Network Tab**: See what URL is being called when you click the button
4. **Google Console**: Check if there are any error messages in the OAuth client

## ✅ Success Checklist
- [ ] Created/selected Google Cloud project
- [ ] Enabled Google+ API
- [ ] Configured OAuth consent screen
- [ ] Added test user (ravalruchit999@gmail.com)
- [ ] Created OAuth 2.0 credentials
- [ ] Added correct redirect URIs
- [ ] Updated .env with new credentials
- [ ] Cleared Laravel caches
- [ ] Restarted server
- [ ] Tested login

## 📞 Need More Help?

If you're still stuck, provide:
1. The exact error message
2. Screenshot of Google Console OAuth client settings
3. Output of: `php test-google-config.php`
4. Laravel log errors from `storage/logs/laravel.log`
