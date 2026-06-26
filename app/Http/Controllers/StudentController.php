<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use App\Services\MatchingService;
use App\Services\AIVerificationService;
use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function __construct(
        private StudentRepository $repository,
        private MatchingService $matchingService
    ) {}

    public function dashboard()
    {
        $profile = auth()->user()->studentProfile->load(['skills', 'reputationScore', 'portfolio.items', 'startupReviews']);
        $hiringOffers = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('status', 'pending')
                        ->where(function($exp) {
                            $exp->whereNull('expires_at')
                                ->orWhere('expires_at', '>=', now());
                        });
                })->orWhereIn('status', ['pending_joining', 'joined']);
            })
            ->with('startup')
            ->get();

        $completedTasks = \App\Models\Application::where('student_profile_id', $profile->id)
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })
            ->with(['task.startup', 'submission'])
            ->get();

        $ratings = \App\Models\Rating::where('student_profile_id', $profile->id)
            ->get()
            ->keyBy('task_id');

        // Compute success metrics
        $projectsCompleted = $completedTasks->count();
        $internshipOffersCount = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where('offer_type', 'internship')
            ->count();
        $jobOffersCount = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where('offer_type', 'job')
            ->count();
        
        $totalEarnings = \App\Models\Application::where('student_profile_id', $profile->id)
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })
            ->with('task')
            ->get()
            ->sum(fn($app) => $app->task->stipend ?? 0.00);

        $reviewedTaskIds = $profile->startupReviews->pluck('task_id')->toArray();

        $recommendedTasks = $this->matchingService->getRecommendedTasksForStudent($profile, 6, $profile->id);

        $interviews = \App\Models\Interview::where('student_profile_id', $profile->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->with(['startup', 'task'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Calculate student dashboard widgets
        $rankingService = app(\App\Services\CandidateRankingService::class);
        $bestMatchingDomain = $profile->primary_domain ?? 'Software Development';
        $topSkillCategory = $profile->skills->groupBy('domain')->sortByDesc(fn($g) => $g->count())->keys()->first() ?? $profile->primary_domain ?? 'Software Development';
        
        $portfolioScoreVal = $rankingService->calculatePortfolioStrength($profile);
        $portfolioStrengthLabel = $rankingService->getPortfolioLabel($portfolioScoreVal);

        // Calculate Hiring Readiness Score:
        // 1. IPRS overall (35%)
        $iprsPart = ($profile->reputationScore->overall_score ?? 50.0) * 0.35;

        // 2. Profile Completion (25%)
        $completionScore = 0;
        if (!empty($profile->bio)) $completionScore += 25;
        if ($profile->skills->isNotEmpty()) $completionScore += 25;
        if ($profile->portfolio && $profile->portfolio->items->isNotEmpty()) $completionScore += 25;
        if (!empty($profile->availability)) $completionScore += 25;
        $completionPart = $completionScore * 0.25;

        // 3. Completed Tasks (25%)
        $completedTasksCountForReadiness = \App\Models\Application::where('student_profile_id', $profile->id)
            ->whereHas('submission', fn($q) => $q->where('status', 'accepted'))->count();
        $internshipCountForReadiness = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where('offer_type', 'internship')->whereIn('status', ['pending_joining', 'joined', 'completed'])->count();
        $jobCountForReadiness = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where('offer_type', 'job')->whereIn('status', ['pending_joining', 'joined', 'completed'])->count();
        $verifiedPortfolioCountForReadiness = $profile->portfolio ? $profile->portfolio->items->whereNotNull('verification_badge')->count() : 0;
        
        $totalCompletedForReadiness = $completedTasksCountForReadiness + $internshipCountForReadiness + $jobCountForReadiness + $verifiedPortfolioCountForReadiness;
        if ($totalCompletedForReadiness === 0) {
            $readinessWorkScore = 0;
        } elseif ($totalCompletedForReadiness >= 1 && $totalCompletedForReadiness <= 3) {
            $readinessWorkScore = 40;
        } elseif ($totalCompletedForReadiness >= 4 && $totalCompletedForReadiness <= 7) {
            $readinessWorkScore = 70;
        } elseif ($totalCompletedForReadiness >= 8 && $totalCompletedForReadiness <= 15) {
            $readinessWorkScore = 90;
        } else {
            $readinessWorkScore = 100;
        }
        $completedTasksPart = $readinessWorkScore * 0.25;

        // 4. Verified Profile (15%)
        $verifiedProfilePart = ($profile->is_verified ? 100.0 : 0.0) * 0.15;

        $hiringReadinessScore = round($iprsPart + $completionPart + $completedTasksPart + $verifiedProfilePart);

        return view('student.dashboard', compact(
            'profile', 
            'recommendedTasks', 
            'hiringOffers', 
            'projectsCompleted', 
            'internshipOffersCount', 
            'jobOffersCount', 
            'totalEarnings',
            'reviewedTaskIds',
            'completedTasks',
            'ratings',
            'interviews',
            'bestMatchingDomain',
            'topSkillCategory',
            'portfolioScoreVal',
            'portfolioStrengthLabel',
            'hiringReadinessScore'
        ));
    }

    public function profile()
    {
        $profile = auth()->user()->studentProfile->load('portfolio');
        $skills = Skill::all();
        return view('student.profile', compact('profile', 'skills'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'skills' => 'nullable|array',
            'primary_domain' => 'nullable|string',
            'preferred_role' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'github_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'leetcode_url' => 'nullable|url',
            'degree_name' => 'nullable|string|max:255',
            'cgpa' => 'nullable|numeric|between:0,10',
            'professional_title' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'college_name' => 'nullable|string|max:255',
            'graduation_year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 5),
            ],
        ]);

        \App\Helpers\ContactDetector::validate($validated['bio'] ?? '', 'bio');

        $profile = auth()->user()->studentProfile;

        if ($request->graduation_year && $request->graduation_year < now()->year - 2) {
            return back()->withErrors([
                'graduation_year' => 'Only current students or graduates from the last two years are allowed.'
            ]);
        }

        if ($profile->is_verified && $profile->graduation_year !== null && $request->has('graduation_year') && (int)$request->graduation_year !== (int)$profile->graduation_year) {
            return back()->withErrors([
                'graduation_year' => 'Verified graduation year cannot be modified.'
            ]);
        }

        // Update user name
        auth()->user()->update(['name' => $validated['name']]);

        // Update profile
        $profile->update([
            'bio' => $validated['bio'],
            'primary_domain' => $validated['primary_domain'] ?? null,
            'preferred_role' => $validated['preferred_role'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'github_url' => $validated['github_url'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'leetcode_url' => $validated['leetcode_url'] ?? null,
            'degree_name' => $validated['degree_name'] ?? null,
            'cgpa' => $validated['cgpa'] ?? null,
            'professional_title' => $validated['professional_title'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? null,
            'college_name' => $validated['college_name'] ?? null,
            'graduation_year' => $validated['graduation_year'] ?? null,
        ]);
        
        if (isset($validated['skills'])) {
            $profile->skills()->sync($validated['skills']);
        }

        // Handle portfolio visibility toggle
        if ($profile->portfolio) {
            $profile->portfolio->update([
                'is_public' => $request->has('is_public')
            ]);
        }

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully');
    }

    public function publicProfile($id)
    {
        $profile = $this->repository->find($id)->load(['reputationScore', 'portfolio.items', 'skillVerifications.skill']);
        
        $startupVerificationCounts = \App\Services\SkillVerificationService::getStartupVerificationCounts($profile->id);
        $verifiedSkills = $profile->skillVerifications->pluck('skill_id')->unique()->toArray();
        $skillScores = $profile->skillVerifications
            ->groupBy('skill_id')
            ->map(function ($verifications) {
                return $verifications->max('score');
            })
            ->toArray();

        return view('student.public-profile', compact('profile', 'startupVerificationCounts', 'verifiedSkills', 'skillScores'));
    }

    public function verification()
    {
        $profile = auth()->user()->studentProfile;
        return view('student.verification', compact('profile'));
    }

    public function sendVerification(Request $request)
    {
        $validated = $request->validate([
            'college_email' => 'required|email|ends_with:.edu,.ac.in,.edu.in',
            'college_name' => 'required|string|max:255',
        ]);

        $profile = auth()->user()->studentProfile;

        $sendKey = 'verify-send:' . $profile->id;
        if (RateLimiter::tooManyAttempts($sendKey, 3)) {
            return back()->with('error', 'Too many verification emails sent. Please try again in an hour.');
        }

        $duplicateEmail = \App\Models\StudentProfile::where('college_email', $validated['college_email'])
            ->where('id', '!=', $profile->id)
            ->where('is_verified', true)
            ->exists();
        if ($duplicateEmail) {
            return back()->withInput()->with('error', 'This college email is already verified on another account.');
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addHours(24);

        $profile->update([
            'college_email' => $validated['college_email'],
            'college_name' => $validated['college_name'],
            'verification_token' => $code,
            'verification_token_expires_at' => $expiresAt,
        ]);

        RateLimiter::hit($sendKey, 3600);

        try {
            \Mail::send('emails.student-verification-code', ['code' => $code, 'profile' => $profile], function($message) use ($validated) {
                $message->to($validated['college_email']);
                $message->subject('Your Verification Code - InternGrowth');
            });
            
            return redirect()->route('student.verification.code')->with('success', 'Verification code sent to ' . $validated['college_email']);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email. Please check your email address.');
        }
    }

    public function showVerificationCode()
    {
        $profile = auth()->user()->studentProfile;
        
        if ($profile->is_verified) {
            return redirect()->route('student.dashboard')->with('success', 'You are already verified!');
        }
        
        if (!$profile->verification_token) {
            return redirect()->route('student.verification')->with('error', 'Please request a verification code first.');
        }
        
        return view('student.verification-code', compact('profile'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $profile = auth()->user()->studentProfile;

        $verifyKey = 'verify-code:' . $profile->id;
        if (RateLimiter::tooManyAttempts($verifyKey, 10)) {
            return back()->withInput()->with('error', 'Too many failed attempts. Please request a new verification code.');
        }

        if (!$profile->verification_token || !$profile->verification_token_expires_at) {
            return redirect()->route('student.verification')->with('error', 'Please request a verification code first.');
        }

        if ($profile->verification_token_expires_at->isPast()) {
            $profile->update(['verification_token' => null, 'verification_token_expires_at' => null]);
            return back()->withInput()->with('error', 'Verification code has expired. Please request a new one.');
        }

        $inputCode = preg_replace('/[^0-9]/', '', $request->code);
        $storedCode = preg_replace('/[^0-9]/', '', $profile->verification_token);

        if ($storedCode === $inputCode) {
            $profile->update([
                'is_verified' => true,
                'email_verified_at' => now(),
                'verification_token' => null,
                'verification_token_expires_at' => null,
                'verification_method' => 'college_email',
            ]);

            RateLimiter::clear($verifyKey);

            return redirect()->route('student.dashboard')->with('success', 'College email verified successfully! You now have access to all tasks.');
        }

        RateLimiter::hit($verifyKey, 900);

        return back()->withInput()->with('error', 'Invalid verification code. Please try again.');
    }

    public function verifyEmail($token)
    {
        return redirect()->route('login')
            ->with('error', 'Email verification links are no longer supported. Please log in and verify using the 6-digit code sent to your college email.');
    }

    public function analytics()
    {
        $profile = auth()->user()->studentProfile->load([
            'skills',
            'ratings'
        ]);

        // Get all applications with their tasks (and skills, startup) and submissions
        $applications = \App\Models\Application::where('student_profile_id', $profile->id)
            ->with(['task.startup', 'task.skills', 'submission'])
            ->get();

        // Key loaded ratings by task_id to avoid N+1 query
        $ratings = $profile->ratings->keyBy('task_id');
        foreach($applications as $application) {
            $application->rating = $ratings->get($application->task_id);
        }

        // Calculate analytics
        // Count completed tasks based on accepted submissions
        $completedTasks = $applications->filter(function($app) {
            return $app->submission && $app->submission->status === 'accepted';
        });
        $completedTasksCount = $completedTasks->count();

        $pendingTasks = $applications->filter(function($app) {
            return $app->status === 'approved' && (!$app->submission || $app->submission->status !== 'accepted');
        });

        $totalStipend = $completedTasks->sum(function($app) {
            return $app->task->stipend ?? 0;
        });

        $avgRating = $profile->ratings->avg('rating') ?? 0;

        // Skills statistics
        $skillsStats = [];
        $maxTaskCount = 0;
        
        foreach($profile->skills as $skill) {
            $taskCount = $completedTasks->filter(function($app) use ($skill) {
                return $app->task->skills->contains($skill->id);
            })->count();
            
            if ($taskCount > $maxTaskCount) {
                $maxTaskCount = $taskCount;
            }
            
            $skillsStats[] = [
                'name' => $skill->name,
                'count' => $taskCount,
                'percentage' => 0 // Will calculate after
            ];
        }

        // Calculate percentages
        foreach($skillsStats as &$stat) {
            $stat['percentage'] = $maxTaskCount > 0 ? ($stat['count'] / $maxTaskCount) * 100 : 0;
        }

        // Sort by count
        usort($skillsStats, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Success rate
        $totalApplications = $applications->where('status', '!=', 'applied')->count();
        $successRate = $totalApplications > 0 ? ($completedTasks->count() / $totalApplications) * 100 : 0;

        // Projects by Domain
        $projectsByDomain = [];
        if ($profile->portfolio) {
            $projectsByDomain = \App\Models\PortfolioItem::where('portfolio_id', $profile->portfolio->id)
                ->select('domain', \DB::raw('count(*) as count'))
                ->whereNotNull('domain')
                ->groupBy('domain')
                ->pluck('count', 'domain')
                ->toArray();
        }

        // Reputation by Domain
        $domainReputations = $profile->reputationScore?->domain_scores ?? [];
        foreach (array_keys(\App\Models\StudentProfile::$domains) as $domain) {
            if (!isset($domainReputations[$domain])) {
                $domainReputations[$domain] = 50.00;
            }
        }

        // Internships by Domain
        $internshipsByDomain = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where('offer_type', 'internship')
            ->whereIn('status', ['accepted', 'joined', 'completed'])
            ->select('domain', \DB::raw('count(*) as count'))
            ->whereNotNull('domain')
            ->groupBy('domain')
            ->pluck('count', 'domain')
            ->toArray();

        $analytics = [
            'completed_tasks' => $completedTasksCount, // Use certificate count instead
            'pending_tasks' => $pendingTasks->count(),
            'total_stipend' => $totalStipend,
            'avg_rating' => $avgRating,
            'skills_stats' => array_slice($skillsStats, 0, 5), // Top 5 skills
            'success_rate' => round($successRate, 1),
            'active_applications' => $applications->whereIn('status', ['applied', 'approved'])->count(),
            'completed_tasks_list' => $completedTasks->sortByDesc('submission.updated_at')->take(10),
            'projects_by_domain' => $projectsByDomain,
            'reputation_by_domain' => $domainReputations,
            'internships_by_domain' => $internshipsByDomain
        ];

        return view('student.analytics', compact('profile', 'analytics'));
    }

    public function downloadCV(Request $request)
    {
        $profile = auth()->user()->studentProfile->load([
            'skills',
            'ratings',
            'reputationScore',
            'portfolio.items'
        ]);

        $resumeService = app(\App\Services\ResumeBuilderService::class);

        // Generate data structures dynamically
        $experiences = $resumeService->groupExperiencesByStartup($profile, $request->has('hide_low_rated'));
        $summary = $resumeService->generateProfessionalSummary($profile);
        $achievements = $resumeService->calculateAchievements($profile);
        $skillsCategorized = $resumeService->buildSkillSections($profile);
        $professionalTitle = $profile->professional_title ?: $resumeService->getCleanRole($profile);

        return view('student.cv-download', compact(
            'profile',
            'experiences',
            'summary',
            'achievements',
            'skillsCategorized',
            'professionalTitle'
        ));
    }

    public function updateResumeSettings(Request $request)
    {
        $validated = $request->validate([
            'resume_theme' => 'required|string|in:ats,startup,verified,developer',
            'show_iprs' => 'boolean',
            'show_stipends' => 'boolean',
            'show_ratings' => 'boolean',
            'show_certificates' => 'boolean',
            'show_social_links' => 'boolean',
            'show_profile_photo' => 'boolean',
        ]);

        $profile = auth()->user()->studentProfile;
        
        $profile->update([
            'resume_theme' => $validated['resume_theme'],
            'show_iprs' => $request->has('show_iprs'),
            'show_stipends' => $request->has('show_stipends'),
            'show_ratings' => $request->has('show_ratings'),
            'show_certificates' => $request->has('show_certificates'),
            'show_social_links' => $request->has('show_social_links'),
            'show_profile_photo' => $request->has('show_profile_photo'),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Resume settings updated successfully']);
        }

        return back()->with('success', 'Resume settings updated successfully');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // College ID Card AI Verification
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Show the College ID upload/verification page.
     */
    public function showIdVerification()
    {
        $profile = auth()->user()->studentProfile;
        if (!$profile) {
            $profile = auth()->user()->studentProfile()->create([
                'bio' => null,
                'portfolio_links' => [],
                'reliability_score' => 0,
                'is_verified' => false,
            ]);
        }

        // If already verified by any method, redirect to dashboard
        if ($profile->is_verified) {
            return redirect()->route('student.dashboard')
                ->with('success', 'You are already verified! You have full access to all tasks.');
        }

        return view('student.id-verification', compact('profile'));
    }

    /**
     * Handle the College ID card upload and trigger AI verification.
     */
    public function submitIdVerification(Request $request)
    {
        $currentYear = (int) date('Y');
        $minAllowedYear = $currentYear - 2;

        $request->validate([
            'id_card_image'   => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'graduation_year' => 'required|integer|between:' . ($currentYear - 5) . ',' . ($currentYear + 5),
        ], [
            'id_card_image.required' => 'Please upload an image of your college ID card.',
            'id_card_image.image'    => 'The file must be an image (JPG, PNG, or WebP).',
            'id_card_image.mimes'    => 'Only JPG, JPEG, PNG, and WebP images are accepted.',
            'id_card_image.max'      => 'Image size must not exceed 10MB.',
            'graduation_year.required' => 'Please select your expected or actual graduation year.',
            'graduation_year.integer'  => 'Graduation year must be a valid number.',
            'graduation_year.between'  => 'Please select a valid graduation year within the allowed range.',
        ]);

        // Enforce the 2-year post-graduation limit
        if ((int) $request->graduation_year < $minAllowedYear) {
            return back()->withInput()->withErrors([
                'graduation_year' => "Verification failed: InternGrowth is restricted to current students and recent graduates. You must have graduated in {$minAllowedYear} or later."
            ]);
        }

        $profile = auth()->user()->studentProfile;
        if (!$profile) {
            $profile = auth()->user()->studentProfile()->create([
                'bio' => null,
                'portfolio_links' => [],
                'reliability_score' => 0,
                'is_verified' => false,
            ]);
        }

        // Prevent re-submission if already AI-approved or admin-approved
        if (in_array($profile->id_card_verification_status, ['ai_approved', 'admin_approved'])) {
            return redirect()->route('student.dashboard')
                ->with('success', 'Your college ID is already verified!');
        }

        if ($profile->id_card_verification_status === 'processing') {
            return back()->with('error', 'Your ID is still being verified. Please wait a moment.');
        }

        // Remove previous upload from private storage
        if ($profile->id_card_path) {
            Storage::disk('local')->delete($profile->id_card_path);
            Storage::disk('public')->delete($profile->id_card_path);
        }

        try {
            // Store on private disk — not web-accessible
            $path = $request->file('id_card_image')->store('id-cards', 'local');

            if (!$path) {
                return back()->withInput()->with('error', 'Failed to save the uploaded image. Please try again.');
            }

            // Mark as processing
            $profile->update([
                'graduation_year'             => $request->graduation_year,
                'id_card_path'                => $path,
                'id_card_verification_status' => 'processing',
                'id_card_submitted_at'        => now(),
                'id_card_ai_result'           => null,
            ]);

            // Run AI verification (passing the graduation year to cross-reference)
            $aiService = app(AIVerificationService::class);
            $result    = $aiService->verifyCollegeId($path, auth()->user()->name, (int) $request->graduation_year);

            // Store the AI result
            $profile->update(['id_card_ai_result' => $result]);

            // Apply decision based on recommendation
            switch ($result['recommendation']) {
                case 'approve':
                    $profile->update([
                        'id_card_verification_status' => 'ai_approved',
                        'id_card_verified_at'         => now(),
                        'is_verified'                 => true,
                        'verification_method'         => 'college_id_ai',
                        // Store college name extracted by AI if not already set
                        'college_name'                => $profile->college_name ?: ($result['college_name'] ?? null),
                    ]);
                    return redirect()->route('student.verify-id')
                        ->with('ai_approved', true)
                        ->with('ai_result', $result);

                case 'manual_review':
                    $profile->update([
                        'id_card_verification_status' => 'manual_review',
                    ]);
                    return redirect()->route('student.verify-id')
                        ->with('manual_review', true)
                        ->with('ai_result', $result);

                default: // reject
                    $profile->update([
                        'id_card_verification_status' => 'ai_rejected',
                    ]);
                    return redirect()->route('student.verify-id')
                        ->with('ai_rejected', true)
                        ->with('ai_result', $result);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ID Verification failed', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // Reset status so the student can retry
            try {
                $profile->update([
                    'id_card_verification_status' => 'none',
                ]);
            } catch (\Throwable $dbEx) {
                // Ignore DB error if profile update fails to avoid secondary fatal errors
            }

            return back()->withInput()->with('error', 'Something went wrong during verification. Please try again. If the problem persists, use the college email verification method instead.');
        }
    }

    /**
     * JSON endpoint to poll verification status.
     */
    public function verificationStatus()
    {
        $profile = auth()->user()->studentProfile;
        if (!$profile) {
            return response()->json([
                'is_verified'                 => false,
                'id_card_verification_status' => 'none',
                'verification_method'         => null,
            ]);
        }

        return response()->json([
            'is_verified'                 => $profile->is_verified,
            'id_card_verification_status' => $profile->id_card_verification_status,
            'verification_method'         => $profile->verification_method,
        ]);
    }

    public function storePortfolioItem(Request $request)
    {
        $validated = $request->validate([
            'project_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'github_url' => 'required_without:demo_url|nullable|url|max:500',
            'demo_url' => 'required_without:github_url|nullable|url|max:500',
            'skills_demonstrated' => 'required|array|min:1',
            'skills_demonstrated.*' => 'exists:skills,id',
        ], [
            'github_url.required_without' => 'Please provide at least a GitHub URL or a Demo URL.',
            'demo_url.required_without' => 'Please provide at least a GitHub URL or a Demo URL.',
            'skills_demonstrated.required' => 'Please select at least one skill demonstrated in this project.',
        ]);

        \App\Helpers\ContactDetector::validate($validated['description'] ?? '', 'description');

        $profile = auth()->user()->studentProfile;
        $portfolio = $profile->portfolio;
        if (!$portfolio) {
            $portfolio = \App\Models\Portfolio::create([
                'student_profile_id' => $profile->id,
                'custom_slug' => 'student-' . strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', auth()->user()->name))) . '-' . rand(1000, 9999),
                'is_public' => true,
            ]);
        }

        // Fetch skill names from database
        $skills = \App\Models\Skill::whereIn('id', $validated['skills_demonstrated'])->pluck('name')->toArray();

        // Create the PortfolioItem
        \App\Models\PortfolioItem::create([
            'portfolio_id' => $portfolio->id,
            'project_title' => $validated['project_title'],
            'auto_summary' => $validated['description'] ?? 'Personal project demonstrating skills.',
            'skills_demonstrated' => $skills,
            'github_url' => $validated['github_url'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'verification_badge' => null, // null means self-submitted, not task completion verified
            'completed_at' => now(),
            'startup_name' => 'Self-submitted',
        ]);

        return redirect()->route('student.profile')->with('success', 'Portfolio project added successfully! This has registered proof of work for the selected skills.');
    }

    public function deletePortfolioItem($id)
    {
        $profile = auth()->user()->studentProfile;
        $item = \App\Models\PortfolioItem::whereHas('portfolio', function($q) use ($profile) {
            $q->where('student_profile_id', $profile->id);
        })->findOrFail($id);

        // Prevent deleting platform task completions (they have a task_id)
        if ($item->task_id) {
            return back()->with('error', 'You cannot delete verified platform task projects.');
        }

        $item->delete();

        return redirect()->route('student.profile')->with('success', 'Portfolio project removed successfully.');
    }
}

