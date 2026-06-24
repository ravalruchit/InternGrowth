<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Rating;
use App\Models\Notification;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function create($applicationId)
    {
        $application = \App\Models\Application::with('task.startup')->findOrFail($applicationId);
        
        // Check if user owns this application
        if ($application->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if application is approved
        if ($application->status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'You can only submit work for approved applications.');
        }
        
        // Check if already submitted
        if ($application->submission) {
            return redirect()->route('dashboard')->with('error', 'You have already submitted work for this task.');
        }
        
        return view('submissions.create', compact('application'));
    }

    public function store(Request $request, $applicationId)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $validated['application_id'] = $applicationId;
        
        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('submissions', 'public');
                    $uploadedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                        'uploaded_at' => now()->toDateTimeString(),
                        'version' => 'original'
                    ];
                }
            }
        }
        
        // Only set files if there are actual uploads, otherwise set to null
        $validated['files'] = !empty($uploadedFiles) ? $uploadedFiles : null;
        $submission = Submission::create($validated);

        // Notify the startup that work was submitted
        $application = \App\Models\Application::with('task.startup')->find($applicationId);
        if ($application && $application->task && $application->task->startup) {
            \App\Models\Notification::create([
                'user_id' => $application->task->startup->user_id,
                'title'   => 'Work Submitted',
                'message' => auth()->user()->name . ' submitted work for your task "' . $application->task->title . '".',
                'type'    => 'info',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Work submitted successfully!');
    }

    public function review($id)
    {
        $submission = Submission::with([
            'application.task.skills', 
            'application.student'
        ])->findOrFail($id);
        
        // Check if rating exists for this task and student
        $rating = Rating::where('task_id', $submission->application->task_id)
                       ->where('student_profile_id', $submission->application->student_profile_id)
                       ->first();

        // Load existing skill verifications for this task+student+startup
        $existingVerifications = \App\Models\SkillVerification::where('student_profile_id', $submission->application->student_profile_id)
            ->where('startup_profile_id', $submission->application->task->startup_profile_id)
            ->where('task_id', $submission->application->task_id)
            ->get()
            ->keyBy('skill_id');

        // Get all platform skills for the task
        $taskSkills = $submission->application->task->skills ?? collect();
        
        return view('submissions.review', compact('submission', 'rating', 'existingVerifications', 'taskSkills'));
    }

    public function accept(Request $request, $id)
    {
        $moneyMessage = \Illuminate\Support\Facades\DB::transaction(function() use ($id) {
            $submission = Submission::with(['application.task.skills', 'application.student'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($submission->status === 'accepted') {
                abort(400, 'Submission already accepted.');
            }

            $submission->update(['status' => 'accepted']);

            // Update task status to completed
            $task = $submission->application->task;
            $task->update(['status' => 'completed']);
            
            // Release escrow money
            $escrow = $task->escrow;
            $moneyMsg = '';
            if ($escrow && $escrow->status === 'locked') {
                $platformFee = round($escrow->amount * (env('PLATFORM_FEE_PERCENTAGE', 10) / 100), 2);
                $studentAmount = round($escrow->amount - $platformFee, 2);
                
                // Add to student wallet
                $studentProfile = $submission->application->student;
                $studentProfile->increment('wallet_balance', $studentAmount);
                
                // Update escrow status
                $escrow->update(['status' => 'released']);
                
                // Record transactions
                \App\Models\Transaction::create([
                    'user_type' => 'student',
                    'user_id' => $studentProfile->id,
                    'type' => 'credit',
                    'amount' => $studentAmount,
                    'description' => "Payment received for task: {$task->title}",
                    'reference_id' => "task_{$task->id}"
                ]);
                
                \App\Models\Transaction::create([
                    'user_type' => 'platform',
                    'user_id' => 0,
                    'type' => 'credit',
                    'amount' => $platformFee,
                    'description' => "Platform fee from task: {$task->title}",
                    'reference_id' => "task_{$task->id}"
                ]);
                
                $moneyMsg = " and ₹{$studentAmount}";
            }

            // Auto-generate verified portfolio item
            $portfolioService = new \App\Services\PortfolioAutomationService();
            $portfolioService->addVerifiedTaskToPortfolio($submission->id);

            // Verify skills associated with the completed task (track which startup verified)
            $skillsService = new \App\Services\SkillVerificationService();
            $startupProfileId = $task->startup_profile_id;
            foreach ($task->skills as $skill) {
                $skillsService->verifySkillByTaskCompletion($submission->application->student_profile_id, $skill->id, null, $startupProfileId, $task->id);
            }

            Notification::create([
                'user_id' => $submission->application->student->user_id,
                'title' => 'Submission Accepted',
                'message' => "Your submission was accepted. Your IPRS reputation score has been updated{$moneyMsg}!",
                'type' => 'success'
            ]);

            // Recalculate reputation scores
            $reputationService = new \App\Services\ReputationEngineService();
            $reputationService->updateReputation($submission->application->student_profile_id);

            return $moneyMsg;
        });

        return back()->with('success', 'Submission accepted and IPRS score updated' . $moneyMessage . '!');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate(['feedback' => 'required|string|min:20']);
        
        $moneyMessage = \Illuminate\Support\Facades\DB::transaction(function() use ($id, $validated) {
            $submission = Submission::with('application.student', 'application.task')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($submission->status === 'accepted' || $submission->status === 'rejected') {
                abort(400, 'Submission already reviewed.');
            }
            
            $submission->update(['status' => 'rejected', 'feedback' => $validated['feedback']]);

            // Refund escrow money to startup
            $task = $submission->application->task;
            $escrow = $task->escrow;
            $moneyMsg = '';
            
            if ($escrow && $escrow->status === 'locked') {
                $startupProfile = $task->startup;
                $startupProfile->increment('wallet_balance', $escrow->amount);
                
                // Update escrow status
                $escrow->update(['status' => 'refunded']);
                
                // Record transaction
                \App\Models\Transaction::create([
                    'user_type' => 'startup',
                    'user_id' => $startupProfile->id,
                    'type' => 'credit',
                    'amount' => $escrow->amount,
                    'description' => "Escrow refunded for rejected task: {$task->title}",
                    'reference_id' => "task_{$task->id}"
                ]);
                
                $moneyMsg = ' Escrow amount refunded to your wallet.';
            }

            Notification::create([
                'user_id' => $submission->application->student->user_id,
                'title' => 'Submission Rejected',
                'message' => 'Your submission was rejected. Please check feedback.',
                'type' => 'warning'
            ]);

            // Recalculate reputation scores
            $reputationService = new \App\Services\ReputationEngineService();
            $reputationService->updateReputation($submission->application->student_profile_id);

            return $moneyMsg;
        });

        return back()->with('success', 'Submission rejected with feedback provided.' . $moneyMessage);
    }

    public function requestRevision(Request $request, $id)
    {
        $validated = $request->validate(['feedback' => 'required|string']);
        
        $submission = Submission::with('application.student')->findOrFail($id);
        $submission->update(['status' => 'revision_requested', 'feedback' => $validated['feedback']]);

        Notification::create([
            'user_id' => $submission->application->student->user_id,
            'title' => 'Revision Requested',
            'message' => 'Please revise your submission based on feedback.',
            'type' => 'info'
        ]);

        return back()->with('success', 'Revision requested');
    }

    public function revise($id)
    {
        $submission = Submission::with('application.task.startup')->findOrFail($id);
        
        // Check if user owns this submission
        if ($submission->application->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if revision is requested
        if ($submission->status !== 'revision_requested') {
            return redirect()->route('dashboard')->with('error', 'This submission is not pending revision.');
        }
        
        return view('submissions.revise', compact('submission'));
    }

    public function updateRevision(Request $request, $id)
    {
        \Log::info('Revision update started', [
            'submission_id' => $id,
            'has_files' => $request->hasFile('files'),
            'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
        ]);

        $validated = $request->validate([
            'content' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|max:10240',
        ]);

        $submission = Submission::findOrFail($id);
        
        // Check if user owns this submission
        if ($submission->application->student_profile_id !== auth()->user()->studentProfile->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('submissions', 'public');
                    $uploadedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getClientMimeType(),
                        'uploaded_at' => now()->toDateTimeString(),
                        'version' => 'revision'
                    ];
                    \Log::info('File uploaded', ['name' => $file->getClientOriginalName(), 'path' => $path]);
                }
            }
        }
        
        // Merge new files with existing files if any
        $existingFiles = is_array($submission->files) ? $submission->files : [];
        // Filter out empty arrays from existing files
        $existingFiles = array_filter($existingFiles, function($file) {
            return is_array($file) && !empty($file) && isset($file['path']);
        });
        
        $allFiles = array_merge($existingFiles, $uploadedFiles);
        
        \Log::info('Files merged', [
            'existing_count' => count($existingFiles),
            'new_count' => count($uploadedFiles),
            'total_count' => count($allFiles)
        ]);
        
        $submission->update([
            'content' => $validated['content'],
            'files' => !empty($allFiles) ? array_values($allFiles) : null,
            'status' => 'submitted',
            'feedback' => null // Clear previous feedback
        ]);

        // Notify startup
        Notification::create([
            'user_id' => $submission->application->task->startup->user_id,
            'title' => 'Submission Revised',
            'message' => 'A student has revised their submission for your review.',
            'type' => 'info'
        ]);

        return redirect()->route('dashboard')->with('success', 'Revision submitted successfully!');
    }

    /**
     * Process startup skill verification after submission acceptance.
     * Startup selects skills, rates proficiency (1-5), and adds optional notes.
     */
    public function verifySkills(Request $request, $id)
    {
        $submission = Submission::with('application.task.skills')->findOrFail($id);

        // Authorize: only the task's startup can verify skills
        $startup = auth()->user()->startupProfile;
        if (!$startup || $submission->application->task->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized.');
        }

        // Submission must be accepted
        if ($submission->status !== 'accepted') {
            return back()->with('error', 'Skills can only be verified for accepted submissions.');
        }

        $validated = $request->validate([
            'skills' => 'required|array|min:1',
            'skills.*.skill_id' => 'required|integer|exists:skills,id',
            'skills.*.rating' => 'required|integer|min:1|max:5',
            'skills.*.notes' => 'nullable|string|max:500',
        ]);

        $service = new \App\Services\SkillVerificationService();
        $service->verifySkillsFromStartupReview(
            $submission->application->student_profile_id,
            $startup->id,
            $submission->application->task_id,
            $validated['skills'],
            'task'
        );

        return back()->with('success', 'Skills verified successfully! The student\'s profile has been updated.');
    }
}
