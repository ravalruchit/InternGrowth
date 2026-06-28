<?php

namespace App\Services;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\Submission;
use App\Models\Interview;
use App\Models\HiringOffer;
use App\Models\Transaction;
use App\Models\Escrow;
use App\Models\WalletTopupRequest;
use App\Models\ReputationScore;
use App\Models\StartupTrustScore;
use App\Models\Skill;
use App\Models\SkillVerification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsService
{
    public function getAnalyticsData(): array
    {
        // ── 1. PLATFORM HEALTH SCORE ──────────────────────────────────────────
        $studentVerifyRate = StudentProfile::count() > 0 
            ? (StudentProfile::where('is_verified', true)->count() / StudentProfile::count()) * 100 
            : 100.00;
        $startupVerifyRate = StartupProfile::count() > 0 
            ? (StartupProfile::where('is_verified', true)->count() / StartupProfile::count()) * 100 
            : 100.00;
        $overallVerifyRate = ($studentVerifyRate + $startupVerifyRate) / 2;

        $escrowTotal = Escrow::whereIn('status', ['released', 'refunded'])->count();
        $escrowSuccessRate = $escrowTotal > 0 
            ? (Escrow::where('status', 'released')->count() / $escrowTotal) * 100 
            : 100.00;

        $hiringTotal = HiringOffer::whereIn('status', ['pending_joining', 'rejected', 'joined', 'completed'])->count();
        $hiringSuccessRate = $hiringTotal > 0 
            ? (HiringOffer::whereIn('status', ['joined', 'completed'])->count() / $hiringTotal) * 100 
            : 100.00;

        $avgIprs = ReputationScore::avg('overall_score') ?? 50.00;
        $avgTrust = StartupTrustScore::avg('overall_score') ?? 50.00;

        $healthScore = round(
            ($overallVerifyRate * 0.2) + 
            ($escrowSuccessRate * 0.2) + 
            ($hiringSuccessRate * 0.2) + 
            ($avgIprs * 0.2) + 
            ($avgTrust * 0.2)
        );

        // Calculate previous month's health score trajectory
        $prevMonth = now()->subMonth();
        $prevStudentsCount = StudentProfile::where('created_at', '<', $prevMonth)->count();
        $prevStudentsVerified = StudentProfile::where('created_at', '<', $prevMonth)->where('is_verified', true)->count();
        $prevStudentVerifyRate = $prevStudentsCount > 0 ? ($prevStudentsVerified / $prevStudentsCount) * 100 : 100;

        $prevStartupsCount = StartupProfile::where('created_at', '<', $prevMonth)->count();
        $prevStartupsVerified = StartupProfile::where('created_at', '<', $prevMonth)->where('is_verified', true)->count();
        $prevStartupVerifyRate = $prevStartupsCount > 0 ? ($prevStartupsVerified / $prevStartupsCount) * 100 : 100;
        $prevVerifyRate = ($prevStudentVerifyRate + $prevStartupVerifyRate) / 2;

        $prevEscrowReleased = Escrow::where('created_at', '<', $prevMonth)->where('status', 'released')->count();
        $prevEscrowRefunded = Escrow::where('created_at', '<', $prevMonth)->where('status', 'refunded')->count();
        $prevEscrowTotal = $prevEscrowReleased + $prevEscrowRefunded;
        $prevEscrowSuccessRate = $prevEscrowTotal > 0 ? ($prevEscrowReleased / $prevEscrowTotal) * 100 : 100;

        $prevHiringJoined = HiringOffer::where('created_at', '<', $prevMonth)->whereIn('status', ['joined', 'completed'])->count();
        $prevHiringTotal = HiringOffer::where('created_at', '<', $prevMonth)->whereIn('status', ['pending_joining', 'rejected', 'joined', 'completed'])->count();
        $prevHiringSuccessRate = $prevHiringTotal > 0 ? ($prevHiringJoined / $prevHiringTotal) * 100 : 100;

        $prevHealthScore = round(
            ($prevVerifyRate * 0.2) + 
            ($prevEscrowSuccessRate * 0.2) + 
            ($prevHiringSuccessRate * 0.2) + 
            ($avgIprs * 0.2) + 
            ($avgTrust * 0.2)
        );
        $healthScoreChange = $healthScore - $prevHealthScore;

        // ── 2. EXECUTIVE KPIS ────────────────────────────────────────────────
        $studentsTotal = StudentProfile::count();
        $studentsVerified = StudentProfile::where('is_verified', true)->count();
        $studentsNewThisMonth = StudentProfile::where('created_at', '>=', now()->startOfMonth())->count();

        $startupsTotal = StartupProfile::count();
        $startupsVerified = StartupProfile::where('is_verified', true)->count();
        $startupsNewThisMonth = StartupProfile::where('created_at', '>=', now()->startOfMonth())->count();

        $activeTasks = Task::where('status', 'posted')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $activeApplications = Application::whereNotIn('status', ['rejected', 'hired', 'internship_accepted'])->count();
        $activeInterviews = Interview::whereIn('status', ['pending', 'accepted'])->count();

        $internshipPlacements = HiringOffer::where('offer_type', 'internship')->whereIn('status', ['joined', 'completed'])->count();
        $jobPlacements = HiringOffer::where('offer_type', 'job')->whereIn('status', ['joined', 'completed'])->count();
        $totalHires = $internshipPlacements + $jobPlacements;

        $totalRevenue = Transaction::where('user_type', 'platform')->where('type', 'credit')->where('status', 'active')->sum('amount');
        $revenueThisMonth = Transaction::where('user_type', 'platform')->where('type', 'credit')->where('status', 'active')->where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $revenueLastMonth = Transaction::where('user_type', 'platform')->where('type', 'credit')->where('status', 'active')->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount');
        $escrowLocked = Escrow::where('status', 'locked')->sum('amount');
        $escrowReleased = Escrow::where('status', 'released')->sum('amount');
        $walletTotal = StartupProfile::sum('wallet_balance') + StudentProfile::sum('wallet_balance');

        // ── 3. REVENUE ANALYTICS & FORECASTING ──────────────────────────────
        $taskCommissionRevenue = Transaction::where('user_type', 'platform')->where('type', 'credit')->where('status', 'active')->where('reference_id', 'like', 'task_%')->sum('amount');
        $hiringSuccessRevenue = Transaction::where('user_type', 'platform')->where('type', 'credit')->where('status', 'active')->where('reference_id', 'like', 'offer_%')->sum('amount');
        $subscriptionRevenue = Transaction::where('user_type', 'platform')->where('type', 'credit')->where('status', 'active')->where('reference_id', 'like', 'sub_%')->sum('amount');

        // Forecasting
        $pipelineCommissions = Task::where('status', 'posted')->sum('stipend') * 0.10;
        $pipelineOffers = HiringOffer::where('status', 'pending')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->sum('reserved_fee');
        $expectedRevenueThisMonth = $revenueThisMonth + $pipelineCommissions + $pipelineOffers;

        // Next month: Actual revenue this month * current growth rate trend (or fallback if 0)
        $growthRate = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) : 0.05;
        $expectedRevenueNextMonth = $revenueThisMonth * (1 + max(-0.5, min(1.0, $growthRate)));

        // Revenue Last 12 Months
        $revenueChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $monthTasks = Transaction::where('user_type', 'platform')
                ->where('type', 'credit')
                ->where('status', 'active')
                ->where('reference_id', 'like', 'task_%')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthHires = Transaction::where('user_type', 'platform')
                ->where('type', 'credit')
                ->where('status', 'active')
                ->where('reference_id', 'like', 'offer_%')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthSubs = Transaction::where('user_type', 'platform')
                ->where('type', 'credit')
                ->where('status', 'active')
                ->where('reference_id', 'like', 'sub_%')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $revenueChartData[] = [
                'month'  => $monthStart->format('M Y'),
                'tasks'  => round($monthTasks, 2),
                'hiring' => round($monthHires, 2),
                'subscriptions' => round($monthSubs, 2),
                'total'  => round($monthTasks + $monthHires + $monthSubs, 2),
            ];
        }

        // Top Revenue Generating Startups
        $allTxs = Transaction::where('user_type', 'platform')
            ->where('type', 'credit')
            ->where('status', 'active')
            ->get();

        $startupRevenueMap = [];
        $taskIds = [];
        $offerIds = [];

        foreach ($allTxs as $tx) {
            if (str_starts_with($tx->reference_id, 'task_')) {
                $taskIds[] = (int) str_replace('task_', '', $tx->reference_id);
            } elseif (str_starts_with($tx->reference_id, 'offer_')) {
                $offerIds[] = (int) str_replace('offer_', '', $tx->reference_id);
            }
        }

        $tasks = Task::whereIn('id', array_unique($taskIds))->get()->keyBy('id');
        $offers = HiringOffer::whereIn('id', array_unique($offerIds))->get()->keyBy('id');
        $startups = StartupProfile::with('user')->get()->keyBy('id');

        foreach ($allTxs as $tx) {
            $startupId = null;
            if (str_starts_with($tx->reference_id, 'task_')) {
                $tid = (int) str_replace('task_', '', $tx->reference_id);
                if (isset($tasks[$tid])) {
                    $startupId = $tasks[$tid]->startup_profile_id;
                }
            } elseif (str_starts_with($tx->reference_id, 'offer_')) {
                $oid = (int) str_replace('offer_', '', $tx->reference_id);
                if (isset($offers[$oid])) {
                    $startupId = $offers[$oid]->startup_profile_id;
                }
            }

            if ($startupId) {
                if (!isset($startupRevenueMap[$startupId])) {
                    $startupRevenueMap[$startupId] = 0;
                }
                $startupRevenueMap[$startupId] += $tx->amount;
            }
        }

        $topStartupsRevenue = [];
        foreach ($startupRevenueMap as $sid => $rev) {
            if (isset($startups[$sid])) {
                $st = $startups[$sid];
                $topStartupsRevenue[] = [
                    'startup_id'   => $sid,
                    'name'         => $st->company_name ?? $st->user->name ?? 'Startup #' . $sid,
                    'revenue'      => round($rev, 2),
                    'tasks_posted' => Task::where('startup_profile_id', $sid)->count(),
                    'hires'        => HiringOffer::where('startup_profile_id', $sid)->whereIn('status', ['joined', 'completed'])->count(),
                ];
            }
        }
        usort($topStartupsRevenue, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
        $topStartupsRevenue = array_slice($topStartupsRevenue, 0, 5);

        // ── 4. ESCROW HEALTH & RISK SCORE ───────────────────────────────────
        $escrowLockedCount = Escrow::where('status', 'locked')->count();
        $escrowReleasedCount = Escrow::where('status', 'released')->count();
        $escrowRefundedCount = Escrow::where('status', 'refunded')->count();
        $escrowTotalCount = $escrowLockedCount + $escrowReleasedCount + $escrowRefundedCount;

        $escrowValLocked = Escrow::where('status', 'locked')->sum('amount');
        $escrowValReleased = Escrow::where('status', 'released')->sum('amount');
        $escrowValRefunded = Escrow::where('status', 'refunded')->sum('amount');

        // Escrow Integrity Audits (Deduction Checks)
        $escrowRiskScore = 100;
        $escrowAlerts = [];

        // 1. Tasks completed but escrow is still locked
        $completedWithLockedEscrow = Task::where('status', 'completed')
            ->whereHas('escrow', fn($q) => $q->where('status', 'locked'))
            ->with(['escrow', 'startup'])
            ->get();
        if ($completedWithLockedEscrow->isNotEmpty()) {
            $deduct = $completedWithLockedEscrow->count() * 10;
            $escrowRiskScore -= $deduct;
            foreach ($completedWithLockedEscrow as $t) {
                $escrowAlerts[] = [
                    'severity' => 'critical',
                    'message'  => "Task #{$t->id} ('{$t->title}') is completed, but escrow of ₹" . number_format($t->escrow->amount ?? 0) . " remains locked.",
                ];
            }
        }

        // 2. Submissions accepted but task escrow is locked
        $submissionsLocked = Submission::where('status', 'accepted')
            ->whereHas('application.task.escrow', fn($q) => $q->where('status', 'locked'))
            ->with(['application.task.escrow'])
            ->get();
        if ($submissionsLocked->isNotEmpty()) {
            $deduct = $submissionsLocked->count() * 10;
            $escrowRiskScore -= $deduct;
            foreach ($submissionsLocked as $s) {
                $t = $s->application->task;
                $escrowAlerts[] = [
                    'severity' => 'critical',
                    'message'  => "Submission #{$s->id} accepted, but task #{$t->id} ('{$t->title}') escrow remains locked.",
                ];
            }
        }

        // 3. Task stipend column !== Escrow amount column
        $mismatchedEscrows = Task::whereHas('escrow', fn($q) => $q->whereColumn('tasks.stipend', '!=', 'escrows.amount'))
            ->with('escrow')
            ->get();
        if ($mismatchedEscrows->isNotEmpty()) {
            $deduct = $mismatchedEscrows->count() * 15;
            $escrowRiskScore -= $deduct;
            foreach ($mismatchedEscrows as $t) {
                $escrowAlerts[] = [
                    'severity' => 'critical',
                    'message'  => "Stipend mismatch on Task #{$t->id}: Task stipend is ₹" . number_format($t->stipend) . ", but escrow ledger reports ₹" . number_format($t->escrow->amount) . ".",
                ];
            }
        }

        // 4. Orphan escrows (no task reference)
        $orphanEscrows = Escrow::whereNotExists(function($q) {
            $q->select(DB::raw(1))->from('tasks')->whereColumn('tasks.id', 'escrows.task_id');
        })->get();
        if ($orphanEscrows->isNotEmpty()) {
            $deduct = $orphanEscrows->count() * 15;
            $escrowRiskScore -= $deduct;
            foreach ($orphanEscrows as $e) {
                $escrowAlerts[] = [
                    'severity' => 'critical',
                    'message'  => "Orphan escrow record detected (ID #{$e->id}) with no corresponding task.",
                ];
            }
        }

        $escrowRiskScore = max(0, min(100, $escrowRiskScore));

        // ── 5. HIRING QUALITY ANALYTICS ─────────────────────────────────────
        $avgInternshipDuration = HiringOffer::where('offer_type', 'internship')
            ->where('status', 'completed')
            ->get()
            ->avg(function($offer) {
                if ($offer->end_date && $offer->start_date) {
                    $start = Carbon::parse($offer->start_date);
                    $end = Carbon::parse($offer->end_date);
                    return max(1, $start->diffInMonths($end));
                }
                return 3;
            }) ?? 3.0;

        $completedInternships = HiringOffer::where('offer_type', 'internship')->where('status', 'completed')->count();
        $cancelledHires = HiringOffer::whereIn('status', ['cancelled_by_student', 'withdrawn_by_startup', 'cancelled'])->count();
        $withdrawnOffers = HiringOffer::where('status', 'withdrawn')->count();

        // ── 6. HIRING FUNNEL & 7. CONVERSION LOSS ANALYTICS ─────────────────
        $funnelAppCount = Application::count();
        $funnelTaskStarted = Application::whereIn('status', ['approved', 'completed', 'internship_offered', 'internship_accepted', 'hired'])->count();
        $funnelTaskCompleted = Application::whereHas('submission', fn($q) => $q->where('status', 'accepted'))->count();
        $funnelInterviewed = Interview::where('status', 'completed')->distinct('student_profile_id')->count();
        $funnelOfferSent = HiringOffer::count();
        $funnelOfferAccepted = HiringOffer::whereIn('status', ['pending_joining', 'joined', 'completed'])->count();
        $funnelJoined = HiringOffer::whereIn('status', ['joined', 'completed'])->count();
        $funnelCompleted = HiringOffer::where('status', 'completed')->count();

        // Conversion Loss percentages
        $dropAppToTask = $funnelAppCount > 0 ? round((($funnelAppCount - $funnelTaskStarted) / $funnelAppCount) * 100, 1) : 0;
        $dropTaskToComplete = $funnelTaskStarted > 0 ? round((($funnelTaskStarted - $funnelTaskCompleted) / $funnelTaskStarted) * 100, 1) : 0;
        $dropCompleteToInterview = $funnelTaskCompleted > 0 ? round((($funnelTaskCompleted - $funnelInterviewed) / $funnelTaskCompleted) * 100, 1) : 0;
        $dropInterviewToOffer = $funnelInterviewed > 0 ? round((($funnelInterviewed - $funnelOfferSent) / $funnelInterviewed) * 100, 1) : 0;
        $dropOfferToAccept = $funnelOfferSent > 0 ? round((($funnelOfferSent - $funnelOfferAccepted) / $funnelOfferSent) * 100, 1) : 0;
        $dropAcceptToJoin = $funnelOfferAccepted > 0 ? round((($funnelOfferAccepted - $funnelJoined) / $funnelOfferAccepted) * 100, 1) : 0;
        $dropJoinToComplete = $funnelJoined > 0 ? round((($funnelJoined - $funnelCompleted) / $funnelJoined) * 100, 1) : 0;

        // ── 8. STUDENT ANALYTICS ─────────────────────────────────────────────
        $avgStudentIprs = ReputationScore::avg('overall_score') ?? 50.00;
        $studentsAbove80Iprs = ReputationScore::where('overall_score', '>=', 80)->count();
        $studentsAbove90Iprs = ReputationScore::where('overall_score', '>=', 90)->count();

        $topStudentsByIprs = StudentProfile::with(['user', 'reputationScore'])
            ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
            ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
            ->select('student_profiles.*')
            ->take(10)
            ->get();

        $topVerifiedSkills = SkillVerification::where('verification_method', 'task_completion')
            ->whereNotNull('startup_profile_id')
            ->select('skill_id', DB::raw('COUNT(DISTINCT student_profile_id) as student_count'))
            ->groupBy('skill_id')
            ->with('skill')
            ->orderBy('student_count', 'desc')
            ->take(10)
            ->get();

        $topColleges = StudentProfile::whereNotNull('college_name')
            ->where('college_name', '!=', '')
            ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
            ->select('college_name', 
                DB::raw('COUNT(student_profiles.id) as student_count'), 
                DB::raw('SUM(CASE WHEN is_verified THEN 1 ELSE 0 END) as verified_count'),
                DB::raw('COALESCE(AVG(reputation_scores.overall_score), 50.00) as avg_iprs')
            )
            ->groupBy('college_name')
            ->orderBy('student_count', 'desc')
            ->take(10)
            ->get();

        // ── 9. STARTUP ANALYTICS ─────────────────────────────────────────────
        $avgStartupTrust = StartupTrustScore::avg('overall_score') ?? 50.00;
        $startupsAbove80Trust = StartupTrustScore::where('overall_score', '>=', 80)->count();

        $topHiringStartups = StartupProfile::with('user')
            ->select('startup_profiles.*')
            ->withCount([
                'tasks as tasks_posted_count',
                'hiringOffers as hires_count' => function($q) {
                    $q->whereIn('status', ['joined', 'completed']);
                }
            ])
            ->orderBy('hires_count', 'desc')
            ->take(10)
            ->get();

        $topReviewedStartups = StartupProfile::withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->orderBy('avg_rating', 'desc')
            ->orderBy('review_count', 'desc')
            ->take(10)
            ->get();

        // ── 10. DOMAIN ANALYTICS ─────────────────────────────────────────────
        $domains = ['Software Development', 'UI/UX Design', 'Digital Marketing', 'Data & AI', 'Content & Business'];
        $domainAnalytics = [];
        $topDomainName = 'Software Development';
        $topDomainScore = 0;

        foreach ($domains as $domain) {
            $domStudents = StudentProfile::where('primary_domain', $domain)->count();
            $domTasks = Task::where('domain', $domain)->count();
            $domApps = Application::whereHas('task', fn($q) => $q->where('domain', $domain))->count();
            $domInterviews = Interview::whereHas('task', fn($q) => $q->where('domain', $domain))->count();
            $domOffers = HiringOffer::whereHas('sourceTask', fn($q) => $q->where('domain', $domain))->count();
            $domHires = HiringOffer::whereIn('status', ['joined', 'completed'])->where(function($q) use ($domain) {
                $q->whereHas('sourceTask', fn($sq) => $sq->where('domain', $domain))
                  ->orWhere(function($oq) use ($domain) {
                      $oq->whereNull('source_task_id')->whereHas('student', fn($sq) => $sq->where('primary_domain', $domain));
                  });
            })->count();

            // Calculate attributed platform revenue
            $domRevenue = 0.00;
            foreach ($allTxs as $tx) {
                if (str_starts_with($tx->reference_id, 'task_')) {
                    $tid = (int) str_replace('task_', '', $tx->reference_id);
                    if (isset($tasks[$tid]) && $tasks[$tid]->domain === $domain) {
                        $domRevenue += $tx->amount;
                    }
                } elseif (str_starts_with($tx->reference_id, 'offer_')) {
                    $oid = (int) str_replace('offer_', '', $tx->reference_id);
                    if (isset($offers[$oid])) {
                        $offer = $offers[$oid];
                        $targetDom = null;
                        if ($offer->source_task_id && isset($tasks[$offer->source_task_id])) {
                            $targetDom = $tasks[$offer->source_task_id]->domain;
                        } elseif ($offer->student) {
                            $targetDom = $offer->student->primary_domain;
                        }
                        if ($targetDom === $domain) {
                            $domRevenue += $tx->amount;
                        }
                    }
                }
            }

            $domainAnalytics[$domain] = [
                'students'     => $domStudents,
                'tasks'        => $domTasks,
                'applications' => $domApps,
                'interviews'   => $domInterviews,
                'offers'       => $domOffers,
                'hires'        => $domHires,
                'revenue'      => round($domRevenue, 2),
            ];

            // Core priority score
            $scoreVal = $domHires + ($domRevenue / 100);
            if ($scoreVal > $topDomainScore) {
                $topDomainScore = $scoreVal;
                $topDomainName = $domain;
            }
        }

        // ── 11. SKILL DEMAND & REVENUE ───────────────────────────────────────
        $skillDemandList = Skill::withCount('tasks as task_count')->orderBy('task_count', 'desc')->take(10)->get();
        $skillDemandGap = [];

        foreach ($skillDemandList as $skill) {
            $supply = SkillVerification::where('skill_id', $skill->id)
                ->where('verification_method', 'task_completion')
                ->whereNotNull('startup_profile_id')
                ->distinct('student_profile_id')
                ->count('student_profile_id');

            // Skill Revenue allocation
            $skillRev = 0.00;
            foreach ($allTxs as $tx) {
                $skillsCount = 0;
                $hasSkill = false;
                
                if (str_starts_with($tx->reference_id, 'task_')) {
                    $tid = (int) str_replace('task_', '', $tx->reference_id);
                    if (isset($tasks[$tid])) {
                        $task = $tasks[$tid];
                        $reqSkills = is_array($task->required_skills) 
                            ? $task->required_skills 
                            : json_decode($task->required_skills ?? '[]', true);
                        if (is_array($reqSkills)) {
                            $skillsCount = count($reqSkills);
                            $hasSkill = in_array($skill->name, $reqSkills);
                        }
                    }
                } elseif (str_starts_with($tx->reference_id, 'offer_')) {
                    $oid = (int) str_replace('offer_', '', $tx->reference_id);
                    if (isset($offers[$oid])) {
                        $offer = $offers[$oid];
                        if ($offer->source_task_id && isset($tasks[$offer->source_task_id])) {
                            $task = $tasks[$offer->source_task_id];
                            $reqSkills = is_array($task->required_skills) 
                                ? $task->required_skills 
                                : json_decode($task->required_skills ?? '[]', true);
                            if (is_array($reqSkills)) {
                                $skillsCount = count($reqSkills);
                                $hasSkill = in_array($skill->name, $reqSkills);
                            }
                        } elseif ($offer->student) {
                            $studSkills = $offer->student->skills->pluck('name')->toArray();
                            $skillsCount = count($studSkills);
                            $hasSkill = in_array($skill->name, $studSkills);
                        }
                    }
                }

                if ($hasSkill && $skillsCount > 0) {
                    $skillRev += ($tx->amount / $skillsCount);
                }
            }

            $skillDemandGap[] = [
                'skill_name' => $skill->name,
                'demand'     => $skill->task_count,
                'supply'     => $supply,
                'gap'        => max(0, $skill->task_count - $supply),
                'revenue'    => round($skillRev, 2),
            ];
        }

        // Sort gap descending
        usort($skillDemandGap, fn($a, $b) => $b['gap'] <=> $a['gap']);

        // ── 12. VERIFICATION ANALYTICS ───────────────────────────────────────
        $studentVerifyPending = StudentProfile::where('id_card_verification_status', 'manual_review')->count();
        $studentVerifyApproved = StudentProfile::whereIn('id_card_verification_status', ['ai_approved', 'admin_approved'])->count();
        $studentVerifyRejected = StudentProfile::where('id_card_verification_status', 'ai_rejected')->count();

        $startupVerifyPending = StartupProfile::where('verification_status', 'pending')
            ->whereNotNull('verification_submitted_at')
            ->count();
        $startupVerifyApproved = StartupProfile::where('verification_status', 'approved')->count();
        $startupVerifyRejected = StartupProfile::where('verification_status', 'rejected')->count();

        $aiVerifyApproved = StudentProfile::where('id_card_verification_status', 'ai_approved')->count() + StartupProfile::where('ai_verification_status', 'ai_approved')->count();
        $aiVerifyRejected = StudentProfile::where('id_card_verification_status', 'ai_rejected')->count() + StartupProfile::where('ai_verification_status', 'ai_rejected')->count();
        $aiVerifyEscalated = StudentProfile::where('id_card_verification_status', 'manual_review')->count() + StartupProfile::where('verification_status', 'pending')->where('ai_verification_status', 'manual_review')->count();

        // ── 13. WALLET DIAGNOSTICS & BALANCES ───────────────────────────────
        $walletMismatches = [];
        
        // Audit top 30 startups
        $startupProfiles = StartupProfile::with('user')->take(30)->get();
        foreach ($startupProfiles as $sp) {
            $ledgerSum = Transaction::where('user_type', 'startup')
                ->where('user_id', $sp->id)
                ->where('status', 'active')
                ->sum(DB::raw("CASE WHEN type = 'credit' THEN amount ELSE -amount END"));
            
            $diff = abs(floatval($sp->wallet_balance) - floatval($ledgerSum));
            if ($diff > 0.05) {
                $walletMismatches[] = [
                    'type'     => 'Startup',
                    'name'     => $sp->company_name ?? $sp->user->name ?? 'Startup #' . $sp->id,
                    'balance'  => floatval($sp->wallet_balance),
                    'ledger'   => floatval($ledgerSum),
                    'anomaly'  => $diff,
                ];
            }
        }

        // Audit top 30 students
        $studentProfiles = StudentProfile::with('user')->take(30)->get();
        foreach ($studentProfiles as $sp) {
            $ledgerSum = Transaction::where('user_type', 'student')
                ->where('user_id', $sp->id)
                ->where('status', 'active')
                ->sum(DB::raw("CASE WHEN type = 'credit' THEN amount ELSE -amount END"));
            
            $diff = abs(floatval($sp->wallet_balance) - floatval($ledgerSum));
            if ($diff > 0.05) {
                $walletMismatches[] = [
                    'type'     => 'Student',
                    'name'     => $sp->user->name ?? 'Student #' . $sp->id,
                    'balance'  => floatval($sp->wallet_balance),
                    'ledger'   => floatval($ledgerSum),
                    'anomaly'  => $diff,
                ];
            }
        }

        // Largest Wallet Holders (Startups)
        $largestWallets = StartupProfile::with('user')
            ->orderBy('wallet_balance', 'desc')
            ->take(5)
            ->get()
            ->map(fn($sp) => [
                'name'    => $sp->company_name ?? $sp->user->name ?? 'Startup #' . $sp->id,
                'balance' => floatval($sp->wallet_balance),
            ])
            ->toArray();

        // Largest Payout Recipients (Students)
        $largestPayouts = StudentProfile::with('user')
            ->orderBy('wallet_balance', 'desc')
            ->take(5)
            ->get()
            ->map(fn($sp) => [
                'name'    => $sp->user->name ?? 'Student #' . $sp->id,
                'balance' => floatval($sp->wallet_balance),
            ])
            ->toArray();

        // ── 14. ADMIN ALERTS CENTER ─────────────────────────────────────────
        $allAlerts = [];

        // Critical alerts from escrow & wallet mismatches
        foreach ($escrowAlerts as $alert) {
            $allAlerts[] = [
                'severity' => 'critical',
                'category' => 'Escrow Anomaly',
                'message'  => $alert['message'],
            ];
        }

        foreach ($walletMismatches as $wm) {
            $allAlerts[] = [
                'severity' => 'critical',
                'category' => 'Wallet Mismatch',
                'message'  => "Wallet mismatch detected for {$wm['type']} '{$wm['name']}': wallet balance is ₹" . number_format($wm['balance'], 2) . ", but transaction ledger balances to ₹" . number_format($wm['ledger'], 2) . " (Anomaly: ₹" . number_format($wm['anomaly'], 2) . ").",
            ];
        }

        // High alerts from verification queue backlogs
        $verificationBacklog = $studentVerifyPending + $startupVerifyPending;
        if ($verificationBacklog > 5) {
            $allAlerts[] = [
                'severity' => 'high',
                'category' => 'Verification Backlog',
                'message'  => "High verification queue backlog: {$verificationBacklog} pending verification reviews ({$studentVerifyPending} students, {$startupVerifyPending} startups) need manual action.",
            ];
        }

        // Medium alerts from low trust score startups
        $lowTrustStartups = StartupTrustScore::where('overall_score', '<', 60)->count();
        if ($lowTrustStartups > 0) {
            $allAlerts[] = [
                'severity' => 'medium',
                'category' => 'Startup Quality',
                'message'  => "{$lowTrustStartups} registered startups currently have a Trust Score below 60. Monitor candidate complaints.",
            ];
        }

        // Low alerts from pending top-ups
        $pendingTopups = WalletTopupRequest::where('status', 'pending')->count();
        if ($pendingTopups > 0) {
            $allAlerts[] = [
                'severity' => 'low',
                'category' => 'Topup Request',
                'message'  => "{$pendingTopups} startup wallet top-up request(s) are pending review and approval.",
            ];
        }

        return [
            'health_score'               => $healthScore,
            'health_score_change'        => $healthScoreChange,
            'kpis' => [
                'students_total'             => $studentsTotal,
                'students_verified'          => $studentsVerified,
                'students_verify_rate'       => round($studentVerifyRate, 1),
                'students_new_this_month'    => $studentsNewThisMonth,
                'startups_total'             => $startupsTotal,
                'startups_verified'          => $startupsVerified,
                'startups_verify_rate'       => round($startupVerifyRate, 1),
                'startups_new_this_month'    => $startupsNewThisMonth,
                'active_tasks'               => $activeTasks,
                'completed_tasks'            => $completedTasks,
                'active_applications'        => $activeApplications,
                'active_interviews'          => $activeInterviews,
                'internship_placements'      => $internshipPlacements,
                'job_placements'             => $jobPlacements,
                'total_hires'                => $totalHires,
                'total_revenue'              => round($totalRevenue, 2),
                'revenue_this_month'         => round($revenueThisMonth, 2),
                'revenue_last_month'         => round($revenueLastMonth, 2),
                'escrow_locked'              => round($escrowLocked, 2),
                'escrow_released'            => round($escrowReleased, 2),
                'wallet_total'               => round($walletTotal, 2),
            ],
            'revenue' => [
                'task_commission'            => round($taskCommissionRevenue, 2),
                'hiring_success'             => round($hiringSuccessRevenue, 2),
                'subscription'               => round($subscriptionRevenue, 2),
                'forecast_this_month'        => round($expectedRevenueThisMonth, 2),
                'forecast_next_month'        => round($expectedRevenueNextMonth, 2),
                'growth_rate'                => round($growthRate * 100, 1),
                'chart_12m'                  => $revenueChartData,
                'top_startups'               => $topStartupsRevenue,
            ],
            'escrow' => [
                'total_count'                => $escrowTotalCount,
                'locked_count'               => $escrowLockedCount,
                'released_count'             => $escrowReleasedCount,
                'refunded_count'             => $escrowRefundedCount,
                'success_rate'               => round($escrowSuccessRate, 1),
                'val_locked'                 => round($escrowValLocked, 2),
                'val_released'               => round($escrowValReleased, 2),
                'val_refunded'               => round($escrowValRefunded, 2),
                'risk_score'                 => $escrowRiskScore,
            ],
            'hiring_quality' => [
                'avg_duration'               => round($avgInternshipDuration, 1),
                'completed_internships'      => $completedInternships,
                'cancelled_hires'            => $cancelledHires,
                'withdrawn_offers'           => $withdrawnOffers,
            ],
            'funnel' => [
                'applications'               => $funnelAppCount,
                'task_started'               => $funnelTaskStarted,
                'task_completed'             => $funnelTaskCompleted,
                'interviewed'                => $funnelInterviewed,
                'offer_sent'                 => $funnelOfferSent,
                'offer_accepted'             => $funnelOfferAccepted,
                'joined'                     => $funnelJoined,
                'completed'                  => $funnelCompleted,
            ],
            'funnel_drops' => [
                'app_to_task'                => $dropAppToTask,
                'task_to_complete'           => $dropTaskToComplete,
                'complete_to_interview'      => $dropCompleteToInterview,
                'interview_to_offer'         => $dropInterviewToOffer,
                'offer_to_accept'            => $dropOfferToAccept,
                'accept_to_join'             => $dropAcceptToJoin,
                'join_to_complete'           => $dropJoinToComplete,
            ],
            'students' => [
                'avg_iprs'                   => round($avgStudentIprs, 1),
                'above_80'                   => $studentsAbove80Iprs,
                'above_90'                   => $studentsAbove90Iprs,
                'top_list'                   => $topStudentsByIprs,
                'top_skills'                 => $topVerifiedSkills,
                'top_colleges'               => $topColleges,
            ],
            'startups' => [
                'avg_trust'                  => round($avgStartupTrust, 1),
                'above_80'                   => $startupsAbove80Trust,
                'top_list'                   => $topHiringStartups,
                'top_reviewed'               => $topReviewedStartups,
            ],
            'domains' => [
                'list'                       => $domainAnalytics,
                'top_name'                   => $topDomainName,
            ],
            'skills_revenue'                 => $skillDemandGap,
            'verification' => [
                'student_pending'            => $studentVerifyPending,
                'student_approved'           => $studentVerifyApproved,
                'student_rejected'           => $studentVerifyRejected,
                'startup_pending'            => $startupVerifyPending,
                'startup_approved'           => $startupVerifyApproved,
                'startup_rejected'           => $startupVerifyRejected,
                'ai_approved'                => $aiVerifyApproved,
                'ai_rejected'                => $aiVerifyRejected,
                'ai_escalated'               => $aiVerifyEscalated,
            ],
            'wallet' => [
                'mismatches'                 => $walletMismatches,
                'largest_wallets'            => $largestWallets,
                'largest_payouts'            => $largestPayouts,
            ],
            'alerts'                         => $allAlerts,
        ];
    }
}
