<div class="ig-card p-6 flex flex-col justify-between relative transition-all duration-300">
    
    <!-- Star Bookmark Button -->
    <div class="absolute top-6 right-6 z-10">
        <form action="{{ route('startup.candidates.save', $student->id) }}" method="POST">
            @csrf
            <button type="submit" class="p-2 rounded-full border {{ $student->is_saved ? 'border-[var(--ig-lime-deep)] bg-[var(--ig-lime)] text-[var(--ig-ink)] hover:scale-105' : 'border-[var(--ig-line-2)] bg-white text-[var(--ig-muted)] hover:text-[var(--ig-ink)] hover:border-[var(--ig-ink)]' }} transition-all duration-200">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 .587l3.668 7.431 8.2 1.191-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.133 9.409l8.2-1.191L12 .587z"/>
                </svg>
            </button>
        </form>
    </div>

    <div>
        <!-- Student Profile Info Header -->
        <div class="flex items-start space-x-4 mb-4 pr-10">
            <div class="w-12 h-12 bg-[var(--ig-ink)] text-[var(--ig-lime)] font-extrabold text-lg flex items-center justify-center rounded-2xl flex-shrink-0">
                {{ strtoupper(substr($student->user->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center space-x-1.5">
                    <h3 class="font-bold text-[var(--ig-ink)] text-base leading-tight">{{ $student->user->name }}</h3>
                    @if($student->is_verified)
                        <span class="text-xs px-1.5 py-0.5 rounded bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] font-bold uppercase tracking-wider" title="Academic Verified Profile">Verified</span>
                    @endif
                </div>
                <p class="text-xs text-[var(--ig-muted)] mt-1.5 font-medium flex items-center">
                    🏫 {{ $student->college_name ?? 'Not Specified' }}
                </p>
            </div>
        </div>

        <!-- Availability Badge -->
        <div class="mb-4">
            @if($student->availability === 'open_to_work')
                <span class="ig-chip ig-chip-success">
                    🟢 Open to Work
                </span>
            @elseif($student->availability === 'looking_for_internship')
                <span class="ig-chip ig-chip-accent">
                    💼 Looking for Internship
                </span>
            @elseif($student->availability === 'looking_for_job')
                <span class="ig-chip ig-chip-ink">
                    🚀 Looking for Full-Time
                </span>
            @elseif($student->availability === 'freelance_available')
                <span class="ig-chip ig-chip-lime">
                    ⚡ Freelance Available
                </span>
            @endif
        </div>

        @php
            $rankingDetails = $student->ai_match['ranking_details'] ?? [];
            $matchScore = $student->ai_match['percentage'] ?? 50;
            $matchLabel = $student->ai_match['label'] ?? 'Low Match';
            $topStrength = $rankingDetails['top_strength'] ?? 'General Aptitude';
            $verifiedTasksCount = $rankingDetails['completed_tasks_count'] ?? 0;
            $portfolioRatingLabel = $rankingDetails['portfolio_rating_label'] ?? 'No Portfolio';
        @endphp

        <!-- AI Match Percentage & Premium Metrics -->
        <div class="bg-[var(--ig-bg-2)] rounded-2xl p-4 mb-4 border border-[var(--ig-line)]">
            <div class="flex justify-between items-center mb-2">
                <span class="ig-eyebrow text-[10px] font-bold uppercase tracking-wider text-[var(--ig-muted)]">AI Matching Insights</span>
                <span class="ig-chip ig-chip-lime font-mono font-bold text-xs">
                    {{ $matchScore }}% {{ $matchLabel }}
                </span>
            </div>

            <!-- Progress Bar under match percentage -->
            <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden mb-3 border border-gray-250/50 shadow-inner">
                <div class="bg-[var(--ig-accent)] h-full rounded-full transition-all duration-500" style="width: {{ $matchScore }}%"></div>
            </div>
            
            <!-- AI Sourcing Details checklist -->
            <div class="space-y-2 text-xs border-b border-dashed border-[var(--ig-line-2)] pb-3">
                <div class="flex items-center justify-between text-xs pt-1">
                    <span class="text-[var(--ig-muted)] font-medium">🔥 Top Strength</span>
                    <span class="font-bold text-[var(--ig-ink)]">{{ $topStrength }}</span>
                </div>
                <div class="flex items-center justify-between text-xs pt-1">
                    <span class="text-[var(--ig-muted)] font-medium">✔️ Verified Work</span>
                    <span class="font-bold text-[var(--ig-ink)]">{{ $verifiedTasksCount }} Tasks Completed</span>
                </div>
                <div class="flex items-center justify-between text-xs pt-1">
                    <span class="text-[var(--ig-muted)] font-medium">📁 Portfolio Status</span>
                    <span class="font-bold text-[var(--ig-ink)]">{{ $portfolioRatingLabel }}</span>
                </div>
            </div>

            <!-- View Match Details Drawer -->
            @if(isset($rankingDetails['breakdown']))
                <div class="mt-3">
                    <button type="button" 
                            class="w-full text-left py-2 px-3 bg-white/70 hover:bg-gray-100 transition flex items-center justify-between text-[9px] font-bold text-gray-700 uppercase tracking-wider font-poppins rounded-xl border border-[var(--ig-line-2)]" 
                            onclick="toggleInsightsDrawer('match-drawer-candidate-{{ $student->id }}')">
                        <span class="flex items-center gap-1">
                            📊 View Match Details
                        </span>
                        <span class="arrow transition-transform duration-200 select-none">▼</span>
                    </button>
                    
                    <div id="match-drawer-candidate-{{ $student->id }}" class="hidden pt-3 mt-3 border-t border-dashed border-gray-200 space-y-2.5 text-[11px]">
                        <!-- Match Reasons / Explanations -->
                        @if(!empty($rankingDetails['explanations']))
                            <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-xl p-2.5 mb-2.5">
                                <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider block mb-1">Match Insights</span>
                                <ul class="list-disc list-inside space-y-1 text-emerald-950 font-medium text-[10.5px]">
                                    @foreach($rankingDetails['explanations'] as $expl)
                                        <li>{{ $expl }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Skills Match (55%) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold text-gray-700">
                                <span>Skills Match (55%)</span>
                                <span>{{ $rankingDetails['breakdown']['skills_match'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-1">
                                <div class="bg-[var(--ig-accent)] h-1 rounded-full" style="width: {{ $rankingDetails['breakdown']['skills_match'] ?? 0 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Domain Match (15%) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold text-gray-700">
                                <span>Domain Match (15%)</span>
                                <span>{{ $rankingDetails['breakdown']['domain_alignment'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-1">
                                <div class="bg-[var(--ig-accent)] h-1 rounded-full" style="width: {{ $rankingDetails['breakdown']['domain_alignment'] ?? 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Role Match (10%) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold text-gray-700">
                                <span>Role Match (10%)</span>
                                <span>{{ $rankingDetails['breakdown']['role_alignment'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-1">
                                <div class="bg-[var(--ig-accent)] h-1 rounded-full" style="width: {{ $rankingDetails['breakdown']['role_alignment'] ?? 0 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Verified Work (10%) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold text-gray-700">
                                <span>Verified Work (10%)</span>
                                <span>{{ $rankingDetails['breakdown']['verified_work'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-1">
                                <div class="bg-[var(--ig-accent)] h-1 rounded-full" style="width: {{ $rankingDetails['breakdown']['verified_work'] ?? 0 }}%"></div>
                            </div>
                        </div>

                        <!-- IPRS Score (5%) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold text-gray-700">
                                <span>IPRS Score (5%)</span>
                                <span>{{ $rankingDetails['breakdown']['iprs'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-1">
                                <div class="bg-[var(--ig-accent)] h-1 rounded-full" style="width: {{ $rankingDetails['breakdown']['iprs'] ?? 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Portfolio Quality (5%) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold text-gray-700">
                                <span>Portfolio Quality (5%)</span>
                                <span>{{ $rankingDetails['breakdown']['portfolio'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-1">
                                <div class="bg-[var(--ig-accent)] h-1 rounded-full" style="width: {{ $rankingDetails['breakdown']['portfolio'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Success Metrics Stats -->
        <div class="grid grid-cols-3 gap-2 py-3 border-t border-b border-[var(--ig-line)] text-center mb-6">
            <div>
                <span class="block ig-stat-num text-xl text-[var(--ig-ink)]">{{ $student->projects_count }}</span>
                <span class="ig-eyebrow text-[9px]">Projects</span>
            </div>
            <div>
                <span class="block ig-stat-num text-xl text-[var(--ig-ink)]">{{ $student->internships_count }}</span>
                <span class="ig-eyebrow text-[9px]">Internships</span>
            </div>
            <div>
                <span class="block ig-stat-num text-xl text-[var(--ig-ink)]">{{ $student->offers_count }}</span>
                <span class="ig-eyebrow text-[9px]">Offers</span>
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-3">
        <!-- View Public Profile Link -->
        <a href="{{ route('students.public-profile', $student->id) }}" target="_blank" class="ig-btn ig-btn-ghost flex-1 text-center justify-center text-xs py-2 px-3">
            Full Profile ↗
        </a>
        
        <!-- Slide Preview Drawer Trigger -->
        <button type="button" 
                onclick="openStudentDrawer(this)" 
                data-student="{{ json_encode([
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'initials' => strtoupper(substr($student->user->name, 0, 2)),
                    'college_name' => $student->college_name ?? 'Not Specified',
                    'is_verified' => $student->is_verified,
                    'bio' => $student->bio ?? 'No bio provided.',
                    'availability' => $student->availability,
                    'availability_text' => $student->availability === 'open_to_work' ? 'Open to Work' : 
                                        ($student->availability === 'looking_for_internship' ? 'Looking for Internship' : 
                                        ($student->availability === 'looking_for_job' ? 'Looking for Full-Time' : 'Freelance Available')),
                    'overall_score' => $student->reputationScore->overall_score ?? 50.00,
                    'trust_score' => $student->reputationScore->trust_score ?? 50.00,
                    'completion_rate' => $student->reputationScore->completion_rate ?? 100.00,
                    'on_time_rate' => $student->reputationScore->on_time_rate ?? 100.00,
                    'satisfaction_rating' => $student->reputationScore->satisfaction_rating ?? 0.00,
                    'communication_rating' => $student->reputationScore->communication_rating ?? 0.00,
                    'interview_performance_score' => $student->reputationScore->interview_performance_score ?? 100.00,
                    'interviews_attended' => $student->reputationScore->interviews_attended ?? 0,
                    'interview_success_rate' => $student->reputationScore->interview_success_rate ?? 100.00,
                    'strong_candidate_outcomes' => $student->reputationScore->strong_candidate_outcomes ?? 0,
                    'no_shows' => $student->reputationScore->no_shows ?? 0,
                    'projects' => $student->portfolio ? $student->portfolio->items->map(fn($item) => [
                        'project_title' => $item->project_title,
                        'startup_name' => $item->startup_name,
                        'date' => $item->created_at->format('M Y'),
                        'rating' => $item->rating_received,
                        'skills' => $item->skills_demonstrated ?? []
                    ])->toArray() : [],
                    'skills' => $student->skills->pluck('name')->toArray()
                ]) }}"
                class="ig-btn ig-btn-primary flex-1 text-center justify-center text-xs py-2 px-3">
            Preview Card
        </button>
    </div>
</div>
