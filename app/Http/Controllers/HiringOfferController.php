<?php

namespace App\Http\Controllers;

use App\Models\HiringOffer;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;

class HiringOfferController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_profile_id' => 'required|exists:student_profiles,id',
            'offer_type' => 'required|in:internship,job',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'compensation' => 'required|numeric|min:0',
            'compensation_period' => 'required|in:monthly,annual',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'contract_terms' => 'nullable|string',
            'source_task_id' => 'nullable|exists:tasks,id',
            'agreement' => 'required|accepted',
        ], [
            'agreement.accepted' => 'You must confirm that this hiring process will be completed through InternGrowth.'
        ]);

        // Validate contact leaks
        \App\Helpers\ContactDetector::validate($validated['description'], 'description');
        if (!empty($validated['contract_terms'])) {
            \App\Helpers\ContactDetector::validate($validated['contract_terms'], 'contract_terms');
        }

        $startup = auth()->user()->startupProfile;

        if (!$startup) {
            return back()->with('error', 'Only startups can extend hiring offers.');
        }

        // Block if startup is not verified and active
        if (!$startup->isVerifiedAndActive()) {
            return back()->with('error', 'Your startup account must be verified by an admin and active before you can extend hiring offers.');
        }

        // Block if startup has outstanding negative balance
        if ($startup->wallet_balance < 0) {
            return back()->with('error', 'You cannot extend new offers until your outstanding balance is cleared.');
        }

        $student = StudentProfile::with('user')->findOrFail($validated['student_profile_id']);

        // Compute success fee based on user criteria
        $successFee = 0.00;
        if ($validated['offer_type'] === 'internship') {
            // Check if there is already an active (pending or accepted) promotional offer (fee = 0)
            $hasPromoClaimed = HiringOffer::where('startup_profile_id', $startup->id)
                ->where('offer_type', 'internship')
                ->whereIn('status', ['pending', 'accepted'])
                ->where('reserved_fee', 0.00)
                ->exists();
            
            // First hire is promo ₹0, subsequent is ₹1,999
            $successFee = $hasPromoClaimed ? 1999.00 : 0.00;
        } else {
            // Job: 5% of CTC
            $annualCTC = $validated['compensation_period'] === 'annual' 
                ? $validated['compensation'] 
                : $validated['compensation'] * 12;
            $successFee = $annualCTC * 0.05;
        }

        // Block sending offer if wallet funds are insufficient to cover reservation fee
        if ($startup->wallet_balance < $successFee) {
            return back()->with('error', 'Insufficient funds in wallet to cover the success fee reservation (Required: ₹' . number_format($successFee, 2) . ', Current Balance: ₹' . number_format($startup->wallet_balance, 2) . '). Please top up your wallet.');
        }

        $validated['startup_profile_id'] = $startup->id;
        $validated['status'] = 'pending';
        $validated['expires_at'] = now()->addDays(7);
        $validated['reserved_fee'] = $successFee;

        $offer = \Illuminate\Support\Facades\DB::transaction(function() use ($validated, $startup, $successFee, $student) {
            $offer = HiringOffer::create($validated);

            if ($successFee > 0) {
                // Deduct from startup wallet
                $startup->decrement('wallet_balance', $successFee);

                // Record debit transaction for startup
                Transaction::create([
                    'user_type' => 'startup',
                    'user_id' => $startup->id,
                    'type' => 'debit',
                    'amount' => $successFee,
                    'description' => "Reserved success fee for offer ID: {$offer->id} ({$offer->offer_type}) to {$student->user->name}",
                    'reference_id' => "offer_{$offer->id}"
                ]);
            }

            return $offer;
        });

        // Log the agreement details on the application if it exists
        if ($offer->source_task_id) {
            $application = \App\Models\Application::where('task_id', $offer->source_task_id)
                ->where('student_profile_id', $offer->student_profile_id)
                ->first();
            if ($application) {
                $application->update([
                    'agreement_accepted' => true,
                    'agreement_accepted_at' => now(),
                    'agreement_ip' => $request->ip()
                ]);
            }
        }

        // Notify student
        Notification::create([
            'user_id' => $offer->student->user_id,
            'title' => 'New Hiring Offer Received',
            'message' => "You have received a new {$offer->offer_type} offer from {$startup->company_name}: '{$offer->title}'",
            'type' => 'info'
        ]);

        return back()->with('success', 'Hiring offer sent successfully! Success fee has been reserved.');
    }

    public function accept($id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

        if (!auth()->user()->studentProfile || $offer->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($offer->status !== 'pending') {
            return back()->with('error', 'This offer is no longer pending.');
        }

        $startup = $offer->startup;
        $student = $offer->student;

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $startup) {
            $offer->update(['status' => 'accepted']);

            // Sync with application status and outcomes
            if ($offer->source_task_id) {
                $application = \App\Models\Application::where('task_id', $offer->source_task_id)
                    ->where('student_profile_id', $offer->student_profile_id)
                    ->first();
                if ($application) {
                    $hasInterview = \App\Models\Interview::where('task_id', $offer->source_task_id)
                        ->where('student_profile_id', $offer->student_profile_id)
                        ->exists();
                    $hiredVia = $hasInterview ? 'interview' : 'task';
                    $targetStatus = $offer->offer_type === 'internship' ? 'internship_accepted' : 'hired';
                    $targetOutcome = $offer->offer_type === 'internship' ? 'hired_intern' : 'hired_job';
                    
                    $application->update([
                        'status' => $targetStatus,
                        'startup_hiring_outcome' => $targetOutcome,
                        'hired_via' => $hiredVia
                    ]);
                }
            }

            // Record credit transaction for platform if a fee was reserved
            if ($offer->reserved_fee > 0) {
                Transaction::create([
                    'user_type' => 'platform',
                    'user_id' => 0,
                    'type' => 'credit',
                    'amount' => $offer->reserved_fee,
                    'description' => "Recruitment success fee from {$startup->company_name} for offer ID: {$offer->id}",
                    'reference_id' => "offer_{$offer->id}"
                ]);
            }
        });

        // Notify startup
        $feeText = $offer->reserved_fee > 0 ? "A placement success fee of ₹{$offer->reserved_fee} has been charged." : "This is your first promotional placement hire (₹0 fee charged).";
        Notification::create([
            'user_id' => $startup->user_id,
            'title' => 'Hiring Offer Accepted!',
            'message' => "{$student->user->name} has accepted your {$offer->offer_type} offer for '{$offer->title}'. {$feeText}",
            'type' => 'success'
        ]);

        return back()->with('success', 'Offer accepted successfully! Startup has been notified.');
    }

    public function reject($id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

        if (!auth()->user()->studentProfile || $offer->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($offer->status !== 'pending') {
            return back()->with('error', 'This offer is no longer pending.');
        }

        $startup = $offer->startup;

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $startup) {
            $offer->update(['status' => 'rejected']);

            // Refund the reserved fee to startup wallet
            if ($offer->reserved_fee > 0) {
                $startup->increment('wallet_balance', $offer->reserved_fee);

                Transaction::create([
                    'user_type' => 'startup',
                    'user_id' => $startup->id,
                    'type' => 'credit',
                    'amount' => $offer->reserved_fee,
                    'description' => "Refunded success fee reservation for declined offer ID: {$offer->id}",
                    'reference_id' => "offer_{$offer->id}"
                ]);
            }
        });

        // Notify startup
        Notification::create([
            'user_id' => $offer->startup->user_id,
            'title' => 'Hiring Offer Declined',
            'message' => "{$offer->student->user->name} has declined your {$offer->offer_type} offer for '{$offer->title}'.",
            'type' => 'warning'
        ]);

        return back()->with('success', 'Offer declined successfully.');
    }

    public function withdraw($id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

        if (!auth()->user()->startupProfile || $offer->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($offer->status !== 'pending') {
            return back()->with('error', 'This offer cannot be withdrawn.');
        }

        $startup = $offer->startup;

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $startup) {
            $offer->update(['status' => 'withdrawn']);

            // Refund the reserved fee to startup wallet
            if ($offer->reserved_fee > 0) {
                $startup->increment('wallet_balance', $offer->reserved_fee);

                Transaction::create([
                    'user_type' => 'startup',
                    'user_id' => $startup->id,
                    'type' => 'credit',
                    'amount' => $offer->reserved_fee,
                    'description' => "Refunded success fee reservation for withdrawn offer ID: {$offer->id}",
                    'reference_id' => "offer_{$offer->id}"
                ]);
            }
        });

        // Notify student
        Notification::create([
            'user_id' => $offer->student->user_id,
            'title' => 'Offer Withdrawn',
            'message' => "{$offer->startup->company_name} has withdrawn their offer for '{$offer->title}'.",
            'type' => 'info'
        ]);

        return back()->with('success', 'Offer withdrawn successfully.');
    }

    public function counterOffer(Request $request, $id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

        if (!auth()->user()->studentProfile || $offer->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($offer->status !== 'pending') {
            return back()->with('error', 'This offer is no longer pending.');
        }

        $validated = $request->validate([
            'counter_compensation' => 'required|numeric|min:0',
            'counter_note' => 'required|string|max:1000'
        ]);

        \App\Helpers\ContactDetector::validate($validated['counter_note'], 'counter_note');

        $startup = $offer->startup;

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $validated, $startup) {
            $offer->update([
                'status' => 'countered',
                'counter_compensation' => $validated['counter_compensation'],
                'counter_note' => $validated['counter_note']
            ]);

            // Refund reserved fee to startup wallet
            if ($offer->reserved_fee > 0) {
                $startup->increment('wallet_balance', $offer->reserved_fee);

                Transaction::create([
                    'user_type' => 'startup',
                    'user_id' => $startup->id,
                    'type' => 'credit',
                    'amount' => $offer->reserved_fee,
                    'description' => "Refunded success fee reservation for countered offer ID: {$offer->id}",
                    'reference_id' => "offer_{$offer->id}"
                ]);
            }
        });

        // Notify startup
        Notification::create([
            'user_id' => $offer->startup->user_id,
            'title' => 'Offer Countered by Student',
            'message' => "{$offer->student->user->name} has countered your {$offer->offer_type} offer for '{$offer->title}' with ₹" . number_format($validated['counter_compensation']),
            'type' => 'warning'
        ]);

        // Recalculate student IPRS
        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($offer->student_profile_id);

        return back()->with('success', 'Counter-offer sent successfully.');
    }
}
