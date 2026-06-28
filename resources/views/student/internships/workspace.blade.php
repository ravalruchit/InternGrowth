<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Internship Workspace</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    {{ $offer->role ?: 'Software Engineer' }} Workspace
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1.5 uppercase tracking-wider">
                    Startup: <span class="font-bold text-[var(--ig-ink)]">{{ $offer->startup->company_name }}</span> | Status: {{ $offer->status }}
                </p>
            </div>
            <div>
                <a href="{{ route('student.internships.index') }}" class="ig-btn ig-btn-ghost text-xs">
                    <span>← Back to My Internships</span>
                </a>
            </div>
        </div>

        <!-- Session Flash Messages -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-8 text-sm">
                <p class="font-bold text-emerald-950">✓ {{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-955 font-bold shadow-sm">
                <p>⚠️ {{ session('error') }}</p>
            </div>
        @endif

        <div x-data="{ activeTab: 'overview' }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Navigation Sidebar -->
            <div class="lg:col-span-3 space-y-2">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-gray-600 hover:bg-slate-50'" class="w-full text-left px-4 py-3 rounded-xl text-sm transition font-semibold flex items-center gap-2">
                    📊 Overview
                </button>
                <button @click="activeTab = 'mission'" :class="activeTab === 'mission' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-gray-600 hover:bg-slate-50'" class="w-full text-left px-4 py-3 rounded-xl text-sm transition font-semibold flex items-center gap-2">
                    🎯 Mission Board
                </button>
                <button @click="activeTab = 'reports'" :class="activeTab === 'reports' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-gray-600 hover:bg-slate-50'" class="w-full text-left px-4 py-3 rounded-xl text-sm transition font-semibold flex items-center gap-2">
                    📝 Weekly Reports
                </button>
                <button @click="activeTab = 'messages'" :class="activeTab === 'messages' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-gray-600 hover:bg-slate-50'" class="w-full text-left px-4 py-3 rounded-xl text-sm transition font-semibold flex items-center gap-2">
                    💬 Messages
                </button>
                <button @click="activeTab = 'resources'" :class="activeTab === 'resources' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-gray-600 hover:bg-slate-50'" class="w-full text-left px-4 py-3 rounded-xl text-sm transition font-semibold flex items-center gap-2">
                    🗂️ Resources
                </button>
                <button @click="activeTab = 'certificates'" :class="activeTab === 'certificates' ? 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold' : 'text-gray-600 hover:bg-slate-50'" class="w-full text-left px-4 py-3 rounded-xl text-sm transition font-semibold flex items-center gap-2">
                    🎖️ Certificates
                </button>
            </div>

            <!-- Right Content Panels -->
            <div class="lg:col-span-9 bg-white border border-[var(--ig-line)] rounded-3xl p-6 md:p-8 shadow-sm">
                
                <!-- Tab: Overview -->
                <div x-show="activeTab === 'overview'" class="space-y-8">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Workspace Overview</h2>
                    
                    <!-- Progress Bar -->
                    <div class="space-y-2 pt-2">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-gray-500">Contract Progress</span>
                            <span class="text-[var(--ig-accent)] font-bold">{{ $offer->progress_pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3">
                            <div class="bg-gradient-to-r from-[var(--ig-accent-soft)] to-[var(--ig-accent)] h-3 rounded-full transition-all duration-500" style="width: {{ $offer->progress_pct }}%"></div>
                        </div>
                    </div>

                    <!-- Gamified Streaks Widget -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 border border-slate-200/60 rounded-2xl p-6">
                        <div class="space-y-3">
                            <p class="text-xs uppercase font-extrabold text-[var(--ig-muted)] tracking-widest">— Streak Status</p>
                            <h3 class="text-3xl font-black text-gray-900 font-poppins flex items-center gap-2">
                                🔥 Current Streak: {{ $offer->current_streak }} days
                            </h3>
                            <p class="text-xs text-[var(--ig-muted)]">Check in daily to build code visibility and earn bonus platform reputation points.</p>
                            
                            <div class="pt-2">
                                @if($canCheckInToday)
                                    <form method="POST" action="{{ route('student.internships.checkin', $offer->id) }}">
                                        @csrf
                                        <button type="submit" class="ig-btn bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs px-6 py-2.5 rounded-xl transition border-none cursor-pointer">
                                            ✔ Worked Today
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="ig-btn bg-emerald-50 text-emerald-600 font-extrabold text-xs px-6 py-2.5 rounded-xl border border-emerald-200/50 cursor-not-allowed flex items-center gap-1">
                                        🟢 Checked In Today! (Streak active)
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6 flex flex-col justify-between">
                            <div>
                                <p class="text-xs uppercase font-extrabold text-slate-500 tracking-widest">— Next Milestone</p>
                                <p class="text-sm font-bold text-gray-800 mt-2">
                                    {{ 10 - ($offer->current_streak % 10) }} days to next milestone
                                </p>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">10-day milestone streak adds <strong class="text-[var(--ig-accent)]">+25 IPRS Reputation Points</strong>.</p>
                            </div>
                            <div class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl p-2.5">
                                🎁 Reward: Priority profile visibility on Discovery search.
                            </div>
                        </div>
                    </div>

                    <!-- Meta Data Details -->
                    <div class="grid grid-cols-3 gap-6 text-xs bg-slate-50 border border-slate-100 rounded-xl p-4">
                        <div>
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Stipend Rate</p>
                            <p class="text-gray-900 font-extrabold mt-0.5">₹{{ number_format($offer->compensation, 0) }} / {{ $offer->compensation_period }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Start Date</p>
                            <p class="text-gray-900 font-extrabold mt-0.5">{{ $offer->start_date->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Expected End</p>
                            <p class="text-gray-900 font-extrabold mt-0.5">{{ $offer->end_date ? $offer->end_date->format('d M, Y') : 'Ongoing' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tab: Mission Board -->
                <div x-show="activeTab === 'mission'" class="space-y-6" style="display: none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Mission Board</h2>
                    
                    <!-- Progress Submit Form -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                        <h4 class="font-bold text-base text-[var(--ig-ink)] mb-3">Post Progress Updates</h4>
                        <form method="POST" action="{{ route('student.internship.updates.store', $offer->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Update Title</label>
                                <input type="text" name="title" required placeholder="e.g. Connected PostgreSQL models" 
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Work Description / Blockers</label>
                                <textarea name="description" required placeholder="Describe what components you completed or package version details today..." rows="3"
                                          class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">GitHub Link (Optional)</label>
                                    <input type="url" name="github_url" placeholder="https://github.com/..." 
                                           class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Demo Link (Optional)</label>
                                    <input type="url" name="demo_url" placeholder="https://..." 
                                           class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs">
                                </div>
                            </div>
                            <button type="submit" class="bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold text-xs py-2 px-6 rounded-xl transition shadow-sm">
                                Submit Progress Log
                            </button>
                        </form>
                    </div>

                    <!-- Updates Log History -->
                    <div>
                        <h4 class="font-bold text-base text-[var(--ig-ink)] mb-4">Activity Logs Timeline</h4>
                        <div class="divide-y divide-gray-150">
                            @forelse($offer->updates as $update)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <h5 class="font-bold text-sm text-gray-800">{{ $update->title }}</h5>
                                    <p class="text-[9px] text-gray-400 font-medium">{{ $update->created_at->format('M d, Y @ H:i') }}</p>
                                    <p class="text-xs text-gray-650 mt-1.5 whitespace-pre-line">{{ $update->description }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-[var(--ig-muted)] py-4 text-center">No logs recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Weekly Reports -->
                <div x-show="activeTab === 'reports'" class="space-y-6" style="display: none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Weekly Progress Reports</h2>
                    
                    <!-- Report Submit Form -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                        <h4 class="font-bold text-base text-[var(--ig-ink)] mb-3">Submit Weekly Report</h4>
                        <form method="POST" action="{{ route('student.internship.reports.store', $offer->id) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Week Number</label>
                                <input type="number" name="week_number" required value="{{ $nextWeekNumber }}" min="1"
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Completed Deliverables</label>
                                <textarea name="tasks_completed" required placeholder="Summarize your main achievements this week..." rows="3"
                                          class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Blockers / Challenges</label>
                                <textarea name="challenges" required placeholder="What blocked you or required major research..." rows="2"
                                          class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Goals for Next Week</label>
                                <textarea name="next_week_goals" required placeholder="What are your key deliverables for the upcoming week..." rows="2"
                                          class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                            </div>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs py-2.5 px-6 rounded-xl transition shadow-sm border-none cursor-pointer">
                                Submit Week Report for Review
                            </button>
                        </form>
                    </div>

                    <!-- Evaluation History -->
                    <div>
                        <h4 class="font-bold text-base text-[var(--ig-ink)] mb-4">Evaluations History</h4>
                        <div class="space-y-4">
                            @forelse($offer->weeklyReports as $report)
                                <div class="border border-gray-200 rounded-2xl p-4 bg-slate-50/50">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="ig-chip ig-chip-accent font-extrabold text-[8px] uppercase tracking-wider">Week {{ $report->week_number }}</span>
                                        @if($report->rating)
                                            <span class="text-xs font-bold text-emerald-700">⭐ {{ $report->rating }}/5 Rated</span>
                                        @else
                                            <span class="text-xs text-gray-400">⏳ Pending Rating</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-800"><strong class="text-gray-500">Done:</strong> {{ $report->tasks_completed }}</p>
                                    
                                    @if($report->startup_feedback)
                                        <div class="mt-3 p-3 bg-white border border-slate-100 rounded-xl text-xs text-slate-700 italic">
                                            <strong class="text-slate-500 font-bold block not-italic uppercase tracking-widest text-[8px] mb-1">— Startup Review</strong>
                                            "{{ $report->startup_feedback }}"
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-[var(--ig-muted)] py-4 text-center">No weekly reports submitted yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Messages -->
                <div x-show="activeTab === 'messages'" class="space-y-6" style="display: none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Communication</h2>
                    <p class="text-xs text-[var(--ig-muted)]">Maintain active messaging channels with the founder and developers.</p>
                    
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
                        <p class="text-sm font-semibold text-gray-850 mb-4">Start or open chat thread with {{ $offer->startup->company_name }} team</p>
                        @if($conversationId)
                            <a href="{{ route('messages.show', $conversationId) }}" class="ig-btn bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold text-xs py-2.5 px-6 rounded-xl transition inline-block shadow-sm">
                                💬 Open Message Channel
                            </a>
                        @else
                            <a href="{{ route('messages.create', ['studentId' => $offer->student_profile_id, 'startupId' => $offer->startup_profile_id, 'taskId' => $offer->source_task_id ?? '']) }}" class="ig-btn bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold text-xs py-2.5 px-6 rounded-xl transition inline-block shadow-sm">
                                💬 Start New Message Thread
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Tab: Resources -->
                <div x-show="activeTab === 'resources'" class="space-y-6" style="display: none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Resources & Deliverables</h2>
                    <div class="divide-y divide-gray-150">
                        @forelse($offer->weeklyReports->whereNotNull('github_url') as $report)
                            <div class="py-3 flex justify-between items-center text-xs">
                                <div>
                                    <p class="font-extrabold text-gray-900">Week {{ $report->week_number }} Submission Deliverables</p>
                                    <p class="text-slate-400 mt-0.5">Submitted code repositories</p>
                                </div>
                                <div class="flex gap-2">
                                    @if($report->github_url)
                                        <a href="{{ $report->github_url }}" target="_blank" class="text-xs text-slate-800 font-extrabold hover:underline">
                                            🐙 GitHub
                                        </a>
                                    @endif
                                    @if($report->demo_url)
                                        <a href="{{ $report->demo_url }}" target="_blank" class="text-xs text-[var(--ig-accent)] font-extrabold hover:underline">
                                            🔗 Live Demo
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-[var(--ig-muted)] py-4 text-center">No resources recorded yet. Links from your logs/reports will gather here.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Tab: Certificates -->
                <div x-show="activeTab === 'certificates'" class="space-y-6" style="display: none;">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">Verified Placement Certificates</h2>
                    
                    @php
                        $certificate = \App\Models\Certificate::where('hiring_offer_id', $offer->id)->first();
                    @endphp
                    @if($certificate)
                        <div class="bg-indigo-50/30 border border-indigo-100 rounded-2xl p-6 text-center space-y-4">
                            <span class="text-4xl">🎖️</span>
                            <div>
                                <h4 class="font-bold text-lg text-indigo-950">Experience Certificate Issued</h4>
                                <p class="text-xs text-indigo-800/80 mt-1">Certificate Number: {{ $certificate->certificate_number }}</p>
                                <p class="text-xs text-indigo-800/80">Issued at: {{ $certificate->issued_at->format('d M, Y') }}</p>
                            </div>
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('certificates.verify', $certificate->certificate_number) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs py-2 px-6 rounded-xl transition shadow-sm">
                                    View Credentials
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center text-[var(--ig-muted)]">
                            <p class="text-sm font-semibold text-gray-800 mb-1">No certificate issued yet.</p>
                            <p class="text-xs text-gray-500">Your certificate will be generated automatically once the startup marks this internship as completed.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
