<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    /**
     * Display the plans and features.
     */
    public function index()
    {
        $user = auth()->user();
        $activeSubscription = $user ? $user->activeSubscription() : null;

        return view('pricing.index', compact('user', 'activeSubscription'));
    }

    /**
     * Simulate an instant upgrade under demo mode.
     */
    public function upgrade(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:student_pro,startup_growth',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to upgrade your plan.');
        }

        $plan = $request->plan;
        $cycle = $request->billing_cycle;
        
        // Define pricing
        $prices = [
            'student_pro' => ['monthly' => 99.00, 'yearly' => 999.00],
            'startup_growth' => ['monthly' => 999.00, 'yearly' => 9999.00],
        ];

        $amount = $prices[$plan][$cycle] ?? 0.00;

        // Perform instant simulation upgrade
        $this->subscriptionService->activatePlan(
            $user,
            $plan,
            $cycle,
            $amount,
            'DEMO_PAY_' . strtoupper(uniqid())
        );

        $planLabel = $plan === 'student_pro' ? 'InternGrowth Pro' : 'Startup Growth';

        return redirect()->route($user->isStudent() ? 'dashboard' : 'startup.dashboard')
            ->with('success', "💳 Payment Simulated Successfully! You have been upgraded to {$planLabel} ({$cycle}).");
    }

    /**
     * Cancel active plan.
     */
    public function cancel()
    {
        $user = auth()->user();
        if ($user) {
            $this->subscriptionService->cancelPlan($user);
            return back()->with('success', 'Subscription cancelled successfully.');
        }
        return back();
    }
}
