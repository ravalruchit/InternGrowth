<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Interview;
use App\Models\Message;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function store(Request $request, $conversationId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:5|max:240',
            'type' => 'required|string|in:online,phone,in_person',
            'location' => 'required|string|max:255',
            'agenda' => 'nullable|string|max:2000',
            'agreement' => 'required|accepted',
        ], [
            'agreement.accepted' => 'You must agree to process hiring through InternGrowth to schedule an interview.'
        ]);

        // Validate contact leaks
        \App\Helpers\ContactDetector::validate($validated['title'], 'title');
        \App\Helpers\ContactDetector::validate($validated['location'], 'location');
        if (!empty($validated['agenda'])) {
            \App\Helpers\ContactDetector::validate($validated['agenda'], 'agenda');
        }

        $conversation = Conversation::findOrFail($conversationId);
        
        $startup = auth()->user()->startupProfile;
        if (!$startup || $conversation->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized conversation access.');
        }

        // Create Interview record
        $interview = Interview::create([
            'conversation_id' => $conversation->id,
            'startup_profile_id' => $conversation->startup_profile_id,
            'student_profile_id' => $conversation->student_profile_id,
            'task_id' => $conversation->task_id,
            'title' => $validated['title'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
            'type' => $validated['type'],
            'location' => $validated['location'],
            'agenda' => $validated['agenda'] ?? null,
            'status' => 'pending',
        ]);

        // Sync the application workflow
        if ($conversation->task_id) {
            $application = \App\Models\Application::where('task_id', $conversation->task_id)
                ->where('student_profile_id', $conversation->student_profile_id)
                ->first();
            if ($application) {
                $application->update([
                    'agreement_accepted' => true,
                    'agreement_accepted_at' => now(),
                    'agreement_ip' => $request->ip(),
                    'startup_hiring_outcome' => 'interview_scheduled',
                    'status' => 'interview'
                ]);
            }
        }

        // Create Chat message invitation card
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'type' => 'interview',
            'interview_id' => $interview->id,
            'message' => '📅 Interview Invitation: ' . $interview->title,
        ]);

        $conversation->touch();

        return back()->with('success', 'Interview scheduled and invitation card sent in chat.');
    }

    public function accept($id)
    {
        $interview = Interview::findOrFail($id);
        $student = auth()->user()->studentProfile;

        if (!$student || $interview->student_profile_id !== $student->id) {
            abort(403, 'Unauthorized.');
        }

        if ($interview->status !== 'pending') {
            return back()->with('error', 'This interview has already been updated.');
        }

        $interview->update(['status' => 'accepted']);

        // Send System message
        Message::create([
            'conversation_id' => $interview->conversation_id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => "🎓 I have accepted the interview invitation for '{$interview->title}'.",
        ]);

        $interview->conversation->touch();

        return back()->with('success', 'You have accepted the interview invitation.');
    }

    public function reject($id)
    {
        $interview = Interview::findOrFail($id);
        $student = auth()->user()->studentProfile;

        if (!$student || $interview->student_profile_id !== $student->id) {
            abort(403, 'Unauthorized.');
        }

        if ($interview->status !== 'pending') {
            return back()->with('error', 'This interview has already been updated.');
        }

        $interview->update(['status' => 'rejected']);

        // Send System message
        Message::create([
            'conversation_id' => $interview->conversation_id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => "❌ I have declined the interview invitation for '{$interview->title}'.",
        ]);

        $interview->conversation->touch();

        return back()->with('success', 'You have declined the interview invitation.');
    }

    public function cancel($id)
    {
        $interview = Interview::findOrFail($id);
        $startup = auth()->user()->startupProfile;

        if (!$startup || $interview->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized.');
        }

        if (!in_array($interview->status, ['pending', 'accepted'])) {
            return back()->with('error', 'This interview cannot be cancelled.');
        }

        $interview->update(['status' => 'cancelled']);

        // Send System message
        Message::create([
            'conversation_id' => $interview->conversation_id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => "🚫 We have cancelled the scheduled interview for '{$interview->title}'.",
        ]);

        $interview->conversation->touch();

        return back()->with('success', 'Interview invitation cancelled.');
    }

    public function complete(Request $request, $id)
    {
        $validated = $request->validate([
            'outcome' => 'required|string|in:strong_candidate,proceed_to_offer,keep_in_pipeline,needs_another_round,rejected',
            'technical_rating' => 'required|integer|min:1|max:10',
            'communication_rating' => 'required|integer|min:1|max:10',
            'problem_solving_rating' => 'required|integer|min:1|max:10',
            'feedback_notes' => 'nullable|string|max:2000',
        ]);

        if (!empty($validated['feedback_notes'])) {
            \App\Helpers\ContactDetector::validate($validated['feedback_notes'], 'feedback_notes');
        }

        $interview = Interview::findOrFail($id);
        $startup = auth()->user()->startupProfile;

        if (!$startup || $interview->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized.');
        }

        if ($interview->status !== 'accepted') {
            return back()->with('error', 'Only accepted/scheduled interviews can be marked complete.');
        }

        $interview->update([
            'status' => 'completed',
            'outcome' => $validated['outcome'],
            'technical_rating' => $validated['technical_rating'],
            'communication_rating' => $validated['communication_rating'],
            'problem_solving_rating' => $validated['problem_solving_rating'],
            'feedback_notes' => $validated['feedback_notes'] ?? null,
        ]);

        // Update application workflow
        if ($interview->task_id) {
            $application = \App\Models\Application::where('task_id', $interview->task_id)
                ->where('student_profile_id', $interview->student_profile_id)
                ->first();
            if ($application) {
                if (in_array($validated['outcome'], ['strong_candidate', 'proceed_to_offer', 'keep_in_pipeline'])) {
                    $application->update([
                        'startup_hiring_outcome' => 'interview_passed'
                    ]);
                } elseif ($validated['outcome'] === 'rejected') {
                    $application->update([
                        'status' => 'rejected',
                        'startup_hiring_outcome' => 'interview_failed'
                    ]);
                }
            }
        }

        // Recalculate student reputation score
        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($interview->student_profile_id);

        // Send System message
        $outcomeText = ucwords(str_replace('_', ' ', $validated['outcome']));
        Message::create([
            'conversation_id' => $interview->conversation_id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => "✅ Interview completed. Performance evaluation logged. Outcome: {$outcomeText}.",
        ]);

        $interview->conversation->touch();

        return back()->with('success', 'Interview completed and feedback saved.');
    }

    public function noShow($id)
    {
        $interview = Interview::findOrFail($id);
        $startup = auth()->user()->startupProfile;

        if (!$startup || $interview->startup_profile_id !== $startup->id) {
            abort(403, 'Unauthorized.');
        }

        if (!in_array($interview->status, ['accepted', 'pending'])) {
            return back()->with('error', 'Cannot mark this interview as no show.');
        }

        $interview->update([
            'status' => 'no_show',
            'no_show_by' => 'student'
        ]);

        // Sync the application outcome if it exists
        if ($interview->task_id) {
            $application = \App\Models\Application::where('task_id', $interview->task_id)
                ->where('student_profile_id', $interview->student_profile_id)
                ->first();
            if ($application) {
                $application->update([
                    'startup_hiring_outcome' => 'interview_failed',
                    'status' => 'rejected'
                ]);
            }
        }

        // Recalculate student reputation score
        $reputationService = new \App\Services\ReputationEngineService();
        $reputationService->updateReputation($interview->student_profile_id);

        // Send System message
        Message::create([
            'conversation_id' => $interview->conversation_id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => "⚠️ Candidate marked as NO SHOW for scheduled interview: '{$interview->title}'.",
        ]);

        $interview->conversation->touch();

        return back()->with('success', 'Candidate marked as no-show.');
    }

    public function studentNoShow($id)
    {
        $interview = Interview::findOrFail($id);
        $student = auth()->user()->studentProfile;

        if (!$student || $interview->student_profile_id !== $student->id) {
            abort(403, 'Unauthorized.');
        }

        if (!in_array($interview->status, ['accepted', 'pending'])) {
            return back()->with('error', 'Cannot mark this interview as no show.');
        }

        $interview->update([
            'status' => 'no_show',
            'no_show_by' => 'startup'
        ]);

        // Recalculate startup reputation score
        $startupReputationService = new \App\Services\StartupReputationService();
        $startupReputationService->updateReputation($interview->startup_profile_id);

        // Send System message
        Message::create([
            'conversation_id' => $interview->conversation_id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => "⚠️ Startup marked as NO SHOW for scheduled interview: '{$interview->title}'.",
        ]);

        $interview->conversation->touch();

        return back()->with('success', 'Startup marked as no-show.');
    }
}
