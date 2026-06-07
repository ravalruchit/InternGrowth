<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Submission;
use App\Models\Certificate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingStartups = User::where('role', 'startup')
            ->whereHas('startupProfile', function($query) {
                $query->where('verification_status', 'pending');
            })->count();
        $flaggedTasks = Task::where('is_flagged', true)->count();
        $plagiarizedSubmissions = Submission::where('is_plagiarized', true)->count();
        
        return view('admin.dashboard', compact('pendingStartups', 'flaggedTasks', 'plagiarizedSubmissions'));
    }

    public function startups()
    {
        $startups = User::where('role', 'startup')->with('startupProfile')->latest()->get();
        return view('admin.startups', compact('startups'));
    }

    public function approveStartup($id)
    {
        $user = User::findOrFail($id);
        if ($user->startupProfile) {
            $user->startupProfile->update(['is_verified' => true]);
            
            // Set session flag for the startup user to show verification alert once
            session()->put('startup_just_verified_' . $user->id, true);
            
            return back()->with('success', 'Startup approved successfully');
        }
        return back()->with('error', 'Startup profile not found');
    }

    public function tasks()
    {
        $tasks = Task::with('startup.user')->latest()->get();
        return view('admin.tasks', compact('tasks'));
    }

    public function moderateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Task moderated');
    }

    public function submissions()
    {
        $submissions = Submission::where('is_plagiarized', true)
            ->with('application.student.user', 'application.task')
            ->latest()
            ->get();
        return view('admin.submissions', compact('submissions'));
    }

    public function issueCertificate($submissionId)
    {
        $submission = Submission::with('application')->findOrFail($submissionId);
        
        $certificateNumber = 'CERT-' . strtoupper(uniqid());
        
        Certificate::create([
            'student_profile_id' => $submission->application->student_profile_id,
            'task_id' => $submission->application->task_id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now()
        ]);

        return back()->with('success', 'Certificate issued');
    }

    // Students CRUD
    public function students()
    {
        $students = User::where('role', 'student')
            ->with('studentProfile')
            ->latest()
            ->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.students.create');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        $profile = \App\Models\StudentProfile::create([
            'user_id' => $user->id,
            'bio' => $request->bio,
            'education' => $request->education,
            'experience' => $request->experience,
            'skills' => json_encode([]),
            'portfolio_url' => '',
            'github_url' => '',
            'linkedin_url' => '',
            'reliability_score' => 1.0,
        ]);

        // Create points wallet
        \App\Models\PointsWallet::create([
            'student_profile_id' => $profile->id,
            'balance' => 0,
        ]);

        return redirect()->route('admin.students')->with('success', 'Student created successfully');
    }

    public function editStudent($id)
    {
        $student = User::where('role', 'student')
            ->with('studentProfile')
            ->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->studentProfile) {
            $user->studentProfile->update([
                'bio' => $request->bio,
                'education' => $request->education,
                'experience' => $request->experience,
            ]);
        }

        return redirect()->route('admin.students')->with('success', 'Student updated successfully');
    }

    public function deleteStudent($id)
    {
        $user = User::where('role', 'student')->findOrFail($id);
        
        // Delete related data
        if ($user->studentProfile) {
            $user->studentProfile->delete();
        }
        
        $user->delete();
        
        return back()->with('success', 'Student deleted successfully');
    }

    // Student Verification Management
    public function verifyStudent($id)
    {
        $user = User::where('role', 'student')->findOrFail($id);
        
        if ($user->studentProfile) {
            $user->studentProfile->update([
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
            
            return back()->with('success', 'Student verified successfully');
        }
        
        return back()->with('error', 'Student profile not found');
    }

    public function unverifyStudent($id)
    {
        $user = User::where('role', 'student')->findOrFail($id);
        
        if ($user->studentProfile) {
            $user->studentProfile->update([
                'is_verified' => false,
                'email_verified_at' => null,
            ]);
            
            return back()->with('success', 'Student unverified successfully');
        }
        
        return back()->with('error', 'Student profile not found');
    }

    // Startups CRUD (enhanced)
    public function createStartup()
    {
        return view('admin.startups.create');
    }

    public function storeStartup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'industry' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'startup',
            'email_verified_at' => now(),
        ]);

        \App\Models\StartupProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'description' => $request->description,
            'industry' => $request->industry,
            'website' => '',
            'location' => '',
            'team_size' => '',
            'founded_year' => null,
            'credibility_score' => 1.0,
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('admin.startups')->with('success', 'Startup created successfully');
    }

    public function editStartup($id)
    {
        $startup = User::where('role', 'startup')
            ->with('startupProfile')
            ->findOrFail($id);
        return view('admin.startups.edit', compact('startup'));
    }

    public function updateStartup(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'company_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'industry' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->startupProfile) {
            $user->startupProfile->update([
                'company_name' => $request->company_name,
                'description' => $request->description,
                'industry' => $request->industry,
                'is_verified' => $request->has('is_verified'),
            ]);
        }

        return redirect()->route('admin.startups')->with('success', 'Startup updated successfully');
    }

    public function deleteStartup($id)
    {
        $user = User::where('role', 'startup')->findOrFail($id);
        
        // Delete related data
        if ($user->startupProfile) {
            $user->startupProfile->delete();
        }
        
        $user->delete();
        
        return back()->with('success', 'Startup deleted successfully');
    }

    // Verification Management
    public function verifications()
    {
        $pendingVerifications = \App\Models\StartupProfile::where('verification_status', 'pending')
            ->whereNotNull('verification_submitted_at')
            ->with('user')
            ->orderBy('verification_submitted_at', 'desc')
            ->get();

        $recentlyReviewed = \App\Models\StartupProfile::whereIn('verification_status', ['approved', 'rejected'])
            ->whereNotNull('verification_reviewed_at')
            ->with('user')
            ->orderBy('verification_reviewed_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.verifications', compact('pendingVerifications', 'recentlyReviewed'));
    }

    public function approveVerification($id)
    {
        $startup = \App\Models\StartupProfile::findOrFail($id);

        $startup->update([
            'verification_status' => 'approved',
            'is_verified' => true,
            'verification_reviewed_at' => now(),
            'verification_notes' => null,
        ]);

        // Set session flag for the startup user to show verification alert once
        session()->put('startup_just_verified_' . $startup->user_id, true);

        return back()->with('success', 'Startup verification approved successfully. They can now post tasks.');
    }

    public function rejectVerification(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $startup = \App\Models\StartupProfile::findOrFail($id);

        $startup->update([
            'verification_status' => 'rejected',
            'is_verified' => false,
            'verification_reviewed_at' => now(),
            'verification_notes' => $request->notes,
        ]);

        return back()->with('success', 'Startup verification rejected. They will see your feedback.');
    }

}
