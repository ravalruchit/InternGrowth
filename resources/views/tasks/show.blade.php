<x-app-layout>
    <div class="ig-container">
        
        <!-- Header & Startup Card -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="lg:col-span-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-[var(--ig-ink)] flex items-center justify-center text-[var(--ig-bg)] ig-display text-lg">
                        {{ strtoupper(substr($task->startup->company_name, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('startups.public-profile', $task->startup->id) }}" class="font-semibold hover:text-[var(--ig-accent)] transition">
                            {{ $task->startup->company_name }}
                        </a>
                        <div class="flex items-center gap-2 mt-0.5">
                            @if($task->startup->is_verified)
                                <span class="ig-chip ig-chip-lime" style="font-size: 10px; padding: 2px 8px;">VERIFIED</span>
                            @endif
                            @php
                                $trustScoreVal = $task->startup->trustScore ? $task->startup->trustScore->overall_score : ($task->startup->credibility_score * 100);
                                if ($trustScoreVal <= 0) $trustScoreVal = 100;
                            @endphp
                            <span class="ig-mono text-[10px] text-[var(--ig-muted)]">🛡️ {{ number_format($trustScoreVal, 0) }}/100 Trust</span>
                        </div>
                    </div>
                </div>
                
                <h1 class="ig-display text-4xl sm:text-5xl md:text-6xl leading-[1.0] text-[var(--ig-ink)]">
                    {{ $task->title }}
                </h1>
            </div>
            
            <div class="lg:col-span-4 lg:text-right flex flex-wrap gap-2 lg:justify-end">
                @if(auth()->check() && auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id)
                    @php
                        $hasApprovedApp = $task->applications()->where('status', 'approved')->count() > 0;
                    @endphp
                    @if(!$hasApprovedApp)
                        <a href="{{ route('tasks.edit', $task->id) }}" class="ig-btn ig-btn-ghost">Edit Task</a>
                    @else
                        <button disabled class="ig-btn opacity-50 cursor-not-allowed border-dashed border-[var(--ig-line-2)]" title="Cannot edit task after approving an application">
                            Edit Task (Locked)
                        </button>
                    @endif
                    @if($task->applications->count() === 0)
                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ig-btn" style="background:var(--ig-rose);color:white;">Delete Task</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
            <!-- Left Panel: Task Details -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Description -->
                <div class="ig-card p-8 ig-reveal">
                    <h2 class="ig-eyebrow mb-4">— Task Description</h2>
                    <div class="text-[var(--ig-ink-2)] text-base leading-relaxed whitespace-pre-line">
                        {{ $task->description }}
                    </div>
                </div>

                <!-- Skills -->
                <div class="ig-card p-8 ig-reveal">
                    <h2 class="ig-eyebrow mb-4">— Required Skills</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($task->skills as $skill)
                            <span class="ig-tag" style="border-style: solid; border-color: var(--ig-line-2)">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Rewards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ig-reveal">
                    <div class="ig-card p-6">
                        <p class="ig-eyebrow mb-2">Reward Points</p>
                        <p class="ig-display text-4xl text-[var(--ig-accent)]">
                            {{ $task->reward_points }} <span class="text-sm font-normal text-[var(--ig-muted)]">points</span>
                        </p>
                    </div>
                    @if($task->stipend)
                        <div class="ig-card p-6">
                            <p class="ig-eyebrow mb-2">Stipend</p>
                            <p class="ig-display text-4xl text-[var(--ig-lime-deep)]">
                                ₹{{ number_format($task->stipend, 0) }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Startup Action / Student Application List (Startup Owner Dashboard View) -->
                @if(auth()->check() && auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id)
                    <div class="mt-8 border-t border-[var(--ig-line)] pt-8 ig-reveal">
                        @include('applications.index', ['applications' => $task->applications, 'task' => $task])
                    </div>
                @endif
            </div>

            <!-- Right Panel: Application Status / AI Recommendation -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                @auth
                    @if(auth()->user()->isStudent())
                        @php
                            $existingApplication = $task->applications->where('student_profile_id', auth()->user()->studentProfile->id)->first();
                            $hasApprovedApplication = $task->applications->where('status', 'approved')->count() > 0;
                            $approvedApplication = $task->applications->where('status', 'approved')->first();
                            $hasAcceptedSubmission = $task->applications->filter(function($app) {
                                return $app->submission && $app->submission->status === 'accepted';
                            })->count() > 0;
                            $acceptedApplication = $task->applications->filter(function($app) {
                                return $app->submission && $app->submission->status === 'accepted';
                            })->first();
                        @endphp
                        
                        @if($hasAcceptedSubmission || $task->status === 'completed')
                            <div class="ig-card-dark p-6 relative overflow-hidden ig-reveal">
                                <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-20" style="background:var(--ig-lime)"></div>
                                <div class="relative">
                                    <span class="ig-chip ig-chip-success mb-3">COMPLETED</span>
                                    <h3 class="ig-display text-2xl text-white">Task Completed</h3>
                                    <p class="text-sm mt-3 leading-relaxed" style="color:#C9C1AE">
                                        This task has been successfully completed by 
                                        <strong>{{ $acceptedApplication ? $acceptedApplication->student->user->name : 'a student' }}</strong>.
                                    </p>
                                </div>
                            </div>
                        @elseif($hasApprovedApplication && !$existingApplication)
                            <div class="ig-card p-6 ig-reveal">
                                <span class="ig-chip ig-chip-warn mb-3">IN PROGRESS</span>
                                <h3 class="ig-display text-2xl">Task Taken</h3>
                                <p class="text-sm mt-3 text-[var(--ig-ink-2)] leading-relaxed">
                                    <strong>{{ $approvedApplication ? $approvedApplication->student->user->name : 'A student' }}</strong> has already been approved for this task. Applications are closed.
                                </p>
                            </div>
                        @elseif($existingApplication)
                            <div class="ig-card p-6 ig-reveal">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="ig-display text-2xl">Your Application</h3>
                                    <a href="{{ route('messages.create', [auth()->user()->studentProfile->id, $task->startup_profile_id, $task->id]) }}" class="w-8 h-8 rounded-full border border-[var(--ig-line)] hover:border-[var(--ig-ink)] flex items-center justify-center transition" title="Message startup">
                                        💬
                                    </a>
                                </div>

                                <div class="mb-5">
                                    @if($existingApplication->status === 'approved')
                                        <span class="ig-chip ig-chip-success">Approved</span>
                                        <p class="text-xs text-green-700 font-semibold mt-2">✓ You've been accepted for this task!</p>
                                    @elseif($existingApplication->status === 'rejected')
                                        <span class="ig-chip ig-chip-danger">Rejected</span>
                                        <p class="text-xs text-[var(--ig-rose)] font-semibold mt-2">✗ Your application was not accepted</p>
                                    @else
                                        <span class="ig-chip ig-chip-warn">Pending</span>
                                        <p class="text-xs text-amber-700 font-semibold mt-2">⏳ Waiting for startup review</p>
                                    @endif
                                </div>

                                @if($existingApplication->cover_letter)
                                    <p class="text-xs text-[var(--ig-muted)] bg-[var(--ig-bg)] rounded-xl p-3 mb-5 leading-relaxed">
                                        <strong>Your message:</strong> {{ Str::limit($existingApplication->cover_letter, 120) }}
                                    </p>
                                @endif

                                @if($existingApplication->status === 'approved')
                                    @php
                                        $submission = $existingApplication->submission;
                                    @endphp
                                    
                                    <div class="border-t border-dashed border-[var(--ig-line)] pt-5">
                                        @if(!$submission)
                                            <div class="bg-[var(--ig-accent-soft)] rounded-xl p-4 border border-[var(--ig-accent)]/20">
                                                <h4 class="font-bold text-[var(--ig-ink)] text-sm mb-1">📝 Ready to submit?</h4>
                                                <p class="text-xs text-[var(--ig-ink-2)] mb-3">Submit your completed work for review.</p>
                                                <a href="{{ route('submissions.create', $existingApplication->id) }}" class="ig-btn ig-btn-accent w-full justify-center" style="padding: 10px; font-size: 13px;">
                                                    <span>Submit work</span><span class="arrow">→</span>
                                                </a>
                                            </div>
                                        @elseif($submission->status === 'pending')
                                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                                                <h4 class="font-bold text-amber-900 text-sm mb-1">⏳ Under Review</h4>
                                                <p class="text-xs text-amber-800">Your submission is waiting for startup review.</p>
                                            </div>
                                        @elseif($submission->status === 'revision_requested')
                                            <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                                                <h4 class="font-bold text-orange-900 text-sm mb-1">🔄 Revision Requested</h4>
                                                <p class="text-xs text-orange-800 mb-2">Changes are needed for your submission.</p>
                                                @if($submission->feedback)
                                                    <p class="text-xs text-orange-950 bg-white p-2 rounded-lg border border-orange-100 mb-3 leading-relaxed">{{ $submission->feedback }}</p>
                                                @endif
                                                <a href="{{ route('submissions.revise', $submission->id) }}" class="ig-btn ig-btn-accent w-full justify-center animate-pulse" style="padding: 10px; font-size: 13px;">
                                                    <span>Revise work</span><span class="arrow">→</span>
                                                </a>
                                            </div>
                                        @elseif($submission->status === 'accepted')
                                            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                                <h4 class="font-bold text-green-900 text-sm mb-1">✅ Work Accepted!</h4>
                                                <p class="text-xs text-green-800">Your submission has been accepted.</p>
                                            </div>
                                        @elseif($submission->status === 'rejected')
                                            <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                                                <h4 class="font-bold text-red-900 text-sm mb-1">❌ Submission Rejected</h4>
                                                @if($submission->feedback)
                                                    <p class="text-xs text-red-950 bg-white p-2 rounded-lg border border-red-100 mb-2">{{ $submission->feedback }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Apply Form -->
                            <div class="ig-card p-6 ig-reveal">
                                <h3 class="ig-display text-2xl mb-4">Apply for Task</h3>
                                <form method="POST" action="{{ route('applications.store', $task->id) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-semibold mb-2 uppercase tracking-wider text-[var(--ig-muted)]">Cover Message (Optional)</label>
                                        <textarea name="cover_letter" rows="4" class="ig-input" placeholder="Explain why you are the best fit for this task..."></textarea>
                                    </div>
                                    <button type="submit" class="ig-btn ig-btn-primary w-full justify-center">
                                        <span>Apply now</span><span class="arrow">→</span>
                                    </button>
                                    <a href="{{ route('messages.create', [auth()->user()->studentProfile->id, $task->startup_profile_id, $task->id]) }}" class="ig-btn ig-btn-ghost w-full justify-center">
                                        💬 Message startup
                                    </a>
                                </form>
                            </div>
                        @endif
                    @endif

                    @if(auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id)
                        <!-- AI Recommended Students -->
                        @if($recommendedStudents && $recommendedStudents->count() > 0)
                            <div class="ig-card-dark p-6 relative overflow-hidden border border-blue-500/20 shadow-xl ig-reveal">
                                <div class="absolute -top-20 -right-20 w-44 h-44 rounded-full blur-3xl opacity-20" style="background:var(--ig-lime)"></div>
                                <div class="relative">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="ig-eyebrow" style="color: #9C9580">— AI Matchmaker</p>
                                            <h3 class="ig-display text-xl text-white mt-1">Recommended Students</h3>
                                        </div>
                                        <span class="ig-chip ig-chip-lime" style="font-size:9.5px;padding:2px 8px;">AI POWERED</span>
                                    </div>

                                    <div class="space-y-4 mt-6">
                                        @foreach($recommendedStudents as $student)
                                            <div class="bg-white/5 border border-white/10 rounded-xl p-4 hover:bg-white/10 transition">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <h4 class="font-semibold text-white text-[15px]">{{ $student->user->name }}</h4>
                                                        <p class="text-xs mt-1" style="color:#C9C1AE">{{ Str::limit($student->bio ?? 'No bio available', 60) }}</p>
                                                        <div class="flex items-center gap-3 mt-3">
                                                            <span class="ig-mono text-[10px]" style="color:#9C9580">Reliability: {{ number_format($student->reliability_score * 100, 0) }}%</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-right flex-shrink-0">
                                                        <span class="ig-stat-num text-lg text-[var(--ig-lime)]">{{ $student->match_score }}%</span>
                                                        <p class="ig-mono text-[9px]" style="color:#9C9580">match</p>
                                                    </div>
                                                </div>
                                                <div class="mt-3 pt-3 border-t border-white/5 text-right">
                                                    <a href="{{ route('students.public-profile', $student->id) }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-[var(--ig-lime)] hover:underline">
                                                        View profile
                                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-center mt-4" style="color:#9C9580">
                                        💡 Pro-tip: Invite these students to apply directly.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                @endauth

            </div>
        </div>
    </div>
</x-app-layout>
