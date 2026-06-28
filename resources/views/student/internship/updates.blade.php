<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Internship Workplace</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Internship <span class="ig-serif text-[var(--ig-accent)]">Workspace.</span><br>
                    Post progress logs and weekly reports.
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-2 uppercase tracking-wider">
                    Offer ID: {{ $offer->id }} | Startup: {{ $offer->startup->company_name }} | Role: {{ $offer->role }}
                </p>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('student.dashboard') }}" class="ig-btn ig-btn-ghost">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Banners -->
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: Form inputs -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Submit Progress Log Form -->
                <div class="ig-card p-6">
                    <h2 class="ig-display text-xl text-[var(--ig-ink)] mb-4">Post Progress Log</h2>
                    <form method="POST" action="{{ route('student.internship.updates.store', $offer->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Update Title</label>
                            <input type="text" name="title" required placeholder="e.g. Implemented checkout flows" 
                                   class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Log Description / Blockers</label>
                            <textarea name="description" required placeholder="Detail the features you completed, packages installed, or docker issues faced today..." rows="3"
                                      class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">GitHub URL (Optional)</label>
                                <input type="url" name="github_url" placeholder="https://github.com/..." 
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs"></small>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Demo Link (Optional)</label>
                                <input type="url" name="demo_url" placeholder="https://..." 
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold py-2 px-4 rounded-xl text-xs transition text-center shadow-sm">
                            Post Progress Update Log
                        </button>
                    </form>
                </div>

                <!-- Submit Weekly Report Form -->
                <div class="ig-card p-6">
                    <h2 class="ig-display text-xl text-[var(--ig-ink)] mb-4">Submit Weekly Progress Report</h2>
                    <form method="POST" action="{{ route('student.internship.reports.store', $offer->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Week Number</label>
                            <input type="number" name="week_number" required value="{{ $nextWeekNumber }}" min="1"
                                   class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Tasks Completed</label>
                            <textarea name="tasks_completed" required placeholder="Summarize your main achievements this week..." rows="3"
                                      class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Challenges & Blocker issues</label>
                            <textarea name="challenges" required placeholder="What blocked you or required major research..." rows="2"
                                      class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Plans & Goals for Next Week</label>
                            <textarea name="next_week_goals" required placeholder="What are your key deliverables for the upcoming week..." rows="2"
                                      class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">GitHub URL (Optional)</label>
                                <input type="url" name="github_url" placeholder="https://github.com/..." 
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Demo Link (Optional)</label>
                                <input type="url" name="demo_url" placeholder="https://..." 
                                       class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2 px-4 rounded-xl text-xs transition text-center shadow-sm">
                            Submit Week Report for Evaluation
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Logs and reports review -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Weekly Reports Tabulating -->
                <div class="ig-card">
                    <h2 class="ig-display text-xl text-[var(--ig-ink)] mb-4">Weekly Evaluation History</h2>
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
                                <p class="text-xs text-gray-800 line-clamp-2"><strong class="text-gray-500">Done:</strong> {{ $report->tasks_completed }}</p>
                                
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

                <!-- Daily log updates log -->
                <div class="ig-card">
                    <h2 class="ig-display text-xl text-[var(--ig-ink)] mb-4">Work Progress Log History</h2>
                    <div class="divide-y divide-gray-150">
                        @forelse($offer->updates as $update)
                            <div class="py-4 first:pt-0 last:pb-0">
                                <h3 class="font-bold text-sm text-gray-800">{{ $update->title }}</h3>
                                <p class="text-[9px] text-gray-400 font-medium">{{ $update->created_at->format('M d, Y @ H:i') }}</p>
                                <p class="text-xs text-gray-650 mt-1.5 whitespace-pre-line">{{ $update->description }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-[var(--ig-muted)] py-4 text-center">No updates submitted yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
