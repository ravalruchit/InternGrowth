# 💰 Manual Wallet System - Simple & Easy!

## No Payment Gateway Needed! 🎉

Instead of dealing with Razorpay signup and verification, we've implemented a **manual wallet system** where you (as admin) control all money.

---

## How It Works

### For Admin (You):
1. Go to Admin Dashboard
2. Click "Manage Wallets"
3. See all startup and student wallets
4. Add or deduct money with one click
5. Track all transactions

### For Startups:
1. See wallet balance on dashboard
2. Create tasks (money locked in escrow automatically)
3. When student submits and you accept:
   - Money released to student
4. If rejected:
   - Money refunded to startup wallet

### For Students:
1. See wallet balance on dashboard
2. Complete tasks
3. When startup accepts:
   - Money added to wallet automatically
4. View transaction history

---

## Admin Wallet Management

### Add Money to Startup:
1. Admin Dashboard → Manage Wallets
2. Find startup in list
3. Click "Add Money"
4. Enter amount (e.g., 1000)
5. Add description (optional)
6. Click "Add Money"
7. Done! Startup can now create tasks

### Add Money to Student:
Same process as above, but in the Students section

### Deduct Money:
1. Click "Deduct" button
2. Enter amount
3. Add reason (optional)
4. Confirm

---

## Test Flow

### Step 1: Add Money to Startup
```
Admin → Manage Wallets → Find "Test Startup" → Add Money → ₹1000
```

### Step 2: Startup Creates Task
```
Startup Dashboard → Create Task → Set stipend ₹500
Result: ₹500 locked in escrow, wallet shows ₹500 remaining
```

### Step 3: Student Applies & Submits
```
Student applies → Gets approved → Submits work
```

### Step 4: Startup Accepts
```
Startup reviews → Accepts submission
Result: 
- Student gets ₹450 (₹50 platform fee)
- Student gets points + certificate
- Escrow released automatically
```

### Step 5: View Transactions
```
Both can go to Wallet page to see transaction history
```

---

## Features

✅ No payment gateway signup needed
✅ Admin controls all money
✅ Escrow system still works (anti-scam)
✅ Automatic money release on accept
✅ Automatic refund on reject
✅ Transaction history for everyone
✅ Platform fee (10%) still applies
✅ Perfect for testing and MVP

---

## Routes

- **Admin**: `/admin/wallets` - Manage all wallets
- **Everyone**: `/wallet` - View own wallet and transactions

---

## Database

Everything is tracked in:
- `startup_profiles.wallet_balance`
- `student_profiles.wallet_balance`
- `transactions` table (all money movements)
- `escrows` table (locked funds)

---

## When to Add Real Payments

Later, when you're ready to go live, you can:
1. Integrate Razorpay/Paytm/PhonePe
2. Let startups add money themselves
3. Let students withdraw to bank
4. Keep admin panel for manual adjustments

For now, this manual system is perfect for:
- Testing
- MVP/Demo
- Small scale operations
- Full control

---

## Status: READY TO USE! 🚀

No signup, no verification, no hassle. Just login as admin and start managing wallets!

**Admin Login:**
- Go to `/admin/wallets`
- Add money to any user
- Everything else works automatically!
