# Google OAuth Troubleshooting Guide

## Understanding the Error

**Error Message**: "Missing required parameter: client_id"

This error appears on Google's page, not your Laravel app. This means:
- ✅ Your Laravel app is working
- ✅ The redirect to Google is happening
- ❌ Google doesn't recognize your OAuth client

## Why This Happens

### Reason 1: OAuth Client Not Created (Most Common)
You need to create an OAuth 2.0 Client ID in Google Cloud Console.

**Solution**: Follow the setup in `GOOGLE_OAUTH_QUICKFIX.md`

### Reason 2: Wrong Client ID Format
The client ID in your .env might be incorrect or incomplete.

**Current Client ID**: `721740348068-3rh6rr4l3h2re4mp3cn6s31r3o1bha01.apps.googleusercontent.com`

**Check**:
- Should end with `.apps.googleusercontent.com`
- Should be a long string with numbers and letters
- No spaces or line breaks

### Reason 3: OAuth Client Disabled
The OAuth client might be disabled in Google Console.

**Solution**:
1. Go to Google Cloud Console > Credentials
2. Find your OAuth client
3. Make sure it's not disabled

### Reason 4: Project Not Selected
You might not have selected the correct project in Google Cloud Console.

**Solution**:
1. Check the project name at the top of Google Cloud Console
2. Make sure it's the project where you created the OAuth client

## Step-by-Step Diagnosis

### Test 1: Check Laravel Configuration
```bash
php test-google-config.php
```

**Expected Output**:
```
✓ All Google OAuth credentials are configured!
```

**If Failed**: Your .env file is not set up correctly.

### Test 2: Check Google Cloud Console
1. Go to: https://console.cloud.google.com/apis/credentials
2. Do you see an OAuth 2.0 Client ID?
   - **YES**: Click on it and verify redirect URIs
   - **NO**: You need to create one (see GOOGLE_OAUTH_QUICKFIX.md)

### Test 3: Check Redirect URI
When you click "Continue with Google", what URL does it try to go to?

**To Check**:
1. Open browser Developer Tools (F12)
2. Go to Network tab
3. Click "Continue with Google"
4. Look at the first request
5. Check the URL parameters

**Should Look Like**:
```
https://accounts.google.com/o/oauth2/auth?
  client_id=721740348068-3rh6rr4l3h2re4mp3cn6s31r3o1bha01.apps.googleusercontent.com
  &redirect_uri=http://localhost:8000/auth/google/callback
  &scope=...
  &response_type=code
```

**If client_id is missing or empty**: Laravel isn't reading your .env file.

### Test 4: Check Browser Console
1. Open browser Developer Tools (F12)
2. Go to Console tab
3. Click "Continue with Google"
4. Look for any JavaScript errors

## The OAuth Flow

Here's what should happen:

```
1. User clicks "Continue with Google"
   ↓
2. Laravel redirects to Google with client_id
   ↓
3. Google shows login page
   ↓
4. User logs in and approves
   ↓
5. Google redirects back to Laravel with code
   ↓
6. Laravel exchanges code for user info
   ↓
7. User is logged in or asked to select role
```

**Your error happens at step 2-3**, meaning Google doesn't recognize the client_id.

## Solutions by Scenario

### Scenario A: First Time Setup
**You've never set up Google OAuth before**

→ Follow `GOOGLE_OAUTH_QUICKFIX.md` completely

### Scenario B: It Worked Before
**Google OAuth worked before but stopped**

Possible causes:
1. OAuth client was deleted
2. Credentials were changed
3. Project was changed
4. .env file was modified

**Solution**:
1. Check if OAuth client still exists in Google Console
2. Verify .env credentials match Google Console
3. Clear Laravel cache: `php artisan config:clear`

### Scenario C: Using Existing Credentials
**You copied credentials from somewhere else**

The credentials might be:
1. From a different project
2. From a different domain
3. Expired or revoked

**Solution**: Create fresh credentials for your project

### Scenario D: Multiple Projects
**You have multiple Google Cloud projects**

You might be:
1. Looking at the wrong project
2. Using credentials from project A in project B

**Solution**:
1. Note which project has the OAuth client
2. Use credentials from that specific project
3. Or create new credentials in your current project

## Quick Fixes to Try

### Fix 1: Create New Credentials
The fastest solution is to create brand new OAuth credentials:

1. Go to: https://console.cloud.google.com/apis/credentials
2. Click "CREATE CREDENTIALS" > "OAuth client ID"
3. Follow the wizard
4. Update your .env with the NEW credentials
5. Run: `php artisan config:clear`

### Fix 2: Check OAuth Consent Screen
1. Go to: https://console.cloud.google.com/apis/credentials/consent
2. Make sure it's configured
3. Add your email as a test user
4. Save changes

### Fix 3: Enable Required APIs
1. Go to: https://console.cloud.google.com/apis/library
2. Search and enable:
   - Google+ API
   - Google Identity Toolkit API

### Fix 4: Use Different Browser
Sometimes browser cache causes issues:
1. Try in incognito/private mode
2. Or try a different browser
3. Or clear all cookies for localhost

## Verification Checklist

Before testing, verify:

- [ ] Google Cloud project is selected
- [ ] OAuth consent screen is configured
- [ ] OAuth 2.0 Client ID exists
- [ ] Redirect URIs include: `http://localhost:8000/auth/google/callback`
- [ ] .env has correct GOOGLE_CLIENT_ID
- [ ] .env has correct GOOGLE_CLIENT_SECRET
- [ ] .env has correct GOOGLE_REDIRECT_URI
- [ ] Ran `php artisan config:clear`
- [ ] Laravel server is running on port 8000
- [ ] Using the correct URL: `http://localhost:8000/login`

## Still Stuck?

If none of this works, the issue might be:

1. **Google Account Issue**: Try with a different Google account
2. **Network Issue**: Check if you can access Google normally
3. **Firewall**: Check if firewall is blocking the connection
4. **Laravel Issue**: Check `storage/logs/laravel.log` for errors

## Get More Help

Provide these details:
1. Screenshot of the error page
2. Output of: `php test-google-config.php`
3. Screenshot of Google Console OAuth client settings
4. Browser console errors (F12 > Console)
5. Network tab showing the redirect URL (F12 > Network)

## Success Indicators

When everything is working:
- ✅ Clicking "Continue with Google" redirects to Google
- ✅ You see Google's login page (not an error)
- ✅ After login, you're redirected back to InternGrowth
- ✅ You can complete registration/login

## Next Steps

1. Read `GOOGLE_OAUTH_QUICKFIX.md` for fast setup
2. Read `GOOGLE_OAUTH_FIX.md` for detailed guide
3. Run `php test-google-config.php` to verify config
4. Test the login flow
5. Check Laravel logs if issues persist
