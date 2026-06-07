# 🔑 Razorpay Setup Guide

## Quick Setup (5 minutes)

### Step 1: Create Razorpay Account
1. Go to https://razorpay.com/
2. Click "Sign Up" (top right)
3. Enter your email and create password
4. Verify email

### Step 2: Get Test API Keys
1. Login to Razorpay Dashboard
2. Go to **Settings** (left sidebar)
3. Click **API Keys**
4. Click **Generate Test Key**
5. You'll see:
   - **Key ID**: `rzp_test_xxxxxxxxxx`
   - **Key Secret**: `xxxxxxxxxxxxxxxxxx`

### Step 3: Add Keys to `.env`
Open `InternGrowth/.env` and update:
```env
RAZORPAY_KEY=rzp_test_xxxxxxxxxx
RAZORPAY_SECRET=xxxxxxxxxxxxxxxxxx
PLATFORM_FEE_PERCENTAGE=10
```

### Step 4: Test Payment
Use these test card details:
- **Card Number**: `4111 1111 1111 1111`
- **CVV**: Any 3 digits (e.g., `123`)
- **Expiry**: Any future date (e.g., `12/25`)
- **Name**: Any name

---

## Test Mode vs Live Mode

### Test Mode (Current)
- Keys start with `rzp_test_`
- No real money charged
- Use test cards
- Perfect for development

### Live Mode (Production)
- Keys start with `rzp_live_`
- Real money charged
- Real cards only
- Need KYC verification

---

## Going Live (When Ready)

### 1. Complete KYC
- Submit business documents
- Bank account details
- PAN/GST details

### 2. Get Live Keys
- Go to Settings → API Keys
- Switch to "Live Mode"
- Generate Live Keys

### 3. Update `.env`
```env
RAZORPAY_KEY=rzp_live_xxxxxxxxxx
RAZORPAY_SECRET=xxxxxxxxxxxxxxxxxx
```

---

## Razorpay Dashboard Features

### View Payments
- See all transactions
- Payment status
- Customer details

### Refunds
- Process refunds manually
- View refund history

### Settlements
- Money auto-transferred to bank
- Usually T+2 days (2 days after payment)

### Reports
- Download payment reports
- Export to Excel/CSV

---

## Pricing

### Test Mode
- **FREE** - Unlimited test transactions

### Live Mode
- **2% + ₹2** per transaction
- No setup fee
- No annual fee
- Instant activation

---

## Support

- **Email**: support@razorpay.com
- **Phone**: +91-80-6890-6890
- **Docs**: https://razorpay.com/docs/

---

## Common Issues

### Payment Not Working?
1. Check if keys are correct in `.env`
2. Clear Laravel cache: `php artisan config:clear`
3. Check browser console for errors

### Payment Success but Not Updating?
1. Check `/wallet/verify` route is working
2. Check database transactions table
3. Check Laravel logs: `storage/logs/laravel.log`

### Test Card Not Working?
- Make sure using test keys (not live)
- Try different test card: `5104 0600 0000 0008`

---

## Security Tips

1. **Never commit `.env` to Git**
2. **Keep Secret key private**
3. **Use HTTPS in production**
4. **Verify payment signatures** (already implemented)
5. **Log all transactions** (already implemented)

---

## Ready to Test! 🎉

Your Razorpay integration is complete and ready to use!
