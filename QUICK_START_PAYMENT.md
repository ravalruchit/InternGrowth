# 🚀 Quick Start - Payment System

## Ready to Test in 2 Minutes!

---

## Step 1: Start Server (if not running)
```bash
cd InternGrowth
php artisan serve
```

---

## Step 2: Login as Admin
```
URL: http://localhost:8000/login
Email: Your admin email
Password: Your admin password
```

---

## Step 3: Add Money to Startup
1. Click **"Manage Wallets"** on admin dashboard
2. Find a startup in the list
3. Click **"Add Money"**
4. Enter amount: `1000`
5. Click **"Add Money"**
6. ✅ Done! Startup now has ₹1000

---

## Step 4: Create Task as Startup
1. Logout and login as that startup
2. Go to dashboard
3. See wallet balance: ₹1000
4. Click **"Create Task"**
5. Fill details:
   - Title: "Build a Website"
   - Stipend: `500`
   - Reward Points: `100`
6. Submit
7. ✅ Task created! Wallet now shows ₹500 (₹500 locked in escrow)

---

## Step 5: Student Applies & Submits
1. Login as student
2. Browse tasks
3. Apply for the task
4. Wait for approval (login as startup and approve)
5. Submit work with files
6. ✅ Submission sent!

---

## Step 6: Startup Accepts
1. Login as startup
2. Go to dashboard
3. Click "Review" on submission
4. Click **"Accept Submission"**
5. ✅ Magic happens:
   - Student gets ₹450 (₹50 platform fee)
   - Student gets 100 points
   - Student gets certificate
   - Escrow released automatically

---

## Step 7: Check Wallets
1. Login as student
2. Go to `/wallet` or click wallet balance
3. See ₹450 in wallet
4. See transaction history
5. ✅ Complete!

---

## That's It! 🎉

The entire payment system with escrow is working!

### What Happens Automatically:
- ✅ Money locked when task created
- ✅ Money released when accepted
- ✅ Money refunded when rejected
- ✅ Platform fee deducted (10%)
- ✅ Transactions recorded
- ✅ Points awarded
- ✅ Certificates issued

### Admin Can:
- Add money to any wallet
- Deduct money from any wallet
- View all transactions
- Full control

---

## URLs

- **Admin Wallets**: http://localhost:8000/admin/wallets
- **User Wallet**: http://localhost:8000/wallet
- **Admin Dashboard**: http://localhost:8000/admin/dashboard

---

## Test Scenarios

### Scenario 1: Happy Path (Accept)
```
Admin adds ₹1000 → Startup creates task ₹500 → Student submits → Startup accepts
Result: Student gets ₹450, Startup has ₹500 left
```

### Scenario 2: Rejection (Refund)
```
Admin adds ₹1000 → Startup creates task ₹500 → Student submits → Startup rejects
Result: Startup gets ₹500 back (total ₹1000 again)
```

### Scenario 3: Multiple Tasks
```
Admin adds ₹1000 → Startup creates 2 tasks (₹300 each)
Result: ₹600 locked, ₹400 remaining
```

---

## Troubleshooting

### Wallet not showing?
- Clear cache: `php artisan config:clear`
- Refresh page

### Can't create task?
- Check startup has enough balance
- Check startup is verified

### Money not released?
- Check submission was accepted (not just reviewed)
- Check escrow exists in database

---

## Need Help?

Check these files:
- `MANUAL_WALLET_SYSTEM.md` - Detailed explanation
- `PAYMENT_SYSTEM_FINAL.md` - Complete overview
- `PAYMENT_SYSTEM_SETUP_COMPLETE.md` - Technical details

---

## Status: READY! ✅

No payment gateway, no signup, no hassle. Just add money and test!
