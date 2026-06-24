<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTopupRequest;
use App\Models\Transaction;
use App\Models\Notification;

class WalletTopupController extends Controller
{
    // ─── Startup: submit a top-up request ───────────────────────────────────

    public function create()
    {
        $startup = auth()->user()->startupProfile;
        $requests = WalletTopupRequest::where('startup_profile_id', $startup->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wallet.topup', compact('startup', 'requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount'                => 'required|numeric|min:100',
            'payment_method'        => 'required|in:bank_transfer,upi,cheque,other',
            'transaction_reference' => 'nullable|string|max:100',
            'notes'                 => 'nullable|string|max:500',
        ]);

        $startup = auth()->user()->startupProfile;

        // Prevent duplicate pending requests
        $hasPending = WalletTopupRequest::where('startup_profile_id', $startup->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'You already have a pending top-up request. Please wait for admin approval.');
        }

        WalletTopupRequest::create([
            'startup_profile_id'    => $startup->id,
            'amount'                => $validated['amount'],
            'payment_method'        => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'notes'                 => $validated['notes'] ?? null,
            'status'                => 'pending',
        ]);

        return back()->with('success', 'Top-up request submitted! Admin will review and add money to your wallet.');
    }

    // ─── Admin: list all top-up requests ────────────────────────────────────

    public function adminIndex()
    {
        $pending  = WalletTopupRequest::with('startup.user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewed = WalletTopupRequest::with('startup.user')
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('reviewed_at', 'desc')
            ->paginate(20);

        return view('admin.topup-requests', compact('pending', 'reviewed'));
    }

    // ─── Admin: approve a request ────────────────────────────────────────────

    public function approve(Request $request, $id)
    {
        $topup = WalletTopupRequest::with('startup')->findOrFail($id);

        if (!$topup->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($topup, $validated) {
            // Credit the startup wallet
            $startup = $topup->startup;
            $startup->increment('wallet_balance', $topup->amount);

            // Record transaction
            Transaction::create([
                'user_type'    => 'startup',
                'user_id'      => $startup->id,
                'type'         => 'credit',
                'amount'       => $topup->amount,
                'description'  => 'Wallet top-up approved by admin',
                'reference_id' => 'topup_' . $topup->id,
            ]);

            // Update request status
            $topup->update([
                'status'      => 'approved',
                'admin_notes' => $validated['admin_notes'] ?? null,
                'reviewed_at' => now(),
            ]);

            // Notify startup
            Notification::create([
                'user_id' => $startup->user_id,
                'title'   => 'Wallet Top-up Approved',
                'message' => '₹' . number_format($topup->amount, 2) . ' has been added to your wallet.',
                'type'    => 'success',
            ]);
        });

        return back()->with('success', '₹' . number_format($topup->amount, 2) . ' added to ' . $topup->startup->company_name . '\'s wallet.');
    }

    // ─── Admin: reject a request ─────────────────────────────────────────────

    public function reject(Request $request, $id)
    {
        $topup = WalletTopupRequest::with('startup')->findOrFail($id);

        if (!$topup->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($topup, $validated) {
            $topup->update([
                'status'      => 'rejected',
                'admin_notes' => $validated['admin_notes'],
                'reviewed_at' => now(),
            ]);

            // Notify startup
            Notification::create([
                'user_id' => $topup->startup->user_id,
                'title'   => 'Wallet Top-up Rejected',
                'message' => 'Your top-up request of ₹' . number_format($topup->amount, 2) . ' was rejected. Reason: ' . $validated['admin_notes'],
                'type'    => 'warning',
            ]);
        });

        return back()->with('success', 'Top-up request rejected.');
    }
}
