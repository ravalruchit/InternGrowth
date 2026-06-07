# 💰 Complete Payment System with Razorpay - Implementation Plan

## Overview

Building a full escrow-based payment system where:
1. Startups add money to wallet via Razorpay
2. Money locked when creating tasks
3. Released to students when work accepted
4. Students can withdraw to bank account

---

## Database Structure

### New Tables Needed:

1. **wallets** - Store money balances
2. **transactions** - Track all money movements
3. **escrows** - Hold locked funds for tasks
4. **withdrawals** - Student withdrawal requests

---

## Implementation Steps

### Phase 1: Database & Models ✅
- Create migrations for wallets, transactions, escrows, withdrawals
- Create models with relationships
- Add wallet_balance to startup_profiles and student_profiles

### Phase 2: Wallet System ✅
- Startup can view wallet balance
- Add money via Razorpay
- Transaction history

### Phase 3: Escrow System ✅
- Lock funds when task created
- Release funds when submission accepted
- Refund when submission rejected

### Phase 4: Withdrawal System ✅
- Student requests withdrawal
- Admin approves/rejects
- Razorpay payout integration

### Phase 5: Admin Panel ✅
- View all transactions
- Manage withdrawals
- Platform earnings dashboard

---

## Razorpay Setup Required

### 1. Create Razorpay Account
- Go to https://razorpay.com
- Sign up for account
- Complete KYC verification

### 2. Get API Keys
- Dashboard → Settings → API Keys
- Generate Test Keys (for development)
- Generate Live Keys (for production)

### 3. Enable Features
- Enable Payments
- Enable Payouts (for withdrawals)
- Add bank account for settlements

---

## Configuration

Add to `.env`:
```
RAZORPAY_KEY=rzp_test_xxxxxxxxxxxxx
RAZORPAY_SECRET=xxxxxxxxxxxxxxxxxxxxx
RAZORPAY_WEBHOOK_SECRET=xxxxxxxxxxxxx
PLATFORM_FEE_PERCENTAGE=10
```

---

## Flow Diagrams

### Money Flow:

```
Startup Adds Money:
Startup → Razorpay → Platform → Startup Wallet

Task Creation:
Startup Wallet → Escrow (locked)

Work Accepted:
Escrow → Student Wallet (minus platform fee)
Platform Fee → Platform Earnings

Work Rejected:
Escrow → Startup Wallet (refund)

Student Withdrawal:
Student Wallet → Admin Approval → Razorpay Payout → Student Bank
```

---

## Security Considerations

1. **Webhook Verification** - Verify Razorpay webhooks
2. **Transaction Locks** - Prevent double-spending
3. **Audit Trail** - Log all money movements
4. **Balance Checks** - Verify sufficient funds
5. **Fraud Detection** - Monitor suspicious activity

---

## Testing

### Test Mode:
- Use Razorpay test keys
- Test card: 4111 1111 1111 1111
- Any CVV, future expiry date
- Test UPI: success@razorpay

### Production:
- Switch to live keys
- Complete KYC
- Test with small amounts first

---

## Platform Revenue

### Commission Structure:
- 10% platform fee on each task
- Example: Task ₹1,000
  - Student receives: ₹900
  - Platform earns: ₹100

### Revenue Tracking:
- Dashboard shows total earnings
- Monthly/yearly reports
- Withdrawal to business account

---

## Next Steps

I'll now implement this system step by step. This will take approximately 10-12 hours of development work.

Ready to proceed?
