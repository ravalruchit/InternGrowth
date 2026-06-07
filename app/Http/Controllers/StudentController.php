<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use App\Services\MatchingService;
use Illuminate\Http\Request;
use App\Models\Skill;

class StudentController extends Controller
{
    public function __construct(
        private StudentRepository $repository,
        private MatchingService $matchingService
    ) {}

    public function dashboard()
    {
        $profile = auth()->user()->studentProfile->load(['certificates.task', 'skills', 'reputationScore', 'portfolio.items', 'startupReviews']);
        $hiringOffers = \App\Models\HiringOffer::where('student_profile_id', $profile->id)
            ->where('status', 'pending')
            ->with('startup')
            ->get();

        // Compute success metrics
        $projectsCompleted = $profile->certificates->count();
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
        return view('student.dashboard', compact(
            'profile', 
            'recommendedTasks', 
            'hiringOffers', 
            'projectsCompleted', 
            'internshipOffersCount', 
            'jobOffersCount', 
            'totalEarnings',
            'reviewedTaskIds'
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
        ]);

        // Update user name
        auth()->user()->update(['name' => $validated['name']]);

        // Update profile
        $profile = auth()->user()->studentProfile;
        $profile->update(['bio' => $validated['bio']]);
        
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
        return view('student.public-profile', compact('profile'));
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

        // Generate 6-digit verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $profile->update([
            'college_email' => $validated['college_email'],
            'college_name' => $validated['college_name'],
            'verification_token' => $code,
        ]);

        // Send verification email with code
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
            'code' => 'required|string',
        ]);

        $profile = auth()->user()->studentProfile;
        
        // Remove any spaces or special characters from input
        $inputCode = preg_replace('/[^0-9]/', '', $request->code);
        $storedCode = preg_replace('/[^0-9]/', '', $profile->verification_token);

        if ($storedCode === $inputCode) {
            $profile->update([
                'is_verified' => true,
                'email_verified_at' => now(),
                'verification_token' => null,
            ]);

            return redirect()->route('student.dashboard')->with('success', 'College email verified successfully! You now have access to all tasks.');
        }

        return back()->withInput()->with('error', 'Invalid verification code. Please try again.');
    }

    public function verifyEmail($token)
    {
        $profile = \App\Models\StudentProfile::where('verification_token', $token)->firstOrFail();

        $profile->update([
            'is_verified' => true,
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        return redirect()->route('student.dashboard')->with('success', 'College email verified successfully! You now have access to all tasks.');
    }

    public function analytics()
    {
        $profile = auth()->user()->studentProfile->load([
            'certificates.task',
            'skills',
            'ratings'
        ]);

        // Get all applications with their tasks and submissions
        $applications = \App\Models\Application::where('student_profile_id', $profile->id)
            ->with(['task.startup', 'submission'])
            ->get();

        // Load ratings separately for each application
        foreach($applications as $application) {
            $application->rating = \App\Models\Rating::where('task_id', $application->task_id)
                ->where('student_profile_id', $profile->id)
                ->first();
        }

        // Calculate analytics
        // Count completed tasks based on certificates (more reliable)
        $completedTasksCount = $profile->certificates->count();
        
        $completedTasks = $applications->filter(function($app) {
            return $app->submission && $app->submission->status === 'accepted';
        });

        $pendingTasks = $applications->filter(function($app) {
            return $app->status === 'approved' && (!$app->submission || $app->submission->status !== 'accepted');
        });

        $totalPoints = $completedTasks->sum(function($app) {
            return $app->task->reward_points;
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

        $analytics = [
            'completed_tasks' => $completedTasksCount, // Use certificate count instead
            'pending_tasks' => $pendingTasks->count(),
            'total_points' => $totalPoints,
            'total_stipend' => $totalStipend,
            'avg_rating' => $avgRating,
            'skills_stats' => array_slice($skillsStats, 0, 5), // Top 5 skills
            'success_rate' => round($successRate, 1),
            'active_applications' => $applications->whereIn('status', ['applied', 'approved'])->count(),
            'completed_tasks_list' => $completedTasks->sortByDesc('submission.updated_at')->take(10)
        ];

        return view('student.analytics', compact('profile', 'analytics', 'totalPoints'));
    }

    public function downloadCV()
    {
        $profile = auth()->user()->studentProfile->load([
            'certificates.task.startup',
            'skills',
            'ratings'
        ]);

        // Get completed tasks
        $completedTasks = \App\Models\Application::where('student_profile_id', $profile->id)
            ->with(['task.startup', 'submission'])
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })
            ->get();

        // Load ratings for each task
        foreach($completedTasks as $task) {
            $task->rating = \App\Models\Rating::where('task_id', $task->task_id)
                ->where('student_profile_id', $profile->id)
                ->first();
        }

        $totalPoints = $completedTasks->sum(function($app) {
            return $app->task->reward_points;
        });

        $totalStipend = $completedTasks->sum(function($app) {
            return $app->task->stipend ?? 0;
        });

        return view('student.cv-download', compact('profile', 'completedTasks', 'totalPoints', 'totalStipend'));
    }
}
