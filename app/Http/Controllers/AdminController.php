<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Submission;
use App\Models\Certificate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(\App\Services\AdminAnalyticsService $analyticsService)
    {
        $data = $analyticsService->getAnalyticsData();
        return view('admin.dashboard', compact('data'));
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
            ->with(['user', 'verificationLogs'])
            ->orderByRaw("
                CASE 
                    WHEN verification_level = 'A' THEN 1
                    WHEN verification_level = 'B' THEN 2
                    WHEN verification_level = 'C' THEN 3
                    WHEN verification_level = 'D' THEN 4
                    ELSE 5
                END ASC
            ")
            ->orderBy('is_suspicious', 'desc')
            ->orderBy('verification_submitted_at', 'asc')
            ->get();

        $recentlyReviewed = \App\Models\StartupProfile::whereIn('verification_status', ['approved', 'rejected'])
            ->whereNotNull('verification_reviewed_at')
            ->with(['user', 'verificationLogs'])
            ->orderBy('verification_reviewed_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.verifications', compact('pendingVerifications', 'recentlyReviewed'));
    }

    public function approveVerification($id)
    {
        $startup = \App\Models\StartupProfile::findOrFail($id);
        $oldStatus = $startup->ai_verification_status;

        $startup->update([
            'verification_status' => 'approved',
            'is_verified' => true,
            'verification_reviewed_at' => now(),
            'verification_notes' => null,
            'verification_expires_at' => now()->addMonths(12),
            'ai_verification_status' => 'admin_approved',
            'ai_verified_at' => now(),
        ]);

        // Recalculate trust score (it will set it to 50 + other platform events)
        $startup->recalculateTrustScore();

        // Create log entry
        \App\Models\StartupVerificationLog::create([
            'startup_profile_id' => $startup->id,
            'action'             => 'admin_approved',
            'performed_by'       => 'admin',
            'old_status'         => $oldStatus,
            'new_status'         => 'admin_approved',
            'reason'             => 'Admin manually approved verification.',
            'created_at'         => now(),
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
        $oldStatus = $startup->ai_verification_status;

        $startup->update([
            'verification_status' => 'rejected',
            'is_verified' => false,
            'verification_reviewed_at' => now(),
            'verification_notes' => $request->notes,
            'ai_verification_status' => 'admin_rejected',
        ]);

        $startup->recalculateTrustScore();

        // Create log entry
        \App\Models\StartupVerificationLog::create([
            'startup_profile_id' => $startup->id,
            'action'             => 'admin_rejected',
            'performed_by'       => 'admin',
            'old_status'         => $oldStatus,
            'new_status'         => 'admin_rejected',
            'reason'             => $request->notes,
            'created_at'         => now(),
        ]);

        return back()->with('success', 'Startup verification rejected. They will see your feedback.');
    }

    public function toggleSuspiciousFlag($id)
    {
        $startup = \App\Models\StartupProfile::findOrFail($id);
        $oldSuspicious = $startup->is_suspicious;
        $newSuspicious = !$oldSuspicious;

        $startup->update([
            'is_suspicious' => $newSuspicious
        ]);

        $startup->recalculateTrustScore();

        $action = $newSuspicious ? 'marked_suspicious' : 'cleared_suspicious';
        $reason = $newSuspicious ? 'Admin marked this startup as suspicious.' : 'Admin cleared suspicious flag for this startup.';

        \App\Models\StartupVerificationLog::create([
            'startup_profile_id' => $startup->id,
            'action'             => $action,
            'performed_by'       => 'admin',
            'old_status'         => $startup->ai_verification_status,
            'new_status'         => $startup->ai_verification_status,
            'reason'             => $reason,
            'created_at'         => now(),
        ]);

        $message = $newSuspicious 
            ? 'Startup has been marked as suspicious and active verification frozen.' 
            : 'Startup suspicious flag has been cleared.';

        return back()->with('success', $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Student ID Card AI Review Queue
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Show all student ID cards pending manual admin review.
     */
    public function studentIdQueue()
    {
        $pendingQueue = \App\Models\StudentProfile::where('id_card_verification_status', 'manual_review')
            ->whereNotNull('id_card_path')
            ->with('user')
            ->orderBy('id_card_submitted_at', 'asc')
            ->get();

        $recentlyReviewed = \App\Models\StudentProfile::whereIn('id_card_verification_status', ['admin_approved', 'ai_approved', 'ai_rejected'])
            ->whereNotNull('id_card_verified_at')
            ->with('user')
            ->orderBy('id_card_verified_at', 'desc')
            ->take(15)
            ->get();

        return view('admin.student-id-queue', compact('pendingQueue', 'recentlyReviewed'));
    }

    /**
     * Admin manually approves a student's college ID card.
     */
    public function approveStudentId($id)
    {
        $profile = \App\Models\StudentProfile::findOrFail($id);

        $profile->update([
            'id_card_verification_status' => 'admin_approved',
            'id_card_verified_at'         => now(),
            'is_verified'                 => true,
            'verification_method'         => 'admin_manual',
        ]);

        return back()->with('success', 'Student ID verified. Student now has full platform access.');
    }

    /**
     * Admin rejects a student's college ID card with a reason.
     */
    public function rejectStudentId(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $profile = \App\Models\StudentProfile::findOrFail($id);

        // Store rejection reason inside existing ai_result JSON
        $aiResult = $profile->id_card_ai_result ?? [];
        $aiResult['admin_rejection_reason'] = $request->notes;

        $profile->update([
            'id_card_verification_status' => 'ai_rejected',
            'id_card_ai_result'           => $aiResult,
            'is_verified'                 => false,
        ]);

        return back()->with('success', 'Student ID rejected. They will be asked to re-upload.');
    }

    public function exportRevenue()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=revenue_analytics_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Month', 'Task Commissions (INR)', 'Hiring Success Fees (INR)', 'Total Revenue (INR)']);
            
            $analyticsService = app(\App\Services\AdminAnalyticsService::class);
            $data = $analyticsService->getAnalyticsData();
            
            foreach ($data['revenue']['chart_12m'] as $row) {
                fputcsv($file, [$row['month'], $row['tasks'], $row['hiring'], $row['total']]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportHiring()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=hiring_analytics_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Offer ID', 'Startup', 'Student', 'Type', 'Title', 'Compensation (INR)', 'Period', 'Status', 'Reserved Fee (INR)', 'Created At', 'Confirmed At']);
            
            $offers = \App\Models\HiringOffer::with(['startup.user', 'student.user'])->latest()->get();
            foreach ($offers as $o) {
                fputcsv($file, [
                    $o->id,
                    $o->startup->company_name ?? $o->startup->user->name ?? 'Startup #' . $o->startup_profile_id,
                    $o->student->user->name ?? 'Student #' . $o->student_profile_id,
                    ucfirst($o->offer_type),
                    $o->title,
                    $o->compensation,
                    $o->compensation_period,
                    ucfirst($o->status),
                    $o->reserved_fee,
                    $o->created_at->toDateTimeString(),
                    $o->joining_confirmed_at ? \Carbon\Carbon::parse($o->joining_confirmed_at)->toDateTimeString() : 'N/A'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportUsers()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=user_analytics_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['User ID', 'Name', 'Email', 'Role', 'Verified', 'Wallet Balance (INR)', 'Created At']);
            
            $users = \App\Models\User::with(['studentProfile', 'startupProfile'])->get();
            foreach ($users as $u) {
                $verified = 'No';
                $balance = 0.00;
                
                if ($u->role === 'student' && $u->studentProfile) {
                    $verified = $u->studentProfile->is_verified ? 'Yes' : 'No';
                    $balance = $u->studentProfile->wallet_balance;
                } elseif ($u->role === 'startup' && $u->startupProfile) {
                    $verified = $u->startupProfile->is_verified ? 'Yes' : 'No';
                    $balance = $u->startupProfile->wallet_balance;
                }
                
                fputcsv($file, [
                    $u->id,
                    $u->name,
                    $u->email,
                    ucfirst($u->role),
                    $verified,
                    $balance,
                    $u->created_at->toDateTimeString()
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
