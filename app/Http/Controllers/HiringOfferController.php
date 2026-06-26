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
            // One promotional ₹0 internship offer per startup (lifetime)
            $hasPromoClaimed = HiringOffer::where('startup_profile_id', $startup->id)
                ->where('offer_type', 'internship')
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

        $validated['startup_profile_id'] = $startup->id;
        $validated['status'] = 'pending';
        $validated['expires_at'] = now()->addDays(7);
        $validated['reserved_fee'] = $successFee;

        $offer = \Illuminate\Support\Facades\DB::transaction(function() use ($validated, $successFee, $student) {
            $startup = StartupProfile::lockForUpdate()->findOrFail(auth()->user()->startupProfile->id);

            if ($startup->wallet_balance < $successFee) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'wallet' => 'Insufficient funds in wallet to cover the success fee reservation (Required: ₹' . number_format($successFee, 2) . ', Current Balance: ₹' . number_format($startup->wallet_balance, 2) . '). Please top up your wallet.'
                ]);
            }

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

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer) {
            $offer->update([
                'status' => 'pending_joining',
                'student_joining_status' => 'pending',
                'startup_joining_status' => 'pending'
            ]);

            // Sync with application status and outcomes
            if ($offer->source_task_id) {
                $application = \App\Models\Application::where('task_id', $offer->source_task_id)
                    ->where('student_profile_id', $offer->student_profile_id)
                    ->first();
                if ($application) {
                    $application->update([
                        'status' => $offer->offer_type === 'internship' ? 'internship_offered' : 'hired'
                    ]);
                }
            }
        });

        // Notify startup
        Notification::create([
            'user_id' => $startup->user_id,
            'title' => 'Hiring Offer Accepted!',
            'message' => "{$student->user->name} has accepted your {$offer->offer_type} offer for '{$offer->title}'. Please confirm when they join the position.",
            'type' => 'success'
        ]);

        return back()->with('success', 'Offer accepted! The hiring confirmation window is now active.');
    }

    public function reject($id)
    {
        \Illuminate\Support\Facades\DB::transaction(function() use ($id) {
            $offer = HiringOffer::with(['student.user', 'startup'])->lockForUpdate()->findOrFail($id);

            if (!auth()->user()->studentProfile || $offer->student_profile_id !== auth()->user()->studentProfile->id) {
                abort(403, 'Unauthorized action.');
            }

            if ($offer->status !== 'pending') {
                abort(400, 'This offer is no longer pending.');
            }

            $offer->update(['status' => 'rejected']);

            \App\Services\OfferRefundService::refundReservedFee(
                $offer,
                "Refunded success fee reservation for declined offer ID: {$offer->id}"
            );
        });

        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

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
        \Illuminate\Support\Facades\DB::transaction(function() use ($id) {
            $offer = HiringOffer::with(['student.user', 'startup'])->lockForUpdate()->findOrFail($id);

            if (!auth()->user()->startupProfile || $offer->startup_profile_id !== auth()->user()->startupProfile->id) {
                abort(403, 'Unauthorized action.');
            }

            if ($offer->status !== 'pending') {
                abort(400, 'This offer cannot be withdrawn.');
            }

            $offer->update(['status' => 'withdrawn']);

            \App\Services\OfferRefundService::refundReservedFee(
                $offer,
                "Refunded success fee reservation for withdrawn offer ID: {$offer->id}"
            );
        });

        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

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
        $validated = $request->validate([
            'counter_compensation' => 'required|numeric|min:0',
            'counter_note' => 'required|string|max:1000'
        ]);

        \App\Helpers\ContactDetector::validate($validated['counter_note'], 'counter_note');

        \Illuminate\Support\Facades\DB::transaction(function() use ($id, $validated) {
            $offer = HiringOffer::with(['student.user', 'startup'])->lockForUpdate()->findOrFail($id);

            if (!auth()->user()->studentProfile || $offer->student_profile_id !== auth()->user()->studentProfile->id) {
                abort(403, 'Unauthorized action.');
            }

            if ($offer->status !== 'pending') {
                abort(400, 'This offer is no longer pending.');
            }

            $offer->update([
                'status' => 'countered',
                'counter_compensation' => $validated['counter_compensation'],
                'counter_note' => $validated['counter_note']
            ]);

            \App\Services\OfferRefundService::refundReservedFee(
                $offer,
                "Refunded success fee reservation for countered offer ID: {$offer->id}"
            );
        });

        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);

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

    public function confirmJoining(Request $request, $id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);
        $user = auth()->user();

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $user) {
            $offer = HiringOffer::lockForUpdate()->find($offer->id);

            if ($offer->status !== 'pending_joining') {
                abort(400, 'Joining can only be confirmed for offers pending joining.');
            }

            if ($user->isStudent() && $offer->student_profile_id === $user->studentProfile->id) {
                $offer->update(['student_joining_status' => 'joined']);
            } elseif ($user->isStartup() && $offer->startup_profile_id === $user->startupProfile->id) {
                $offer->update(['startup_joining_status' => 'joined']);
            } else {
                abort(403);
            }

            $offer->refresh();

            // Check if BOTH confirmed
            if ($offer->student_joining_status === 'joined' && $offer->startup_joining_status === 'joined') {
                $offer->update([
                    'status' => 'joined',
                    'joining_confirmed_at' => now()
                ]);

                // Record credit transaction for platform if a fee was reserved
                if ($offer->reserved_fee > 0) {
                    Transaction::create([
                        'user_type' => 'platform',
                        'user_id' => 0,
                        'type' => 'credit',
                        'amount' => $offer->reserved_fee,
                        'description' => "Recruitment success fee from {$offer->startup->company_name} for verified offer ID: {$offer->id}",
                        'reference_id' => "offer_{$offer->id}"
                    ]);
                }

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

                // Notify both
                Notification::create([
                    'user_id' => $offer->student->user_id,
                    'title' => 'Placement Verified!',
                    'message' => "Your placement for '{$offer->title}' has been verified. Welcome aboard!",
                    'type' => 'success'
                ]);

                Notification::create([
                    'user_id' => $offer->startup->user_id,
                    'title' => 'Placement Verified!',
                    'message' => "The placement for {$offer->student->user->name} has been verified. Platform fee completed.",
                    'type' => 'success'
                ]);
            }
        });

        return back()->with('success', 'Joining confirmation status updated.');
    }

    public function cancelJoining(Request $request, $id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);
        $user = auth()->user();

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $user) {
            $offer = HiringOffer::lockForUpdate()->find($offer->id);

            if ($offer->status !== 'pending_joining') {
                abort(400, 'Hiring can only be cancelled for offers pending joining.');
            }

            $refundStartup = false;
            $newStatus = 'cancelled';

            if ($user->isStudent() && $offer->student_profile_id === $user->studentProfile->id) {
                $offer->update(['student_joining_status' => 'cancelled']);
                $newStatus = 'cancelled_by_student';
                $refundStartup = true;
            } elseif ($user->isStartup() && $offer->startup_profile_id === $user->startupProfile->id) {
                $offer->update(['startup_joining_status' => 'withdrawn']);
                $newStatus = 'withdrawn_by_startup';
                $refundStartup = true;
            } else {
                abort(403);
            }

            if ($refundStartup) {
                $offer->update(['status' => $newStatus]);

                \App\Services\OfferRefundService::refundReservedFee(
                    $offer,
                    "Refunded reserved fee for failed hiring confirmation on offer ID: {$offer->id}"
                );

                // Notify startup
                Notification::create([
                    'user_id' => $offer->startup->user_id,
                    'title' => 'Placement Cancelled',
                    'message' => "The placement for '{$offer->title}' has been cancelled. Reserved fee refunded.",
                    'type' => 'warning'
                ]);

                // Notify student
                Notification::create([
                    'user_id' => $offer->student->user_id,
                    'title' => 'Placement Cancelled',
                    'message' => "The placement for '{$offer->title}' has been cancelled.",
                    'type' => 'info'
                ]);
            }
        });

        return back()->with('success', 'Hiring placement cancelled and fee refunded.');
    }

    public function completeInternship(Request $request, $id)
    {
        $offer = HiringOffer::with(['student.user', 'startup'])->findOrFail($id);
        $user = auth()->user();

        if (!$user->isStartup() || $offer->startup_profile_id !== $user->startupProfile->id) {
            abort(403);
        }

        if ($offer->status !== 'joined') {
            return back()->with('error', 'Only verified joined internships/jobs can be marked as completed.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'rating' => 'required|in:excellent,good,average,poor,terminated'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $validated) {
            $offer = HiringOffer::lockForUpdate()->find($offer->id);

            $offer->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $validated['notes'] ?? null,
                'hiring_success_rating' => $validated['rating'],
                'hiring_success_rated_at' => now()
            ]);

            // 1. Issue experience certificate
            $certificateNumber = 'EXP-' . strtoupper(uniqid());
            \App\Models\Certificate::create([
                'student_profile_id' => $offer->student_profile_id,
                'hiring_offer_id' => $offer->id,
                'certificate_number' => $certificateNumber,
                'issued_at' => now()
            ]);

            // 2. Add to student experience ledger (portfolio_items)
            $portfolio = \App\Models\Portfolio::firstOrCreate(
                ['student_profile_id' => $offer->student_profile_id],
                [
                    'custom_slug' => 'student-' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $offer->student->user->name))) . '-' . rand(1000, 9999),
                    'is_public' => true
                ]
            );

            $ratingVal = 2.0;
            if ($validated['rating'] === 'excellent') {
                $ratingVal = 5.0;
            } elseif ($validated['rating'] === 'good') {
                $ratingVal = 4.0;
            } elseif ($validated['rating'] === 'average') {
                $ratingVal = 3.0;
            }

            \App\Models\PortfolioItem::create([
                'portfolio_id' => $portfolio->id,
                'hiring_offer_id' => $offer->id,
                'project_title' => "{$offer->title} at {$offer->startup->company_name}",
                'auto_summary' => $offer->description . ($validated['notes'] ? "\n\nCompletion Review: " . $validated['notes'] : ''),
                'skills_demonstrated' => [$offer->domain ?? 'Software Development'],
                'startup_name' => $offer->startup->company_name,
                'certificate_number' => $certificateNumber,
                'completed_at' => now(),
                'domain' => $offer->domain ?? 'Software Development',
                'role' => $offer->role ?? 'Developer',
                'rating_received' => $ratingVal
            ]);

            // 3. Recalculate reputation score
            $reputationService = new \App\Services\ReputationEngineService();
            $reputationService->updateReputation($offer->student_profile_id);

            // Notify student
            Notification::create([
                'user_id' => $offer->student->user_id,
                'title' => 'Internship Completed & Certified!',
                'message' => "Congratulations! Your internship at {$offer->startup->company_name} is marked as completed and verified in your Experience Ledger.",
                'type' => 'success'
            ]);
        });

        return back()->with('success', 'Internship marked as completed! Experience Certificate issued.');
    }
}
