<?php

namespace App\Http\Controllers;

use App\Models\HiringOffer;
use App\Models\WeeklyReport;
use App\Models\InternshipUpdate;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StartupTeamController extends Controller
{
    public function index()
    {
        $startup = auth()->user()->startupProfile;
        if (!$startup) {
            abort(403, 'Startup profile not found.');
        }

        // Active Interns (joined or active)
        $activeOffers = HiringOffer::where('startup_profile_id', $startup->id)
            ->whereIn('status', ['joined', 'active'])
            ->with(['student.user', 'updates', 'weeklyReports'])
            ->latest()
            ->get()
            ->map(function ($offer) {
                // Calculate progress percentage
                if ($offer->start_date && $offer->end_date) {
                    $totalDays = max(1, $offer->start_date->diffInDays($offer->end_date));
                    $daysPassed = max(0, $offer->start_date->diffInDays(now()));
                    $offer->progress_pct = min(100, round(($daysPassed / $totalDays) * 100));
                } else {
                    $reportsCount = $offer->weeklyReports->count();
                    $offer->progress_pct = min(100, $reportsCount * 10);
                }
                return $offer;
            });

        // Previous Hires (completed)
        $previousOffers = HiringOffer::where('startup_profile_id', $startup->id)
            ->where('status', 'completed')
            ->with('student.user')
            ->latest()
            ->get();

        // Analytics Calculations
        $activeCount = $activeOffers->count();
        $completedCount = $previousOffers->count();
        
        $totalAcceptedOffers = HiringOffer::where('startup_profile_id', $startup->id)
            ->whereIn('status', ['joined', 'completed', 'active', 'bypassed_penalized'])
            ->count();

        $conversionRate = $totalAcceptedOffers > 0 
            ? round(($completedCount / $totalAcceptedOffers) * 100, 1) 
            : 0;

        $avgRating = $previousOffers->where('hiring_success_rating', '>', 0)->avg('hiring_success_rating') ?: 0.0;
        $avgRating = round($avgRating, 1);

        // Average duration (in weeks) of completed internships
        $durations = $previousOffers->map(function ($offer) {
            if ($offer->start_date && $offer->completed_at) {
                return $offer->start_date->diffInWeeks($offer->completed_at);
            }
            return 0;
        })->filter();

        $avgDuration = $durations->count() > 0 ? round($durations->average(), 1) : 0;

        $totalConversions = HiringOffer::where('startup_profile_id', $startup->id)
            ->where('converted_to_full_time', true)
            ->count();

        $analytics = [
            'active_interns' => $activeCount,
            'completed_internships' => $completedCount,
            'conversion_rate' => $conversionRate,
            'avg_rating' => $avgRating,
            'avg_duration' => $avgDuration,
            'total_hires' => $totalAcceptedOffers,
            'conversions' => $totalConversions
        ];

        return view('startup.team.index', compact('activeOffers', 'previousOffers', 'analytics'));
    }

    public function viewWork($offerId)
    {
        $startup = auth()->user()->startupProfile;
        $offer = HiringOffer::where('startup_profile_id', $startup->id)
            ->with(['student.user', 'updates' => function($q) {
                $q->latest();
            }])
            ->findOrFail($offerId);

        return view('startup.team.work', compact('offer'));
    }

    public function viewReports($offerId)
    {
        $startup = auth()->user()->startupProfile;
        $offer = HiringOffer::where('startup_profile_id', $startup->id)
            ->with(['student.user', 'weeklyReports' => function($q) {
                $q->orderBy('week_number', 'asc');
            }])
            ->findOrFail($offerId);

        return view('startup.team.reports', compact('offer'));
    }

    public function submitWeeklyFeedback(Request $request, $reportId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'startup_feedback' => 'required|string|max:1000'
        ]);

        $report = WeeklyReport::with('hiringOffer.startup')->findOrFail($reportId);
        
        // Ensure this belongs to the logged-in startup
        if ($report->hiringOffer->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403);
        }

        $report->update([
            'rating' => $request->rating,
            'startup_feedback' => $request->startup_feedback
        ]);

        // Notify student
        Notification::create([
            'user_id' => $report->student->user_id,
            'title' => "Feedback on Week {$report->week_number} Report",
            'message' => "{$report->hiringOffer->startup->company_name} rated your Week {$report->week_number} report: {$request->rating}/5 stars.",
            'type' => 'success'
        ]);

        return back()->with('success', 'Weekly feedback and rating submitted successfully.');
    }

    public function showConversionForm($offerId)
    {
        $startup = auth()->user()->startupProfile;
        $offer = HiringOffer::where('startup_profile_id', $startup->id)
            ->where('status', 'completed')
            ->with('student.user')
            ->findOrFail($offerId);

        return view('startup.team.convert', compact('offer'));
    }

    public function processConversion(Request $request, $offerId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'compensation' => 'required|numeric|min:0',
            'contract_terms' => 'nullable|string'
        ]);

        $startup = auth()->user()->startupProfile;
        $offer = HiringOffer::where('startup_profile_id', $startup->id)
            ->where('status', 'completed')
            ->findOrFail($offerId);

        // Calculate success fee (5% CTC success fee for full-time job)
        // Compensation here represents the Annual CTC
        $fee = floatval($request->compensation) * 0.05;

        // Check startup wallet balance
        if ($startup->wallet_balance < $fee) {
            return back()->withInput()->with('error', "Insufficient wallet balance to send full-time job offer. Required success fee: ₹" . number_format($fee, 2) . ", Current Balance: ₹" . number_format($startup->wallet_balance, 2));
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($offer, $startup, $request, $fee) {
            // Deduct success fee upfront (held in escrow/reserved)
            $startup->decrement('wallet_balance', $fee);

            // Create new job hiring offer
            $newOffer = HiringOffer::create([
                'startup_profile_id' => $startup->id,
                'student_profile_id' => $offer->student_profile_id,
                'source_task_id' => $offer->source_task_id,
                'offer_type' => 'job',
                'title' => $request->title,
                'description' => $request->description,
                'compensation' => $request->compensation,
                'compensation_period' => 'annual',
                'start_date' => now()->addDays(7)->toDateString(),
                'status' => 'pending',
                'contract_terms' => $request->contract_terms,
                'reserved_fee' => $fee,
                'expires_at' => now()->addDays(7)
            ]);

            // Mark old internship as converted
            $offer->update([
                'converted_to_full_time' => true
            ]);

            // Notify student
            Notification::create([
                'user_id' => $offer->student->user_id,
                'title' => 'Full-time Job Conversion Offer!',
                'message' => "Congratulations! {$startup->company_name} has offered you a full-time job position as '{$request->title}' following your internship.",
                'type' => 'success'
            ]);
        });

        return redirect()->route('startup.team.index')->with('success', 'Full-time conversion job offer successfully sent to the candidate.');
    }
}
