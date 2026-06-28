<?php

namespace App\Services;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Activate a subscription plan for a user.
     */
    public function activatePlan(User $user, string $planType, string $billingCycle, float $amount, ?string $paymentRef = null): Subscription
    {
        return DB::transaction(function () use ($user, $planType, $billingCycle, $amount, $paymentRef) {
            // 1. Mark existing active subscriptions as cancelled/expired
            $user->subscriptions()
                ->whereIn('status', ['active', 'trial'])
                ->update(['status' => 'cancelled']);

            // 2. Compute expiration date
            $startsAt = now();
            $expiresAt = $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth();

            // 3. Create Subscription record
            $descMap = [
                'student_pro' => 'InternGrowth Pro ' . ucfirst($billingCycle),
                'startup_growth' => 'Startup Growth ' . ucfirst($billingCycle),
                'enterprise' => 'Enterprise Custom Contract',
            ];
            $description = $descMap[$planType] ?? 'Subscription Plan';

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_type' => $planType,
                'billing_cycle' => $billingCycle,
                'status' => 'active',
                'amount' => $amount,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'payment_reference' => $paymentRef ?? 'REF_' . strtoupper(uniqid()),
                'description' => $description,
            ]);

            // 4. Update fields directly on the User model
            $user->update([
                'subscription_status' => 'active',
                'subscription_plan' => $planType,
                'trial_ends_at' => null,
            ]);

            // 5. Create Transaction record for revenue ledger
            Transaction::create([
                'user_type' => 'platform',
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => "Purchased subscription: {$description} ({$user->name})",
                'reference_id' => 'sub_' . $subscription->id,
                'status' => 'active',
            ]);

            // 6. Notify user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => '🌟 Plan Activated!',
                'message' => "Congratulations! You are now subscribed to {$description}. Benefits are unlocked.",
                'type' => 'success',
            ]);

            return $subscription;
        });
    }

    /**
     * Start a 7-day trial for a user.
     */
    public function startTrial(User $user, string $planType): Subscription
    {
        return DB::transaction(function () use ($user, $planType) {
            $user->subscriptions()
                ->whereIn('status', ['active', 'trial'])
                ->update(['status' => 'expired']);

            $startsAt = now();
            $expiresAt = now()->addDays(7);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_type' => $planType,
                'billing_cycle' => 'monthly',
                'status' => 'trial',
                'amount' => 0.00,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'payment_reference' => 'TRIAL_' . strtoupper(uniqid()),
                'description' => '7-Day Free Trial: ' . str_replace('_', ' ', ucfirst($planType)),
            ]);

            $user->update([
                'subscription_status' => 'trial',
                'subscription_plan' => $planType,
                'trial_ends_at' => $expiresAt,
            ]);

            return $subscription;
        });
    }

    /**
     * Cancel active plan.
     */
    public function cancelPlan(User $user): void
    {
        DB::transaction(function () use ($user) {
            $activeSub = $user->activeSubscription();
            if ($activeSub) {
                $activeSub->update(['status' => 'cancelled']);
            }

            $user->update([
                'subscription_status' => 'inactive',
                'subscription_plan' => 'free',
            ]);
        });
    }

    /**
     * Calculate subscription statistics and revenue metrics for the Admin Dashboard.
     */
    public function calculateRevenueMetrics(): array
    {
        // 1. Get transaction payments
        $subTxQuery = Transaction::where('type', 'subscription_payment')->where('status', 'completed');
        $totalSubscriptionRev = $subTxQuery->sum('amount');

        // Let's get active user plans
        $totalStudents = User::where('role', 'student')->count();
        $proStudents = User::where('role', 'student')->where('subscription_status', 'active')->count();
        
        $totalStartups = User::where('role', 'startup')->count();
        $growthStartups = User::where('role', 'startup')->where('subscription_status', 'active')->count();

        // 2. MRR (Monthly Recurring Revenue)
        // Student Pro (₹99/month), Startup Growth (₹999/month)
        $mrr = ($proStudents * 99) + ($growthStartups * 999);
        $arr = $mrr * 12;

        // 3. ARPUs
        $activeSubscribersCount = $proStudents + $growthStartups;
        $totalUsers = max(1, $totalStudents + $totalStartups);
        $arpu = $activeSubscribersCount > 0 ? round($mrr / $activeSubscribersCount, 2) : 0.00;

        // 4. Conversion Rate (percentage of total users subscribed)
        $conversionRate = round(($activeSubscribersCount / $totalUsers) * 100, 1);

        // 5. Success Fees revenue (₹1,999) from non-free hiring offers, plus PPO conversions (5% CTC success fee)
        $hiringFeesQuery = Transaction::where('type', 'hiring_fee')->orWhere('description', 'like', '%success fee%');
        $totalHiringFees = $hiringFeesQuery->sum('amount');

        return [
            'mrr' => $mrr,
            'arr' => $arr,
            'active_subscribers' => $activeSubscribersCount,
            'student_pro_count' => $proStudents,
            'startup_growth_count' => $growthStartups,
            'conversion_rate' => $conversionRate,
            'arpu' => $arpu,
            'total_subscription_revenue' => $totalSubscriptionRev,
            'total_hiring_fees' => $totalHiringFees,
            'total_revenue' => $totalSubscriptionRev + $totalHiringFees,
        ];
    }
}
