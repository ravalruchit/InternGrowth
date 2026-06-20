<x-app-layout>
    <div class="ig-container">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Analytics & Resume Studio</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Performance <span class="ig-serif text-[var(--ig-accent)]">Metrics.</span><br>
                    Track your growth & print receipts.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('student.cv.download') }}" class="ig-btn ig-btn-primary">
                    <svg class="w-4.5 h-4.5" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download CV</span>
                    <span class="arrow">→</span>
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-12 ig-anim-fade-up ig-delay-1">
            <div class="ig-card p-6">
                <p class="ig-eyebrow mb-1">Tasks Completed</p>
                <p class="ig-stat-num text-4xl mt-3" data-counter="{{ $analytics['completed_tasks'] }}">0</p>
                <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-2">{{ $analytics['pending_tasks'] }} in progress</p>
            </div>

            <div class="ig-card p-6">
                <p class="ig-eyebrow mb-1">Current Points</p>
                <p class="ig-stat-num text-4xl mt-3" data-counter="{{ (int)($profile->wallet->balance ?? 0) }}">0</p>
                <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-2">{{ number_format($totalPoints) }} earned total</p>
            </div>

            <div class="ig-card p-6">
                <p class="ig-eyebrow mb-1">Stipend Earnings</p>
                <p class="ig-display text-3xl mt-3">₹{{ number_format($profile->wallet_balance, 0) }}</p>
                <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-2">₹{{ number_format($analytics['total_stipend'], 0) }} total stipend</p>
            </div>

            <div class="ig-card p-6">
                <p class="ig-eyebrow mb-1">Average Rating</p>
                <p class="ig-display text-4xl mt-3">
                    {{ number_format($analytics['avg_rating'], 1) }}
                    <span class="text-xs text-[var(--ig-muted)] font-normal">/ 5.0</span>
                </p>
                <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-2">Based on founder reviews</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- Skills & Expertise -->
            <div class="lg:col-span-6 ig-card p-8 ig-reveal">
                <h3 class="ig-display text-2xl mb-8 pb-4 border-b border-[var(--ig-line)]">Skills & Expertise</h3>
                <div class="space-y-5">
                    @forelse($analytics['skills_stats'] as $skill)
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="font-semibold text-sm text-[var(--ig-ink)]">{{ $skill['name'] }}</span>
                                <span class="ig-mono text-xs text-[var(--ig-muted)]">{{ $skill['count'] }} tasks</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[var(--ig-accent)] h-full rounded-full" style="width: {{ $skill['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[var(--ig-muted)]">No skill statistics available yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="lg:col-span-6 ig-card p-8 ig-reveal" data-reveal-delay="100">
                <h3 class="ig-display text-2xl mb-8 pb-4 border-b border-[var(--ig-line)]">Efficiency Signals</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-5 border border-[var(--ig-line)] rounded-2xl">
                        <p class="ig-eyebrow mb-1">Success Rate</p>
                        <p class="ig-stat-num text-3xl mt-2 text-[var(--ig-ink)]">{{ $analytics['success_rate'] }}%</p>
                        <p class="text-[11px] text-[var(--ig-muted)] mt-2">Completed vs accepted tasks</p>
                    </div>

                    <div class="p-5 border border-[var(--ig-line)] rounded-2xl">
                        <p class="ig-eyebrow mb-1">Reliability Score</p>
                        <p class="ig-stat-num text-3xl mt-2 text-[var(--ig-ink)]">{{ number_format($profile->reliability_score * 100, 0) }}%</p>
                        <p class="text-[11px] text-[var(--ig-muted)] mt-2">On-time shipping indicator</p>
                    </div>

                    <div class="p-5 border border-[var(--ig-line)] rounded-2xl sm:col-span-2 flex items-center justify-between">
                        <div>
                            <p class="ig-eyebrow mb-1">Active Applications</p>
                            <p class="ig-stat-num text-3xl mt-2 text-[var(--ig-ink)]">{{ $analytics['active_applications'] }}</p>
                        </div>
                        <span class="ig-chip ig-chip-accent">In Review</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Domain-Specific Analytics -->
        <div class="ig-card p-8 mb-12 ig-reveal">
            <h3 class="ig-display text-2xl mb-8 pb-4 border-b border-[var(--ig-line)]">Domain-Specific Reputation & Projects</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(\App\Models\StudentProfile::$domains as $domainName => $roles)
                    @php
                        $projectsCount = $analytics['projects_by_domain'][$domainName] ?? 0;
                        $reputationVal = $analytics['reputation_by_domain'][$domainName] ?? 50.00;
                        $internshipsCount = $analytics['internships_by_domain'][$domainName] ?? 0;
                        
                        $emoji = '💻';
                        if ($domainName == 'UI/UX Design') $emoji = '🎨';
                        elseif ($domainName == 'Digital Marketing') $emoji = '📈';
                        elseif ($domainName == 'Data & AI') $emoji = '🤖';
                        elseif ($domainName == 'Content & Business') $emoji = '💼';
                    @endphp
                    <div class="p-5 border border-[var(--ig-line)] rounded-2xl bg-[var(--ig-bg-2)] flex flex-col justify-between space-y-4 animate-fade-in">
                        <div class="flex items-center">
                            <span class="text-xl mr-2">{{ $emoji }}</span>
                            <span class="font-bold text-sm text-[var(--ig-ink)]">{{ $domainName }}</span>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1 text-xs">
                                <span class="text-[var(--ig-muted)] font-semibold">IPRS Reputation</span>
                                <span class="font-bold text-[var(--ig-ink)]">{{ round($reputationVal) }}/100</span>
                            </div>
                            <div class="w-full bg-white border border-[var(--ig-line-2)] rounded-full h-2 overflow-hidden">
                                <div class="bg-[var(--ig-accent)] h-full rounded-full" style="width: {{ min($reputationVal, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-[var(--ig-line-2)] text-xs">
                            <div>
                                <span class="block text-[10px] text-[var(--ig-muted)] uppercase tracking-wider font-semibold">Projects</span>
                                <span class="font-bold text-[var(--ig-ink)] text-sm">{{ $projectsCount }} completed</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-[var(--ig-muted)] uppercase tracking-wider font-semibold">Internships</span>
                                <span class="font-bold text-[var(--ig-ink)] text-sm">{{ $internshipsCount }} accepted</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Completed Tasks Timeline -->
        <div class="ig-card p-8 mb-12 ig-reveal">
            <h3 class="ig-display text-2xl mb-8 pb-4 border-b border-[var(--ig-line)]">Work Log & Milestones</h3>
            <div class="space-y-5">
                @forelse($analytics['completed_tasks_list'] as $task)
                    <div class="p-5 border border-[var(--ig-line)] rounded-2xl hover:border-[var(--ig-ink)] transition-colors flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="ig-chip ig-chip-success mb-2" style="font-size:10px;padding:2px 8px;">VERIFIED</span>
                            <h4 class="ig-display text-xl mt-1">{{ $task->task->title }}</h4>
                            <p class="ig-mono text-[11px] text-[var(--ig-muted)] mt-1">
                                {{ $task->task->startup->company_name }} · Shipped {{ $task->submission->updated_at->format('M d, Y') }}
                            </p>
                            @if($task->rating)
                                <div class="flex items-center gap-1 text-yellow-500 mt-2 text-sm">
                                    ★ <span class="text-xs text-[var(--ig-ink-2)] font-semibold">{{ $task->rating->rating }}/5.0</span>
                                </div>
                            @endif
                        </div>
                        <div class="sm:text-right flex-shrink-0">
                            <p class="ig-display text-2xl text-[var(--ig-accent)]">{{ $task->task->reward_points }} <span class="text-xs text-[var(--ig-muted)] font-normal">pts</span></p>
                            @if($task->task->stipend)
                                <p class="text-xs font-semibold text-[var(--ig-lime-deep)] mt-1">₹{{ number_format($task->task->stipend, 0) }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-[var(--ig-muted)]">
                        <p class="ig-display text-xl mb-1">No completed tasks in log yet.</p>
                        <p class="text-xs">Take on and complete marketplace tasks to build your achievements ledger.</p>
                    </div>
                @endforelse
            </div>
        </div>


    </div>
</x-app-layout>
