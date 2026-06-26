<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use App\Models\StudentProfile;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WithdrawalController extends Controller
{
    /**
     * Show student withdrawal request form and history.
     */
    public function index()
    {
        $student = auth()->user()->studentProfile;
        if (!$student) {
            abort(403, 'Only students can request withdrawals.');
        }

        $requests = WithdrawalRequest::where('student_profile_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $hasPending = $requests->contains('status', 'pending');

        return view('student.wallet.withdraw', compact('student', 'requests', 'hasPending'));
    }

    /**
     * Store student withdrawal request.
     */
    public function store(Request $request)
    {
        $student = auth()->user()->studentProfile;
        if (!$student) {
            abort(403, 'Only students can request withdrawals.');
        }

        // Enforce maximum 1 pending request rule
        $pendingExists = WithdrawalRequest::where('student_profile_id', $student->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingExists) {
            return back()->with('error', 'You already have an active pending withdrawal request.');
        }

        // Validate request data
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'method' => 'required|in:bank,upi',
            // Bank details
            'bank_name' => 'required_if:method,bank|nullable|string|max:255',
            'account_number' => 'required_if:method,bank|nullable|string|max:50',
            'ifsc_code' => 'required_if:method,bank|nullable|string|max:20',
            // UPI details
            'upi_id' => 'required_if:method,upi|nullable|string|max:100',
        ], [
            'amount.min' => 'Minimum withdrawal amount is ₹100.',
            'bank_name.required_if' => 'Bank name is required for bank transfer method.',
            'account_number.required_if' => 'Account number is required for bank transfer method.',
            'ifsc_code.required_if' => 'IFSC code is required for bank transfer method.',
            'upi_id.required_if' => 'UPI ID is required for UPI method.',
        ]);

        // Enforce sufficiency check (pre-flight check, also checked inside transaction during admin approval)
        if ($student->wallet_balance < $validated['amount']) {
            return back()->withInput()->withErrors([
                'amount' => 'Insufficient wallet balance. Current balance is ₹' . number_format($student->wallet_balance, 2)
            ]);
        }

        // Save withdrawal request as pending
        WithdrawalRequest::create([
            'student_profile_id' => $student->id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'ifsc_code' => $validated['ifsc_code'] ?? null,
            'upi_id' => $validated['upi_id'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('student.wallet.withdraw')->with('success', 'Withdrawal request submitted successfully.');
    }

    /**
     * Admin view of all requests.
     */
    public function adminIndex(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $query = WithdrawalRequest::with('studentProfile.user')
            ->orderBy('created_at', 'desc');
            
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $requests = $query->get();

        return view('admin.withdrawals', compact('requests', 'status'));
    }

    /**
     * Admin approve request.
     */
    public function adminApprove(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::transaction(function () use ($id, $request) {
                // Lock request
                $withdrawal = WithdrawalRequest::lockForUpdate()->findOrFail($id);

                if ($withdrawal->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'error' => 'This request has already been processed.'
                    ]);
                }

                // Lock student profile
                $student = StudentProfile::lockForUpdate()->findOrFail($withdrawal->student_profile_id);

                // Enforce sufficiency check
                if ($student->wallet_balance < $withdrawal->amount) {
                    throw ValidationException::withMessages([
                        'error' => 'Student has insufficient wallet balance to complete this withdrawal (Balance: ₹' . $student->wallet_balance . ').'
                    ]);
                }

                // Deduct student wallet balance
                $student->decrement('wallet_balance', $withdrawal->amount);

                // Create Transaction ledger entry (debit type: 'withdrawal')
                Transaction::create([
                    'user_type' => 'student',
                    'user_id' => $student->id,
                    'type' => 'withdrawal',
                    'amount' => $withdrawal->amount,
                    'description' => "Withdrawal request #{$withdrawal->id} processed via " . strtoupper($withdrawal->method),
                    'reference_id' => "withdraw_{$withdrawal->id}"
                ]);

                // Update request
                $withdrawal->update([
                    'status' => 'approved',
                    'admin_notes' => $request->admin_notes,
                    'processed_at' => now(),
                ]);

                // Notify student
                Notification::create([
                    'user_id' => $student->user->id,
                    'title' => 'Withdrawal Request Approved',
                    'message' => "Your withdrawal request for ₹" . number_format($withdrawal->amount, 2) . " has been approved.",
                    'type' => 'success'
                ]);
            });

            return back()->with('success', 'Withdrawal request approved and processed.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Admin reject request.
     */
    public function adminReject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ], [
            'admin_notes.required' => 'Please provide a reason for rejecting the withdrawal request.'
        ]);

        try {
            DB::transaction(function () use ($id, $request) {
                // Lock request
                $withdrawal = WithdrawalRequest::lockForUpdate()->findOrFail($id);

                if ($withdrawal->status !== 'pending') {
                    throw ValidationException::withMessages([
                        'error' => 'This request has already been processed.'
                    ]);
                }

                // Update request
                $withdrawal->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->admin_notes,
                    'processed_at' => now(),
                ]);

                // Notify student
                $student = StudentProfile::findOrFail($withdrawal->student_profile_id);
                Notification::create([
                    'user_id' => $student->user->id,
                    'title' => 'Withdrawal Request Rejected',
                    'message' => "Your withdrawal request for ₹" . number_format($withdrawal->amount, 2) . " was rejected. Reason: " . $request->admin_notes,
                    'type' => 'warning'
                ]);
            });

            return back()->with('success', 'Withdrawal request rejected.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}
