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
        
        return view('startup.dashboard', compact('profile', 'tasks', 'completedTasks', 'totalPointsGiven', 'hiringOffers'));
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

        $validated = $request->validate([
            'company_registration_number' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:255',
            'company_address' => 'required|string',
            'contact_phone' => 'required|string|max:20',
            'documents' => ($hasExistingDocs ? 'nullable' : 'required') . '|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Handle document uploads
        $uploadedDocs = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('verification_documents', 'public');
                    $uploadedDocs[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                        'uploaded_at' => now()->toDateTimeString()
                    ];
                }
            }
        }

        $profile->update([
            'company_registration_number' => $validated['company_registration_number'],
            'gst_number' => $validated['gst_number'],
            'company_address' => $validated['company_address'],
            'contact_phone' => $validated['contact_phone'],
            'verification_documents' => !empty($uploadedDocs) ? $uploadedDocs : $profile->verification_documents,
            'verification_status' => 'pending',
            'verification_submitted_at' => now(),
        ]);

        return redirect()->route('startup.dashboard')->with('success', 'Verification request submitted successfully! We will review your documents and notify you.');
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
        $topPhp = collect([]);
        $topUi = collect([]);

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

            // 5. Top PHP
            $topPhp = (clone $baseDiscoverQuery)
                ->whereHas('skills', fn($q) => $q->where('name', 'PHP'))
                ->take(4)
                ->get()
                ->map(fn($student) => $this->transformStudentForDiscovery($student, $targetSkills, $profile));

            // 6. Top UI/UX
            $topUi = (clone $baseDiscoverQuery)
                ->whereHas('skills', fn($q) => $q->where('name', 'UI/UX Design'))
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
            'topPhp',
            'topUi'
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

