<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    /**
     * Render the Admin Revenue and Unit Economics dashboard.
     */
    public function revenue()
    {
        $metrics = $this->subscriptionService->calculateRevenueMetrics();

        // 1. Transaction log
        $recentTransactions = Transaction::whereIn('type', ['subscription_payment', 'hiring_fee'])
            ->latest()
            ->take(10)
            ->get();

        // 2. Unit economics
        $serverCosts = 1500;
        $storageCosts = 500;
        $aiCosts = 1000;
        $emailCosts = 300;
        $totalOperatingCosts = $serverCosts + $storageCosts + $aiCosts + $emailCosts;

        $grossMargin = $metrics['total_revenue'] > 0
            ? round((($metrics['total_revenue'] - $totalOperatingCosts) / $metrics['total_revenue']) * 100, 1)
            : 100.0;

        return view('admin.revenue', compact(
            'metrics',
            'recentTransactions',
            'serverCosts',
            'storageCosts',
            'aiCosts',
            'emailCosts',
            'totalOperatingCosts',
            'grossMargin'
        ));
    }

    /**
     * Render subscription list and manual control dashboard.
     */
    public function index()
    {
        $subscriptions = Subscription::with('user')->latest()->get();
        $users = User::whereIn('role', ['student', 'startup'])->get();

        return view('admin.subscriptions', compact('subscriptions', 'users'));
    }

    /**
     * Manually assign subscription to a user account.
     */
    public function manualUpgrade(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_type' => 'required|in:student_pro,startup_growth,enterprise',
            'billing_cycle' => 'required|in:monthly,yearly',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $this->subscriptionService->activatePlan(
            $user,
            $validated['plan_type'],
            $validated['billing_cycle'],
            $validated['amount'],
            'MANUAL_ADM_' . strtoupper(uniqid())
        );

        return back()->with('success', "Manually granted {$validated['plan_type']} subscription to {$user->name} ({$user->email}).");
    }
}
