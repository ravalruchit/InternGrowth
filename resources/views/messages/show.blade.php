<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if(auth()->check() && auth()->user()->isStudent())
            <x-reputation-card compact="true" />
        @endif

        <!-- Main Chat Panel -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col h-[600px]">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[var(--ig-accent)] to-[#E03E0B] px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('messages.index') }}" class="text-white hover:opacity-85 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h2 class="text-xl font-black text-white tracking-tight">
                                {{ auth()->user()->isStudent() ? $conversation->startup->company_name : $conversation->student->user->name }}
                            </h2>
                            @if($conversation->task)
                                <p class="text-white/85 text-xs font-semibold">Project Ref: {{ $conversation->task->title }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if(auth()->user()->isStartup())
                        @php
                            $isHired = false;
                            if ($conversation->task_id) {
                                $appForChat = \App\Models\Application::where('task_id', $conversation->task_id)
                                    ->where('student_profile_id', $conversation->student_profile_id)
                                    ->first();
                                if ($appForChat && in_array($appForChat->status, ['hired', 'internship_accepted'])) {
                                    $isHired = true;
                                }
                            }
                        @endphp
                        @if(!$isHired)
                            <button onclick="openScheduleModal()" class="bg-white/20 hover:bg-white/30 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs tracking-wide transition flex items-center space-x-1 border border-white/10 shadow-sm">
                                <span>📅 Schedule Interview</span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>

            @php
                $isContactUnlocked = $conversation->student->contactDetailsUnlockedFor(auth()->user());
            @endphp
            @if($isContactUnlocked)
                <div class="bg-emerald-50 border-b border-emerald-200 px-6 py-3 flex-shrink-0 flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-emerald-800 text-xs font-bold">
                        <span>🎉 Direct Contact Details Unlocked!</span>
                    </div>
                    <button onclick="openContactModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-1.5 px-3 rounded-lg text-xs transition">
                        🔓 View Contact Information
                    </button>
                </div>
            @endif

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50">
                @forelse($conversation->messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} mb-4">
                        <div class="w-full max-w-md">
                            @if($message->type === 'interview' && $message->interview)
                                @php
                                    $interview = $message->interview;
                                    $statusColors = match($interview->status) {
                                        'pending' => 'border-yellow-250 bg-yellow-50/80 shadow-yellow-50/20',
                                        'accepted' => 'border-green-250 bg-green-50/80 shadow-green-50/20',
                                        'completed' => 'border-[var(--ig-accent)]/25 bg-[var(--ig-accent-soft)]/20 shadow-[var(--ig-accent)]/5',
                                        'rejected' => 'border-red-250 bg-red-50/80 shadow-red-50/20',
                                        'cancelled' => 'border-gray-250 bg-gray-50/80 shadow-gray-50/20',
                                        'no_show' => 'border-orange-250 bg-orange-50/80 shadow-orange-50/20',
                                        default => 'border-slate-250 bg-slate-50/80',
                                    };
                                    $badgeStyle = match($interview->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        'accepted' => 'bg-green-100 text-green-800 border border-green-200',
                                        'completed' => 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] border border-[var(--ig-accent)]/20',
                                        'rejected' => 'bg-red-100 text-red-800 border border-red-200',
                                        'cancelled' => 'bg-gray-100 text-gray-850 border border-gray-250',
                                        'no_show' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                        default => 'bg-slate-100 text-slate-800 border border-slate-200',
                                    };
                                @endphp
                                <div class="border {{ $statusColors }} rounded-3xl p-5 shadow-md space-y-4 text-gray-900">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeStyle }} mb-1">
                                                {{ ucfirst($interview->status) }}
                                            </span>
                                            <h4 class="text-base font-extrabold text-gray-950">{{ $interview->title }}</h4>
                                        </div>
                                        <span class="text-[10px] font-extrabold text-[var(--ig-accent)] bg-[var(--ig-accent-soft)] border border-[var(--ig-accent)]/20 px-2 py-0.5 rounded-md">
                                            {{ $interview->duration_minutes }} min
                                        </span>
                                    </div>

                                    <!-- Details -->
                                    <div class="space-y-2 text-xs text-gray-650">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm">📅</span>
                                            <span class="font-bold text-gray-800">
                                                {{ $interview->scheduled_at->format('M d, Y \a\t g:i A') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm">📍</span>
                                            @if($interview->type === 'online')
                                                <a href="{{ Str::startsWith($interview->location, 'http') ? $interview->location : 'https://' . $interview->location }}" target="_blank" class="text-[var(--ig-accent)] hover:underline font-bold flex items-center space-x-0.5">
                                                    <span>Join Online Session</span>
                                                    <span>↗</span>
                                                </a>
                                            @else
                                                <span class="font-semibold text-gray-800">{{ $interview->location }}</span>
                                            @endif
                                        </div>
                                        @if($interview->agenda)
                                            <div class="bg-white border border-gray-150 rounded-xl p-3 mt-2">
                                                <p class="font-bold text-gray-750 text-[10px] uppercase tracking-wider mb-0.5">Agenda & Prep Instructions:</p>
                                                <p class="text-xs leading-relaxed text-gray-600">{{ $interview->agenda }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions / Evaluation Breakdown -->
                                    <div class="pt-3 border-t border-dashed border-gray-250/80">
                                        @if($interview->status === 'completed')
                                            <!-- Completed Stats & Feedback -->
                                            <div class="space-y-3 text-xs">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-500 font-bold uppercase tracking-wider text-[10px]">Interview Outcome:</span>
                                                    @php
                                                        $friendlyOutcome = match($interview->outcome) {
                                                            'proceed_to_offer' => 'Proceed to Offer',
                                                            'keep_in_pipeline' => 'Keep in Pipeline',
                                                            'needs_another_round' => 'Needs Another Round',
                                                            'rejected' => auth()->user()->isStudent() ? 'Needs Improvement' : 'Rejected',
                                                            default => ucwords(str_replace('_', ' ', $interview->outcome)),
                                                        };
                                                        $outcomeBadge = match($interview->outcome) {
                                                            'proceed_to_offer' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                                            'keep_in_pipeline' => 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] border border-[var(--ig-accent)]/20',
                                                            'needs_another_round' => 'bg-yellow-100 text-yellow-850 border border-yellow-200',
                                                            'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                                            default => 'bg-slate-100 text-slate-800 border border-slate-200',
                                                        };
                                                    @endphp
                                                    <span class="font-extrabold px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-widest {{ $outcomeBadge }}">
                                                        {{ $friendlyOutcome }}
                                                    </span>
                                                </div>

                                                @if(auth()->user()->isStartup() || auth()->user()->isAdmin())
                                                    <!-- Raw Scores (Startup/Admin Only) -->
                                                    <div class="bg-white border border-gray-200 rounded-xl p-3.5 space-y-1.5 shadow-inner">
                                                        <div class="flex justify-between items-center pb-1.5 border-b border-gray-100 mb-1.5">
                                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Internal Skill Rating</span>
                                                            <span class="text-[9px] text-[var(--ig-accent)] bg-[var(--ig-accent-soft)] font-bold px-1.5 py-0.5 rounded">Startup & Admin Only</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-650 font-medium">Technical Competency:</span>
                                                            <span class="font-extrabold text-gray-950">{{ $interview->technical_rating }}/10</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-650 font-medium">Communication Quality:</span>
                                                            <span class="font-extrabold text-gray-950">{{ $interview->communication_rating }}/10</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-650 font-medium">Problem Solving Skills:</span>
                                                            <span class="font-extrabold text-gray-950">{{ $interview->problem_solving_rating }}/10</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($interview->feedback_notes)
                                                    <div class="bg-[var(--ig-accent-soft)]/30 border border-[var(--ig-accent)]/15 rounded-xl p-3.5 text-gray-900">
                                                        <p class="font-bold text-[10px] uppercase tracking-wider text-[var(--ig-accent)] mb-1">Feedback Notes:</p>
                                                        <p class="text-xs leading-relaxed font-medium text-slate-800">{{ $interview->feedback_notes }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($interview->status === 'pending')
                                            @if(auth()->user()->isStudent())
                                                <div class="flex items-center space-x-2">
                                                    <form method="POST" action="{{ route('student.interviews.accept', $interview->id) }}" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold py-2 rounded-xl text-xs transition shadow-sm">
                                                            Accept Invitation
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('student.interviews.reject', $interview->id) }}">
                                                        @csrf
                                                        <button type="submit" class="bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded-xl text-xs transition border border-gray-250 shadow-sm">
                                                            Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="flex justify-between items-center text-xs text-gray-555">
                                                    <span>Waiting for student confirmation</span>
                                                    <form method="POST" action="{{ route('startup.interviews.cancel', $interview->id) }}">
                                                        @csrf
                                                        <button type="submit" class="text-red-500 hover:text-red-700 font-extrabold text-xs">
                                                            Cancel Invitation
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @elseif($interview->status === 'accepted')
                                            @if(auth()->user()->isStartup())
                                                <div class="space-y-2">
                                                    <button type="button" onclick="openCompleteModal({{ $interview->id }})" class="w-full bg-gradient-to-r from-[var(--ig-accent)] to-[#E03E0B] hover:shadow-lg text-white font-black py-2.5 px-4 rounded-xl text-xs transition">
                                                        Complete & Evaluate Candidate
                                                    </button>
                                                    <div class="flex items-center justify-between text-xs pt-1">
                                                        <form method="POST" action="{{ route('startup.interviews.noshow', $interview->id) }}" class="flex-1 mr-2">
                                                            @csrf
                                                            <button type="submit" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold py-1.5 rounded-lg transition border border-amber-250">
                                                                Candidate No Show ⚠️
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('startup.interviews.cancel', $interview->id) }}">
                                                            @csrf
                                                            <button type="submit" class="text-gray-500 hover:text-red-600 font-semibold py-1.5 px-2">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="space-y-2">
                                                    <div class="text-xs text-green-700 font-bold flex items-center space-x-1">
                                                        <span>✓ Invitation accepted. Ready for interview.</span>
                                                    </div>
                                                    <form method="POST" action="{{ route('student.interviews.noshow', $interview->id) }}">
                                                        @csrf
                                                        <button type="submit" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold py-1.5 rounded-lg transition border border-amber-250 text-xs">
                                                            Startup No Show ⚠️
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @elseif($interview->status === 'no_show')
                                            <div class="text-xs text-orange-800 bg-orange-50 border border-orange-200 rounded-lg p-2.5 font-semibold">
                                                ⚠️ Candidate marked as No-Show. This has penalty on their performance score.
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-500 font-medium italic">
                                                Interview is {{ $interview->status }}.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Standard text message layout -->
                                <div class="{{ $message->sender_id === auth()->id() ? 'bg-gradient-to-r from-[var(--ig-accent)] to-[#E03E0B] text-white shadow-[var(--ig-accent)]/5' : 'bg-white text-gray-900 border border-gray-150' }} rounded-2xl px-4 py-3 shadow-sm">
                                    <p class="text-xs font-bold mb-1 {{ $message->sender_id === auth()->id() ? 'text-white/80' : 'text-gray-500' }}">{{ $message->sender->name }}</p>
                                    <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                                </div>
                            @endif
                            <p class="text-[10px] text-gray-400 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                {{ $message->created_at->format('M d, g:i A') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <span class="text-4xl block mb-2">💬</span>
                        <p class="text-gray-500 font-bold">No messages yet.</p>
                        <p class="text-gray-400 text-xs mt-1">Start the conversation by typing below!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-gray-150 p-4 bg-white flex-shrink-0">
                <form method="POST" action="{{ route('messages.store', $conversation->id) }}" class="flex space-x-3">
                    @csrf
                    <input type="text" name="message" placeholder="Type your message..." required autocomplete="off"
                        class="flex-1 border-gray-250 rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent">
                    <button type="submit" class="bg-gradient-to-r from-[var(--ig-accent)] to-[#E03E0B] text-white px-7 py-3 rounded-full font-black text-sm hover:shadow-lg transition">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedule Interview Modal (Startup Only) -->
    @if(auth()->user()->isStartup())
        <div id="schedule-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
            <div class="bg-white/95 backdrop-blur-lg border border-[var(--ig-accent)]/25 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
                <button onclick="closeScheduleModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-650 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
                    <span>📅 Schedule Interview</span>
                </h3>
                <p class="text-xs text-gray-500 mb-6">Send an interview invitation to {{ $conversation->student->user->name }}.</p>
                
                <form method="POST" action="{{ route('startup.interviews.store', $conversation->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Interview Title</label>
                        <input type="text" name="title" required placeholder="e.g. Technical Coding Round"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Scheduled At</label>
                            <input type="datetime-local" name="scheduled_at" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Duration (min)</label>
                            <select name="duration_minutes" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                                <option value="15">15 Minutes</option>
                                <option value="30" selected>30 Minutes</option>
                                <option value="45">45 Minutes</option>
                                <option value="60">65 Minutes</option>
                                <option value="90">90 Minutes</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Interview Type</label>
                            <select name="type" required onchange="updateLocationPlaceholder(this.value)"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                                <option value="online" selected>Google Meet / Zoom</option>
                                <option value="phone">Phone call</option>
                                <option value="in_person">In Person / Address</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Location / Contact</label>
                            <input type="text" name="location" id="location-input" required placeholder="Google Meet link or URL"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agenda & Prep Notes</label>
                        <textarea name="agenda" rows="3" placeholder="Explain agenda, topics, coding workspace needed..."
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm"></textarea>
                    </div>
                    
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-sm">
                            Schedule & Send
                        </button>
                        <button type="button" onclick="closeScheduleModal()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl transition text-xs">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Evaluation / Complete Modal (Startup Only) -->
        <div id="complete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
            <div class="bg-white/95 backdrop-blur-lg border border-[var(--ig-accent)]/25 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
                <button onclick="closeCompleteModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-650 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
                    <span>✅ Log Interview Evaluation</span>
                </h3>
                <p class="text-xs text-gray-500 mb-6">Evaluate the student's performance. Detailed ratings (1-10) will be kept private from the student.</p>
                
                <form id="complete-form" method="POST" action="" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Hiring Outcome Decision</label>
                        <select name="outcome" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                            <option value="proceed_to_offer">Proceed to Offer</option>
                            <option value="keep_in_pipeline">Keep in Pipeline</option>
                            <option value="needs_another_round">Needs Another Round</option>
                            <option value="rejected">Rejected (Student view says: "Needs Improvement")</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Technical (1-10)</label>
                            <input type="number" name="technical_rating" required min="1" max="10" value="7"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Communication (1-10)</label>
                            <input type="number" name="communication_rating" required min="1" max="10" value="7"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Problem Solving (1-10)</label>
                            <input type="number" name="problem_solving_rating" required min="1" max="10" value="7"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Feedback & Key Learnings (Shown to student)</label>
                        <textarea name="feedback_notes" rows="4" required placeholder="E.g. Work on API design and database optimization..."
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm"></textarea>
                    </div>
                    
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-sm">
                            Submit Evaluation
                        </button>
                        <button type="button" onclick="closeCompleteModal()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl transition text-xs">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        function openScheduleModal() {
            const modal = document.getElementById('schedule-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }
        function closeScheduleModal() {
            const modal = document.getElementById('schedule-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
        function updateLocationPlaceholder(val) {
            const input = document.getElementById('location-input');
            if (!input) return;
            if (val === 'online') {
                input.placeholder = 'Google Meet link or URL';
            } else if (val === 'phone') {
                input.placeholder = 'e.g. +91 98765 43210';
            } else {
                input.placeholder = 'e.g. Office Address Suite 4B';
            }
        }

        function openCompleteModal(interviewId) {
            const modal = document.getElementById('complete-modal');
            const form = document.getElementById('complete-form');
            if (modal && form) {
                form.action = `/startup/interviews/${interviewId}/complete`;
                modal.classList.remove('hidden');
            }
        }
        function closeCompleteModal() {
            const modal = document.getElementById('complete-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
        function openContactModal() {
            const modal = document.getElementById('contact-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }
        function closeContactModal() {
            const modal = document.getElementById('contact-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>

    @if(isset($isContactUnlocked) && $isContactUnlocked)
        <!-- Contact Information Modal -->
        <div id="contact-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
            <div class="bg-white/95 backdrop-blur-lg border border-emerald-100 rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
                <button onclick="closeContactModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-650 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h3 class="text-2xl font-black text-gray-900 mb-4 font-poppins flex items-center space-x-2">
                    <span>🔓 Direct Contact Details</span>
                </h3>
                
                <div class="space-y-4 text-sm">
                    @if(auth()->user()->isStudent())
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">Startup / Company Name</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $conversation->startup->company_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">Founder Email</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $conversation->startup->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">Contact Phone</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $conversation->startup->contact_phone ?? 'N/A' }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">Candidate Name</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $conversation->student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">Primary Email</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $conversation->student->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">College Email</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $conversation->student->college_email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-gray-400">Contact Phone</p>
                            <p class="font-semibold text-gray-800 mt-0.5">+91 98765 43210</p>
                        </div>
                        @if($conversation->student->portfolio_links && count($conversation->student->portfolio_links) > 0)
                            <div>
                                <p class="text-xs uppercase font-bold text-gray-400 mb-1">Portfolio & Social Links</p>
                                <div class="space-y-1">
                                    @foreach($conversation->student->portfolio_links as $link)
                                        @if($link)
                                            <a href="{{ $link }}" target="_blank" class="text-[var(--ig-accent)] hover:underline block truncate">{{ $link }}</a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                    <p class="text-[10px] text-gray-500 italic">Please use these details to coordinate communication professionally.</p>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
