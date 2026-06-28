<x-app-layout>
    <div class="ig-container py-10 max-w-4xl">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 border-b border-[var(--ig-line)] pb-6 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Weekly Progress Hub</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    Weekly Reports of <span class="ig-serif text-[var(--ig-accent)]">{{ $offer->student->user->name }}</span>
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1 uppercase tracking-wider">
                    Internship Evaluation Dashboard
                </p>
            </div>
            <div>
                <a href="{{ route('startup.team.index') }}" class="ig-btn ig-btn-ghost text-xs py-2">
                    <span>← Back to Team</span>
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

        <!-- Reports List -->
        <div class="space-y-8">
            @forelse($offer->weeklyReports as $report)
                <div class="ig-card p-6 md:p-8 hover:shadow-md transition duration-300">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 border-b border-[var(--ig-line)] pb-4">
                        <div>
                            <span class="ig-chip ig-chip-accent uppercase tracking-wider font-extrabold text-[9px] mb-1">Week {{ $report->week_number }}</span>
                            <h3 class="font-bold text-xl text-[var(--ig-ink)] mt-1">Weekly Summary</h3>
                            <p class="text-[10px] text-[var(--ig-muted)] font-semibold mt-0.5">Submitted: {{ $report->created_at->format('d M, Y') }}</p>
                        </div>
                        <div>
                            @if($report->rating)
                                <div class="text-right">
                                    <span class="inline-block bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black py-1 px-3 rounded-full">
                                        ⭐ {{ $report->rating }} / 5.0 Rated
                                    </span>
                                </div>
                            @else
                                <span class="inline-block bg-amber-50 border border-amber-200 text-amber-700 text-xs font-black py-1 px-3 rounded-full">
                                    ⏳ Pending Evaluation
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Report Body sections -->
                    <div class="space-y-5 text-sm">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">— Tasks Completed</p>
                            <p class="text-[var(--ig-ink-2)] mt-1 font-semibold leading-relaxed whitespace-pre-line">{{ $report->tasks_completed }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">— Challenges Faced</p>
                            <p class="text-[var(--ig-ink-2)] mt-1 font-semibold leading-relaxed whitespace-pre-line">{{ $report->challenges }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">— Next Week's Goals</p>
                            <p class="text-[var(--ig-ink-2)] mt-1 font-semibold leading-relaxed whitespace-pre-line">{{ $report->next_week_goals }}</p>
                        </div>

                        @if($report->github_url || $report->demo_url)
                            <div class="flex flex-wrap gap-3 pt-2">
                                @if($report->github_url)
                                    <a href="{{ $report->github_url }}" target="_blank" class="text-xs font-extrabold text-slate-900 hover:underline flex items-center gap-1">
                                        🐙 Repo Link
                                    </a>
                                @endif
                                @if($report->demo_url)
                                    <a href="{{ $report->demo_url }}" target="_blank" class="text-xs font-extrabold text-[var(--ig-accent)] hover:underline flex items-center gap-1">
                                        🔗 Demo Link
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Evaluation Section -->
                    <div class="mt-6 pt-6 border-t border-[var(--ig-line)]">
                        @if($report->rating)
                            <div class="bg-emerald-50/20 border border-emerald-100/50 rounded-2xl p-5">
                                <p class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider">— Startup Review & Feedback</p>
                                <p class="text-sm font-semibold text-gray-800 mt-2 leading-relaxed">"{{ $report->startup_feedback }}"</p>
                            </div>
                        @else
                            <!-- Form to submit evaluation -->
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                                <h4 class="font-bold text-base text-[var(--ig-ink)] mb-4">Evaluate Report Progress</h4>
                                <form method="POST" action="{{ route('startup.team.reports.feedback', $report->id) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Score Rating (1-5 Stars)</label>
                                        <select name="rating" required class="w-full md:w-1/3 px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent">
                                            <option value="5">⭐⭐⭐⭐⭐ Excellent (5/5)</option>
                                            <option value="4" selected>⭐⭐⭐⭐ Good (4/5)</option>
                                            <option value="3">⭐⭐⭐ Average (3/5)</option>
                                            <option value="2">⭐⭐ Poor (2/5)</option>
                                            <option value="1">⭐ Unacceptable (1/5)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Feedback Message</label>
                                        <textarea name="startup_feedback" placeholder="Provide constructive criticism, praise, or next-step targets for the student..." rows="3" required
                                                  class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                                    </div>
                                    <button type="submit" class="bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold text-xs py-2 px-6 rounded-xl transition shadow-sm">
                                        Submit Evaluation
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                    <p class="font-bold text-base text-[var(--ig-ink)]">No weekly reports submitted yet.</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Student weekly reports will show here once submitted at the end of active weeks.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
