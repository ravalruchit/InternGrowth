<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Ensure startup profile exists
        if (!$user->startupProfile) {
            return redirect()->route('startup.profile')->with('error', 'Please complete your profile first.');
        }
        
        $profile = $user->startupProfile->load(['trustScore', 'reviews.student.user', 'reviews.task']);
        $tasks = $profile->tasks()
            ->with([
                'applications.student.user', 
                'applications.submission',
                'ratings'
            ])
            ->latest()
            ->get();
        
        // Get completed tasks
        $completedTasks = $tasks->filter(function($task) {
            return $task->applications->filter(function($app) {
                return $app->submission && $app->submission->status === 'accepted';
            })->count() > 0;
        });
        
        // Calculate total points given
        $totalPointsGiven = $completedTasks->sum('reward_points');
        
        $hiringOffers = \App\Models\HiringOffer::where('startup_profile_id', $profile->id)
            ->with('student.user')
            ->latest()
            ->get();
        
        // Calculate domain metrics for analytics
        $domains = [
            'Software Development',
            'UI/UX Design',
            'Digital Marketing',
            'Data & AI',
            'Content & Business'
        ];
        $applicationsByDomain = [];
        $hiringSuccessByDomain = [];
        
        foreach ($domains as $d) {
            $applicationsByDomain[$d] = 0;
            $hiringSuccessByDomain[$d] = 0;
        }

        foreach ($tasks as $task) {
            if ($task->domain && isset($applicationsByDomain[$task->domain])) {
                $applicationsByDomain[$task->domain] += $task->applications->count();
            }
            if ($task->domain && isset($hiringSuccessByDomain[$task->domain])) {
                $completedCount = $task->applications->filter(function($app) {
                    return $app->submission && $app->submission->status === 'accepted';
                })->count();
                $hiringSuccessByDomain[$task->domain] += $completedCount;
            }
        }

        foreach ($hiringOffers as $offer) {
            if ($offer->status === 'accepted' && $offer->domain && isset($hiringSuccessByDomain[$offer->domain])) {
                $hiringSuccessByDomain[$offer->domain] += 1;
            }
        }

        $domainStats = [];
        foreach ($domains as $d) {
            $domainStats[$d] = [
                'hires' => $hiringSuccessByDomain[$d],
                'apps' => $applicationsByDomain[$d]
            ];
        }

        uasort($domainStats, function($a, $b) {
            if ($b['hires'] !== $a['hires']) {
                return $b['hires'] - $a['hires'];
            }
            return $b['apps'] - $a['apps'];
        });

        $topPerformingDomains = array_keys($domainStats);

        return view('startup.dashboard', compact(
            'profile', 
            'tasks', 
            'completedTasks', 
            'totalPointsGiven', 
            'hiringOffers',
            'applicationsByDomain',
            'hiringSuccessByDomain',
            'topPerformingDomains'
        ));
    }

    public function profile()
    {
        $profile = auth()->user()->startupProfile;
        return view('startup.profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        auth()->user()->startupProfile->update($validated);

        return redirect()->route('startup.profile')->with('success', 'Profile updated');
    }

    public function clearVerificationAlert()
    {
        session()->forget('startup_just_verified_' . auth()->id());
        return response()->json(['success' => true]);
    }

    public function verification()
    {
        $profile = auth()->user()->startupProfile;
        return view('startup.verification', compact('profile'));
    }

    public function submitVerification(Request $request)
    {
        $profile = auth()->user()->startupProfile;
        $hasExistingDocs = $profile->verification_documents && count($profile->verification_documents) > 0;

        // Rate Limit Check: Maximum 3 verification attempts per day
        $recentAttemptsCount = \App\Models\StartupVerificationLog::where('startup_profile_id', $profile->id)
            ->where('action', 'verification_submitted')
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($recentAttemptsCount >= 3) {
            return back()->withInput()->with('error', 'Rate limit exceeded: You can submit a maximum of 3 verification requests per 24 hours. Please wait before attempting again.');
        }

        $request->validate([
            'company_registration_number' => ['required', 'string', 'regex:/^[UL][0-9]{5}[A-Z]{2}[0-9]{4}[A-Z]{3}[0-9]{6}$/i'],
            'gst_number'                  => ['required', 'string', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i'],
            'company_address'             => 'required|string',
            'contact_phone'               => 'required|string|max:20',
            'gst_certificate'             => ($hasExistingDocs ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'registration_document'       => ($hasExistingDocs ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'supporting_document'         => ($hasExistingDocs ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'company_registration_number.regex' => 'The registration number must be a valid 21-character Corporate Identification Number (CIN) format (e.g. U72900GJ2025PTC123456).',
            'gst_number.regex'                  => 'The GSTIN must match the official 15-character format (e.g. 24ABCDE1234F1Z5).',
        ]);

        // AI Protection: Check if any new files are uploaded
        $filesUploaded = $request->hasFile('gst_certificate') || $request->hasFile('registration_document') || $request->hasFile('supporting_document');

        $textFieldsChanged = $profile->company_name !== $request->company_name ||
                             $profile->company_registration_number !== $request->company_registration_number ||
                             $profile->gst_number !== $request->gst_number;

        if (!$filesUploaded && $hasExistingDocs && ($profile->is_verified || !$textFieldsChanged)) {
            // Text-only update: Save details but do not run AI scan or reset verification status
            $profile->update([
                'company_name'                => $request->company_name,
                'company_registration_number' => $request->company_registration_number,
                'gst_number'                  => $request->gst_number,
                'company_address'             => $request->company_address,
                'contact_phone'               => $request->contact_phone,
            ]);
            return redirect()->route('startup.dashboard')->with('success', 'Company details updated successfully (no AI re-scan needed as details were unchanged).');
        }

        // Duplicate Company Check (GST/CIN)
        $duplicate = \App\Models\StartupProfile::where('id', '!=', $profile->id)
            ->where(function($q) use ($request) {
                $q->where('company_registration_number', $request->company_registration_number)
                  ->orWhere(function($sub) use ($request) {
                      if (!empty($request->gst_number)) {
                          $sub->where('gst_number', $request->gst_number);
                      } else {
                          $sub->whereRaw('0 = 1');
                      }
                  });
            })->first();

        if ($duplicate) {
            $oldStatus = $profile->ai_verification_status;
            $profile->update([
                'company_name'                => $request->company_name,
                'company_registration_number' => $request->company_registration_number,
                'gst_number'                  => $request->gst_number,
                'company_address'             => $request->company_address,
                'contact_phone'               => $request->contact_phone,
                'ai_verification_status'      => 'ai_flagged',
                'verification_level'          => 'D',
                'ai_confidence_score'         => 0,
                'verification_status'         => 'pending',
                'verification_submitted_at'   => now(),
                'ai_verification_result'      => [
                    'fraud_score' => 100,
                    'reason' => 'DUPLICATE DETAILS: Another company is already registered with this GSTIN or CIN. Flagged for admin review.',
                    'security_flags' => ['duplicate_company_details']
                ]
            ]);

            \App\Models\StartupVerificationLog::create([
                'startup_profile_id' => $profile->id,
                'action'             => 'verification_submitted',
                'performed_by'       => 'system',
                'old_status'         => $oldStatus,
                'new_status'         => 'ai_flagged',
                'reason'             => 'Duplicate registration credentials flagged during format check.',
                'created_at'         => now(),
            ]);

            return redirect()->route('startup.dashboard')->with('error', 'Verification request submitted. A duplicate warning has been flagged for admin review.');
        }

        // Handle file uploads
        $uploadedDocs = $profile->verification_documents ?? [];

        if ($request->hasFile('gst_certificate')) {
            $file = $request->file('gst_certificate');
            $path = $file->store('verification_documents', 'public');
            $uploadedDocs['gst_certificate'] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $file->getClientMimeType(),
                'uploaded_at' => now()->toDateTimeString()
            ];
        }

        if ($request->hasFile('registration_document')) {
            $file = $request->file('registration_document');
            $path = $file->store('verification_documents', 'public');
            $uploadedDocs['registration_document'] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $file->getClientMimeType(),
                'uploaded_at' => now()->toDateTimeString()
            ];
        }

        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $path = $file->store('verification_documents', 'public');
            $uploadedDocs['supporting_document'] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $file->getClientMimeType(),
                'uploaded_at' => now()->toDateTimeString()
            ];
        }

        // File Hash Cache Check
        $gstPath = $uploadedDocs['gst_certificate']['path'] ?? null;
        $regPath = $uploadedDocs['registration_document']['path'] ?? null;
        $hashString = '';
        if ($gstPath) $hashString .= hash_file('sha256', storage_path('app/public/' . $gstPath));
        if ($regPath) $hashString .= hash_file('sha256', storage_path('app/public/' . $regPath));
        $newHash = hash('sha256', $hashString);

        if ($profile->verification_documents_hash === $newHash && !$textFieldsChanged && $profile->ai_verification_result) {
            // Reuse previous result
            $oldStatus = $profile->ai_verification_status;
            $profile->update([
                'company_name'                => $request->company_name,
                'company_registration_number' => $request->company_registration_number,
                'gst_number'                  => $request->gst_number,
                'company_address'             => $request->company_address,
                'contact_phone'               => $request->contact_phone,
                'verification_status'         => 'pending',
                'verification_submitted_at'   => now(),
            ]);

            \App\Models\StartupVerificationLog::create([
                'startup_profile_id' => $profile->id,
                'action'             => 'verification_submitted',
                'performed_by'       => 'system',
                'old_status'         => $oldStatus,
                'new_status'         => $profile->ai_verification_status,
                'reason'             => 'Verification documents unchanged. Reused cached AI results.',
                'created_at'         => now(),
            ]);

            return redirect()->route('startup.dashboard')->with('success', 'Verification request submitted. Reused previous AI results as files were unchanged.');
        }

        // Call Gemini AI
        $aiService = new \App\Services\AIStartupVerificationService();
        $result = $aiService->verifyStartup(
            $gstPath,
            $regPath,
            $request->company_name,
            $request->gst_number,
            $request->company_registration_number
        );

        $oldStatus = $profile->ai_verification_status;
        $newAIStatus = $result['recommendation'] === 'pre_approve' ? 'ai_pre_approved' : ($result['recommendation'] === 'reject' ? 'ai_rejected' : 'ai_flagged');

        $profile->update([
            'company_name'                => $request->company_name,
            'company_registration_number' => $request->company_registration_number,
            'gst_number'                  => $request->gst_number,
            'company_address'             => $request->company_address,
            'contact_phone'               => $request->contact_phone,
            'verification_documents'      => $uploadedDocs,
            'verification_documents_hash' => $newHash,
            'ai_verification_status'      => $newAIStatus,
            'ai_verification_result'      => $result,
            'ai_confidence_score'         => $result['verification_score'],
            'verification_level'          => $result['verification_level'],
            'verification_status'         => $result['recommendation'] === 'reject' ? 'rejected' : 'pending',
            'verification_submitted_at'   => now(),
        ]);

        \App\Models\StartupVerificationLog::create([
            'startup_profile_id' => $profile->id,
            'action'             => 'verification_submitted',
            'performed_by'       => 'ai',
            'old_status'         => $oldStatus,
            'new_status'         => $newAIStatus,
            'reason'             => "AI document scan completed. Verification Score: {$result['verification_score']} (Level {$result['verification_level']}). Details: {$result['reason']}",
            'created_at'         => now(),
        ]);

        if ($result['recommendation'] === 'reject') {
            return redirect()->route('startup.verification')->with('error', "Verification failed: {$result['reason']}");
        }

        return redirect()->route('startup.dashboard')->with('success', 'Verification documents uploaded and scanned successfully. Awaiting admin approval.');
    }

    private function transformStudentForDiscovery($student, $targetSkills, $profile)
    {
        // Check if student has been saved by this startup
        $student->is_saved = $student->savedByStartups->contains($profile->id);

        // Compute AI Match Score
        // 1. Skill Match (50%): fraction of target skills found in student's skills
        $studentSkillNames = $student->skills->pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();
        $targetSkillNamesLower = array_map(fn($n) => strtolower(trim($n)), $targetSkills);
        
        $matchingSkillsCount = 0;
        $skillBreakdown = [];

        foreach ($targetSkillNamesLower as $targetSkill) {
            $origSkillName = collect($targetSkills)->first(fn($s) => strtolower(trim($s)) === $targetSkill) ?? $targetSkill;
            
            if (in_array($targetSkill, $studentSkillNames)) {
                $matchingSkillsCount++;
                // Check if this skill is verified
                $isVerified = $student->skillVerifications->contains(function($v) use ($targetSkill) {
                    return strtolower(trim($v->skill->name ?? '')) === $targetSkill;
                });
                
                // Score verified higher (90-95%) than unverified (78-85%)
                $skillBreakdown[$origSkillName] = $isVerified ? rand(91, 96) : rand(78, 85);
            } else {
                $skillBreakdown[$origSkillName] = 0;
            }
        }

        $skillMatchScore = 0;
        if (count($targetSkills) > 0) {
            $skillMatchScore = ($matchingSkillsCount / count($targetSkills)) * 100;
        } else {
            $skillMatchScore = 100;
        }

        // 2. Reputation Match (35%): Based on overall reputation score
        $reputationScoreValue = $student->reputationScore->overall_score ?? 50.00;

        // 3. Communication Score: from reputation score or default
        $commScore = ($student->reputationScore->communication_rating ?? 4.8) * 20;

        $totalMatchScore = ($skillMatchScore * 0.50) + ($reputationScoreValue * 0.35) + ($commScore * 0.15);
        $totalMatchScore = round(max(50, min(99, $totalMatchScore)));

        $student->ai_match = [
            'percentage' => $totalMatchScore,
            'breakdown' => $skillBreakdown,
            'communication' => round($commScore)
        ];

        // Load additional counts for cards
        $student->projects_count = $student->portfolio ? $student->portfolio->items->count() : 0;
        $student->internships_count = $student->hiringOffers
            ->where('offer_type', 'internship')
            ->where('status', 'accepted')
            ->count();
        $student->offers_count = $student->hiringOffers
            ->where('offer_type', 'job')
            ->count();

        return $student;
    }

    public function candidates(Request $request)
    {
        $user = auth()->user();
        $profile = $user->startupProfile;

        // Ensure startup profile exists
        if (!$profile) {
            return redirect()->route('startup.profile')->with('error', 'Please complete your profile first.');
        }

        // Enforce verification check
        if (!$profile->isVerifiedAndActive()) {
            return redirect()->route('startup.verification')
                ->with('error', 'You must have a verified startup account to access the candidate directory.');
        }

        // Get startup's posted tasks for the AI Match dropdown
        $postedTasks = $profile->tasks()->latest()->get();

        // Standard positions list for AI Match dropdown
        $defaultPositions = [
            'laravel_intern' => [
                'name' => 'Laravel Intern Position',
                'skills' => ['PHP', 'Laravel']
            ],
            'react_intern' => [
                'name' => 'React Developer Intern',
                'skills' => ['React', 'JavaScript']
            ],
            'python_backend' => [
                'name' => 'Python Backend Developer',
                'skills' => ['Python']
            ],
            'ui_ux' => [
                'name' => 'UI/UX Designer',
                'skills' => ['UI/UX Design']
            ],
            'content_writer' => [
                'name' => 'Content Writer',
                'skills' => ['Content Writing']
            ]
        ];

        // Determine current target position & skills for matching
        $positionMatchKey = $request->input('position_match', 'laravel_intern');
        $targetPositionName = '';
        $targetSkills = [];

        if (str_starts_with($positionMatchKey, 'task_')) {
            $taskId = (int) str_replace('task_', '', $positionMatchKey);
            $task = $profile->tasks()->find($taskId);
            if ($task) {
                $targetPositionName = $task->title;
                $targetSkills = is_array($task->required_skills) 
                    ? $task->required_skills 
                    : json_decode($task->required_skills ?? '[]', true);
            }
        }

        if (empty($targetPositionName) && isset($defaultPositions[$positionMatchKey])) {
            $targetPositionName = $defaultPositions[$positionMatchKey]['name'];
            $targetSkills = $defaultPositions[$positionMatchKey]['skills'];
        }

        // Default if invalid
        if (empty($targetPositionName)) {
            $positionMatchKey = 'laravel_intern';
            $targetPositionName = $defaultPositions['laravel_intern']['name'];
            $targetSkills = $defaultPositions['laravel_intern']['skills'];
        }

        // Fetch all skills for the filter dropdown
        $allSkills = \App\Models\Skill::all();

        // Active View/Tab (discover or search)
        $activeTab = $request->input('tab', 'discover');

        $topTalent = collect([]);
        $fastestGrowing = collect([]);
        $mostReliable = collect([]);
        $recommended = collect([]);
        $topSoftware = collect([]);
        $topDesigners = collect([]);
        $topMarketers = collect([]);
        $topDataAi = collect([]);
        $topBusiness = collect([]);

        if ($activeTab === 'discover') {
            $baseDiscoverQuery = \App\Models\StudentProfile::query()
                ->with(['user', 'skills', 'reputationScore', 'certificates', 'hiringOffers', 'portfolio.items', 'skillVerifications.skill']);

            // 1. Top Talent
            $topTalent = (clone $baseDiscoverQuery)
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 2. Fastest Growing
            $fastestGrowing = (clone $baseDiscoverQuery)
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.total_verified_projects, 0) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 3. Most Reliable
            $mostReliable = (clone $baseDiscoverQuery)
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.completion_rate, 100.00) DESC')
                ->orderByRaw('COALESCE(reputation_scores.on_time_rate, 100.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 4. Recommended For You (based on startup task skills)
            $startupSkills = $profile->tasks()->with('skills')->get()->flatMap(fn($t) => $t->skills->pluck('id'))->unique()->toArray();
            if (!empty($startupSkills)) {
                $recommended = (clone $baseDiscoverQuery)
                    ->whereHas('skills', fn($q) => $q->whereIn('skills.id', $startupSkills))
                    ->take(4)
                    ->get()
                    ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));
            } else {
                $recommended = $topTalent;
            }

            // 5. Top Software Developers
            $topSoftware = (clone $baseDiscoverQuery)
                ->where('primary_domain', 'Software Development')
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 6. Top UI/UX Designers
            $topDesigners = (clone $baseDiscoverQuery)
                ->where('primary_domain', 'UI/UX Design')
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 7. Top Digital Marketers
            $topMarketers = (clone $baseDiscoverQuery)
                ->where('primary_domain', 'Digital Marketing')
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 8. Top Data & AI
            $topDataAi = (clone $baseDiscoverQuery)
                ->where('primary_domain', 'Data & AI')
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 9. Top Content & Business
            $topBusiness = (clone $baseDiscoverQuery)
                ->where('primary_domain', 'Content & Business')
                ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC')
                ->select('student_profiles.*')
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // Set empty paginate object for views
            $students = \App\Models\StudentProfile::whereRaw('0 = 1')->paginate(10);
        } else {
            // Build Student query for directory search
            $query = \App\Models\StudentProfile::query();

            // Filter by Search Name/Bio
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('bio', 'like', $searchTerm)
                      ->orWhereHas('user', function($uq) use ($searchTerm) {
                          $uq->where('name', 'like', $searchTerm);
                      });
                });
            }

            // Filter by Domain
            if ($request->filled('domain')) {
                $query->where('primary_domain', $request->domain);
            }

            // Filter by Role
            if ($request->filled('role')) {
                $query->where('preferred_role', $request->role);
            }

            // Filter by Skill (dropdown)
            if ($request->filled('skill_id')) {
                $query->whereHas('skills', function($q) use ($request) {
                    $q->where('skills.id', $request->skill_id);
                });
            }

            // Filter by College (text search)
            if ($request->filled('college')) {
                $query->where('college_name', 'like', '%' . $request->college . '%');
            }

            // Filter by Availability Checkboxes
            if ($request->filled('availability') && is_array($request->availability)) {
                $query->whereIn('availability', $request->availability);
            }

            // Filter by Saved Bookmarks Only
            if ($request->boolean('bookmarked_only')) {
                $query->whereHas('savedByStartups', function($q) use ($profile) {
                    $q->where('startup_profiles.id', $profile->id);
                });
            }

            // Join reputation score for min IPRS filter and ordering
            $query->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
                  ->select('student_profiles.*');

            // Filter by Min IPRS
            if ($request->filled('min_iprs')) {
                $minIprs = (float) $request->min_iprs;
                $query->where(function($q) use ($minIprs) {
                    $q->where('reputation_scores.overall_score', '>=', $minIprs)
                      ->orWhere(function($sub) use ($minIprs) {
                          if ($minIprs <= 50.00) {
                              $sub->whereNull('reputation_scores.id');
                          } else {
                              $sub->whereRaw('0 = 1');
                          }
                      });
                });
            }

            // Default sorting: Order by overall score (descending)
            $query->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) DESC');

            // Load relations
            $query->with(['user', 'skills', 'reputationScore', 'certificates', 'hiringOffers', 'portfolio.items', 'skillVerifications.skill']);

            // Paginate results
            $students = $query->paginate(10)->withQueryString();

            // Calculate Match Scores & Stats for each student
            $students->getCollection()->transform(function($student) use ($targetSkills, $profile) {
                return $this->transformStudentForDiscovery($student, $targetSkills, $profile);
            });
        }

        return view('startup.candidates', compact(
            'students', 
            'allSkills', 
            'postedTasks', 
            'defaultPositions', 
            'positionMatchKey', 
            'targetPositionName',
            'targetSkills',
            'activeTab',
            'topTalent',
            'fastestGrowing',
            'mostReliable',
            'recommended',
            'topSoftware',
            'topDesigners',
            'topMarketers',
            'topDataAi',
            'topBusiness'
        ));
    }

    public function toggleSaveCandidate($id)
    {
        $student = \App\Models\StudentProfile::findOrFail($id);
        $startup = auth()->user()->startupProfile;

        $startup->savedCandidates()->toggle($student->id);
        $isSaved = $startup->savedCandidates()->where('student_profile_id', $student->id)->exists();

        $message = $isSaved ? 'Candidate saved successfully.' : 'Candidate removed from saved list.';
        return redirect()->back()->with('success', $message);
    }

    public function publicProfile($id)
    {
        $profile = \App\Models\StartupProfile::with(['trustScore', 'reviews.student.user', 'reviews.task'])->findOrFail($id);
        
        // Calculate students hired: approved task applications + accepted hiring offers
        $taskHires = \App\Models\Application::whereHas('task', function($q) use ($id) {
            $q->where('startup_profile_id', $id);
        })->whereIn('status', ['approved', 'completed'])->count();
        
        $directHires = \App\Models\HiringOffer::where('startup_profile_id', $id)
            ->where('status', 'accepted')
            ->count();
            
        $studentsHired = $taskHires + $directHires;
        
        // Average rating
        $averageRating = $profile->reviews()->avg('rating') ?? 0.0;
        
        // Active tasks count
        $activeTasks = $profile->tasks()->where('status', 'posted')->latest()->get();
        
        return view('startup.public-profile', compact('profile', 'studentsHired', 'averageRating', 'activeTasks'));
    }
}

