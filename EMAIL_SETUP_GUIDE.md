# Email Setup Guide for Student Verification

## Problem
Gmail requires App Password for SMTP authentication, not your regular password.

## Solution Options

### Option 1: Use Gmail with App Password (Recommended for Production)

1. **Enable 2-Step Verification**
   - Go to: https://myaccount.google.com/security
   - Enable "2-Step Verification"

2. **Generate App Password**
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Windows Computer"
   - Click "Generate"
   - Copy the 16-character password (format: `abcd efgh ijkl mnop`)

3. **Update .env file**
   ```
   MAIL_PASSWORD=your-16-char-app-password-here
   ```
   (Remove spaces from the app password)

4. **Restart Laravel server**
   ```
   php artisan config:clear
   php artisan serve
   ```

### Option 2: Use Log Driver for Testing (Quick Setup)

For development/testing, you can use Laravel's log driver which saves emails to a log file instead of sending them:

1. **Update .env file**
   ```
   MAIL_MAILER=log
   ```

2. **Restart server**
   ```
   php artisan config:clear
   php artisan serve
   ```

3. **View emails**
   - Emails will be saved in: `storage/logs/laravel.log`
   - You can copy the verification link from the log file

### Option 3: Manual Verification (For Testing)

If you just want to test the system without email:

1. Student submits verification request
2. Check database `student_profiles` table for the `verification_token`
3. Manually visit: `http://localhost:8000/verify-email/{token}`
4. Student will be verified instantly

## Current Configuration

Your .env file is set to:
- MAIL_MAILER=smtp
- MAIL_HOST=smtp.gmail.com
- MAIL_USERNAME=ravalruchit999@gmail.com
- MAIL_PASSWORD=paste-your-16-char-app-password-here

**You need to replace `paste-your-16-char-app-password-here` with your actual Gmail App Password.**

## Testing the Email

After setup, test by:
1. Login as student
2. Go to verification page
3. Submit college email
4. Check if email is sent (or check logs if using log driver)
