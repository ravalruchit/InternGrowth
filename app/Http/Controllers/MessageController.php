<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesPlatformAccess;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use AuthorizesPlatformAccess;

    public function index()
    {
        $user = auth()->user();
        
        if ($user->isStudent()) {
            $conversations = Conversation::where('student_profile_id', $user->studentProfile->id)
                ->with(['startup.user', 'latestMessage', 'task'])
                ->latest('updated_at')
                ->get();
        } else {
            $conversations = Conversation::where('startup_profile_id', $user->startupProfile->id)
                ->with(['student.user', 'latestMessage', 'task'])
                ->latest('updated_at')
                ->get();
        }

        return view('messages.index', compact('conversations'));
    }

    public function show($id)
    {
        $conversation = Conversation::with(['messages.sender', 'student.user', 'startup.user', 'task'])
            ->findOrFail($id);

        $this->authorizeConversationAccess($conversation);

        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('conversation'));
    }

    public function store(Request $request, $conversationId)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        $this->authorizeConversationAccess($conversation);

        $unlocked = false;
        $acceptedOfferExists = \App\Models\HiringOffer::where('student_profile_id', $conversation->student_profile_id)
            ->where('startup_profile_id', $conversation->startup_profile_id)
            ->whereIn('status', ['pending_joining', 'joined', 'completed'])
            ->exists();
        if ($acceptedOfferExists) {
            $unlocked = true;
        }

        if (!$unlocked && $conversation->task_id) {
            $application = \App\Models\Application::where('student_profile_id', $conversation->student_profile_id)
                ->where('task_id', $conversation->task_id)
                ->first();
            if ($application && $application->contactDetailsUnlocked()) {
                $unlocked = true;
            }
        }

        if (!$unlocked) {
            \App\Helpers\ContactDetector::validate($validated['message'], 'message');
        }
        
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $conversation->touch();

        return back()->with('message_sent', 'Message sent');
    }

    public function create($studentId, $startupId, $taskId = null)
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            if ((int) $studentId !== $user->studentProfile->id) {
                abort(403, 'Unauthorized action.');
            }
        } elseif ($user->isStartup()) {
            if ((int) $startupId !== $user->startupProfile->id) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        $conversation = Conversation::firstOrCreate([
            'student_profile_id' => $studentId,
            'startup_profile_id' => $startupId,
            'task_id' => $taskId,
        ]);

        return redirect()->route('messages.show', $conversation->id);
    }
}
