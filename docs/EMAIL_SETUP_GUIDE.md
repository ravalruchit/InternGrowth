# Email Setup Guide - Password Reset & Notifications

## 🔍 Current Issue

Your `.env` file has `MAIL_MAILER=log`, which means emails are being logged to files instead of actually being sent to your inbox.

## 📧 Solution Options

### Option 1: Check Log Files (Quick - Emails Already There!)

Your password reset emails ARE being sent, but to log files!

**To view them:**

1. Open: `storage/logs/laravel.log`
2. Search for: "password" or "reset"
3. You'll find the reset link in the log

**Example log entry:**
```
[2026-02-12 10:30:00] local.DEBUG: Reset Password Notification
To: user@example.com
Subject: Reset Password Notification
Link: http://localhost:8000/reset-password/token-here
```

### Option 2: Use Mailtrap (Recommended for Development)

Mailtrap is a fake SMTP server perfect for testing emails.

**Setup Steps:**

1. **Sign up for Mailtrap**
   - Go to: https://mailtrap.io/
   - Create free account
   - Go to "Email Testing" > "Inboxes"
   - Click on your inbox

2. **Get SMTP Credentials**
   - You'll see: Host, Port, Username, Password

3. **Update .env file:**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@interngrowth.com"
MAIL_FROM_NAME="${APP_NAME}"
```

4. **Clear cache:**
```bash
php artisan config:clear
```

5. **Test it:**
   - Try password reset again
   - Check Mailtrap inbox
   - You'll see the email there!

### Option 3: Use Gmail (For Production/Real Emails)

**Setup Steps:**

1. **Enable 2-Step Verification on Gmail**
   - Go to: https://myaccount.google.com/security
   - Enable 2-Step Verification

2. **Create App Password**
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Windows Computer"
   - Click "Generate"
   - Copy the 16-character password

3. **Update .env file:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

4. **Clear cache:**
```bash
php artisan config:clear
```

### Option 4: Keep Using Log (Development Only)

If you want to keep using log files:

**How to find password reset links:**

1. **Open log file:**
```bash
notepad storage/logs/laravel.log
```

2. **Search for:** "Reset Password" or your email address

3. **Find the reset link** in the log entry

4. **Copy and paste** the link into your browser

## 🚀 Quick Setup (Mailtrap - Recommended)

```bash
# 1. Sign up at mailtrap.io (free)
# 2. Get your credentials
# 3. Update .env:

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste-your-username
MAIL_PASSWORD=paste-your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@interngrowth.com"
MAIL_FROM_NAME="InternGrowth"

# 4. Clear cache
php artisan config:clear

# 5. Test password reset - check Mailtrap inbox!
```

## 🧪 Test Email Sending

Create a test command to verify email works:

```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from InternGrowth', function($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

**If using log:** Check `storage/logs/laravel.log`
**If using Mailtrap:** Check Mailtrap inbox
**If using Gmail:** Check your Gmail inbox

## 📋 Comparison

| Method | Pros | Cons | Best For |
|--------|------|------|----------|
| **Log** | No setup, instant | Can't see formatted emails | Quick development |
| **Mailtrap** | See real emails, free, safe | Extra signup | Development & testing |
| **Gmail** | Real emails, production-ready | Requires app password | Production use |

## 🔧 Troubleshooting

### "Connection refused"
- Check MAIL_HOST and MAIL_PORT
- Make sure credentials are correct
- Clear config cache: `php artisan config:clear`

### "Authentication failed"
- For Gmail: Use App Password, not regular password
- For Mailtrap: Copy credentials exactly
- Check for extra spaces in .env

### "Email not received"
- Check spam folder
- Verify MAIL_FROM_ADDRESS is valid
- Check Laravel logs: `storage/logs/laravel.log`

### "Queue not processing"
If emails are queued:
```bash
php artisan queue:work
```

## 📝 Current Configuration

Your current `.env` has:
```
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
```

This means:
- ✅ Password reset emails ARE being sent
- ✅ They're being logged to `storage/logs/laravel.log`
- ❌ They're NOT being sent to actual email addresses
- ❌ You need to check log files to get reset links

## 🎯 Recommended Action

**For Development:**
1. Use Mailtrap (5 minutes setup)
2. See emails in nice inbox
3. Test all email features

**For Production:**
1. Use Gmail or professional email service
2. Set up proper MAIL_FROM_ADDRESS
3. Test thoroughly before launch

## 📧 Email Features in InternGrowth

Your app sends emails for:
- ✉️ Password reset
- ✉️ Email verification (if enabled)
- ✉️ Welcome emails (if configured)
- ✉️ Notifications (if configured)

All of these are currently going to log files!

## 🚀 Quick Fix (Right Now)

**To get your password reset link immediately:**

1. Open: `storage/logs/laravel.log`
2. Scroll to bottom (most recent)
3. Look for: "Reset Password Notification"
4. Find the URL that looks like:
   ```
   http://localhost:8000/reset-password/abc123token
   ```
5. Copy and paste into browser
6. Reset your password!

## 💡 Pro Tip

For development, I recommend Mailtrap because:
- ✅ Free forever
- ✅ See formatted HTML emails
- ✅ Test spam score
- ✅ No risk of sending test emails to real users
- ✅ Easy to share with team
- ✅ API for automated testing

## 📞 Need Help?

If you want me to:
1. Set up Mailtrap for you
2. Configure Gmail
3. Create a test email command
4. Find your reset link in logs

Just let me know!
