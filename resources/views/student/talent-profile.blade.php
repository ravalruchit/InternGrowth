<x-app-layout>
    <div class="ig-container py-12 ig-anim-fade-up">
        
        <!-- Hero Header Card -->
        <div class="ig-card p-6 sm:p-8 bg-gradient-to-br from-white via-[var(--ig-bg-2)] to-white relative overflow-hidden mb-8 border border-[var(--ig-line-2)] shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <!-- Avatar initials -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[var(--ig-surface-ink)] flex items-center justify-center text-3xl sm:text-4xl font-black text-white uppercase shadow-lg flex-shrink-0 font-poppins">
                        {{ substr($profile->user->name, 0, 2) }}
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="ig-display text-3xl sm:text-4xl text-[var(--ig-ink)]">{{ $profile->user->name }}</h1>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @if($profile->primary_domain)
                                    @php
                                        $emoji = '💻';
                                        if ($profile->primary_domain == 'UI/UX Design') $emoji = '🎨';
                                        elseif ($profile->primary_domain == 'Digital Marketing') $emoji = '📈';
                                        elseif ($profile->primary_domain == 'Data & AI') $emoji = '🤖';
                                        elseif ($profile->primary_domain == 'Content & Business') $emoji = '💼';
                                    @endphp
                                    <span class="ig-chip ig-chip-lime text-[11px] font-bold">{{ $emoji }} {{ $profile->primary_domain }}</span>
                                @else
                                    <span class="ig-chip text-[11px] font-medium">Declared Profile</span>
                                @endif
                                @if($profile->preferred_role)
                                    <span class="ig-chip text-[11px] font-semibold bg-[var(--ig-bg-2)] border border-[var(--ig-line)] text-[var(--ig-ink-2)]">{{ $profile->preferred_role }}</span>
                                @endif
                            </div>
                        </div>

                        @if($profile->college_name)
                            <p class="text-sm font-semibold text-[var(--ig-muted)] mt-1">🏫 {{ $profile->college_name }}</p>
                        @endif

                        @if($profile->bio)
                            <p class="text-sm text-[var(--ig-ink-2)] mt-3 max-w-xl leading-relaxed font-normal">{{ $profile->bio }}</p>
                        @endif

                        <p class="text-[10px] text-[var(--ig-faint)] font-mono mt-2">Member since {{ $profile->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <x-share-profile-button :url="route('talent.profile', $portfolio->custom_slug)" />
                </div>
            </div>
        </div>

        <!-- Hiring Metrics Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
            @php
                $stats = [
                    ['label' => 'Total Projects', 'value' => $hiringSummary['total_projects'], 'icon' => '📂', 'chip' => 'ig-chip-ink'],
                    ['label' => 'Avg Rating', 'value' => $hiringSummary['avg_startup_rating'] ? $hiringSummary['avg_startup_rating'] . ' / 5.0' : 'N/A', 'icon' => '⭐', 'chip' => 'ig-chip-warn'],
                    ['label' => 'Verified Skills', 'value' => $hiringSummary['verified_skills'], 'icon' => '⚡', 'chip' => 'ig-chip-lime'],
                    ['label' => 'Offers Received', 'value' => $hiringSummary['offers_received'], 'icon' => '💼', 'chip' => 'ig-chip-accent'],
                    ['label' => 'IPRS Score', 'value' => $hiringSummary['iprs_score'], 'icon' => '🏆', 'chip' => 'ig-chip-success'],
                ];
            @endphp
            @foreach($stats as $stat)
                <div class="ig-card p-4 text-center hover:translate-y-[-2px] transition duration-200 shadow-sm border border-[var(--ig-line)]">
                    <div class="text-2xl mb-1.5">{{ $stat['icon'] }}</div>
                    <div class="ig-display text-xl sm:text-2xl text-[var(--ig-ink)]">{{ $stat['value'] }}</div>
                    <div class="text-[9px] sm:text-[10px] text-[var(--ig-muted)] font-bold uppercase tracking-wider mt-1">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Layout details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Panel: IPRS Gauge & Skills -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Reputation Score Widget -->
                @php
                    $overall = $score ? $score->overall_score : 50.00;
                @endphp
                <div class="ig-card-dark p-6 relative overflow-hidden shadow-xl">
                    <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-20" style="background:var(--ig-accent)"></div>
                    <div class="relative text-center border-b border-white/10 pb-5 mb-5">
                        <p class="ig-eyebrow text-[10px] uppercase tracking-widest text-[var(--ig-lime)]">Reputation Index</p>
                        <div class="flex items-baseline justify-center gap-1 mt-3">
                            <span class="ig-display text-5xl font-black text-white">{{ round($overall) }}</span>
                            <span class="text-lg font-bold text-white/50">/100</span>
                        </div>
                        <p class="text-[10px]" style="color: #9C9580">Verified Professional Trust Rank</p>
                    </div>

                    <div class="space-y-4 text-xs">
                        @php
                            $metrics = [
                                ['label' => 'Trust Score', 'value' => $score ? round($score->trust_score) : 50, 'color' => 'bg-[var(--ig-accent)]'],
                                ['label' => 'Completion Rate', 'value' => $score ? round($score->completion_rate) : 100, 'color' => 'bg-[var(--ig-lime)]'],
                                ['label' => 'On-Time Delivery', 'value' => $score ? round($score->on_time_rate) : 100, 'color' => 'bg-amber-400'],
                                ['label' => 'Startup Satisfaction', 'value' => $score ? round($score->satisfaction_rating * 20) : 100, 'color' => 'bg-cyan-400'],
                                ['label' => 'Interview Performance', 'value' => $score ? round($score->interview_performance_score) : 100, 'color' => 'bg-[var(--ig-forest)]'],
                            ];
                        @endphp

                        @foreach($metrics as $metric)
                            <div>
                                <div class="flex justify-between font-semibold mb-1">
                                    <span style="color: #C9C1AE">{{ $metric['label'] }}</span>
                                    <span class="text-white">{{ $metric['value'] }}%</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-1 overflow-hidden">
                                    <div class="{{ $metric['color'] }} h-1 rounded-full" style="width: {{ min($metric['value'], 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach

                        @if($score && $score->interviews_attended > 0)
                            <div class="grid grid-cols-2 gap-2 text-[10px] bg-white/5 border border-white/10 rounded-xl p-3 mt-4 text-white/80">
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">Attended</span>
                                    <span class="font-bold text-white text-xs">{{ $score->interviews_attended }}</span>
                                </div>
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">Success</span>
                                    <span class="font-bold text-white text-xs">{{ number_format($score->interview_success_rate, 0) }}%</span>
                                </div>
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">Strong Candidates</span>
                                    <span class="font-bold text-white text-xs">{{ $score->strong_candidate_outcomes }}</span>
                                </div>
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">No Shows</span>
                                    <span class="font-bold text-rose-455 text-xs">{{ $score->no_shows }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Verified Skills list -->
                <div class="ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4 flex items-center">
                        <span class="mr-1.5">⚡</span> Skills Breakdown
                    </h3>

                    <div class="space-y-3">
                        @foreach($profile->skills as $skill)
                            @php
                                $isVerified = in_array($skill->id, $verifiedSkills);
                                $startupCount = $startupVerificationCounts[$skill->id] ?? 0;
                                $skillScore = $skillScores[$skill->id] ?? null;
                            @endphp

                            @if($isVerified)
                                @php
                                    $badgeText = 'Verified';
                                    $badgeClass = 'bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] border-[var(--ig-accent-soft)]';
                                    $badgeEmoji = '✅';
                                    
                                    if ($startupCount >= 5) {
                                        $badgeText = 'Expert';
                                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $badgeEmoji = '👑';
                                    } elseif ($startupCount >= 3) {
                                        $badgeText = 'Proficient';
                                        $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $badgeEmoji = '⭐';
                                    }
                                @endphp
                                <div class="flex items-center justify-between bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl px-3.5 py-3 hover:border-[var(--ig-accent)] transition">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[var(--ig-accent)] animate-pulse"></div>
                                        <span class="text-xs font-bold text-[var(--ig-ink)]">{{ $skill->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-md border {{ $badgeClass }} flex items-center gap-1">
                                            <span>{{ $badgeEmoji }}</span>
                                            <span>{{ $badgeText }}</span>
                                            @if($startupCount > 0)
                                                <span class="opacity-60">({{ $startupCount }} {{ Str::plural('Startup', $startupCount) }})</span>
                                            @endif
                                        </span>
                                        @if($skillScore)
                                            <span class="text-[9px] font-bold text-[var(--ig-ink-2)] bg-white border border-[var(--ig-line)] px-1.5 py-0.5 rounded-md font-mono">
                                                {{ $skillScore }}/100
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-between border border-[var(--ig-line)] rounded-xl px-3.5 py-3 opacity-60">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[var(--ig-muted)]"></div>
                                        <span class="text-xs font-medium text-[var(--ig-ink-2)]">{{ $skill->name }}</span>
                                    </div>
                                    <span class="text-[9px] font-medium text-[var(--ig-muted)] bg-[var(--ig-bg-2)] px-2 py-0.5 rounded-md">Declared</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- B2B Direct Offers -->
                @if(auth()->check() && auth()->user()->isStartup())
                    <div class="ig-card p-6 space-y-4">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Direct Acquisition Pipeline</h3>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Directly pitch to this candidate and bypass the standard hiring steps.</p>
                        
                        <div class="flex flex-col gap-2.5">
                            <button onclick="toggleModal('internship-modal')" class="ig-btn ig-btn-primary justify-center w-full">
                                💼 Offer Internship
                            </button>
                            <button onclick="toggleModal('job-modal')" class="ig-btn ig-btn-ghost justify-center w-full">
                                🚀 Offer Full-Time Job
                            </button>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Panel: Experience Ledger -->
            <div class="lg:col-span-8 space-y-6">
                
                <div class="flex justify-between items-center border-b border-[var(--ig-line)] pb-4">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] flex items-center">
                        <span class="mr-2">📂</span> Verified Experience Ledger
                    </h2>
                    <span class="ig-chip ig-chip-lime text-xs font-bold">
                        {{ $portfolio->items->count() }} Shipped {{ Str::plural('Project', $portfolio->items->count()) }}
                    </span>
                </div>

                @if($portfolio->items->count() > 0)
                    <div class="space-y-6">
                        @foreach($portfolio->items as $item)
                            @php $badge = $item->badgeLabel(); @endphp
                            <div class="ig-card p-6 hover:translate-y-[-2px] transition duration-350 shadow-sm border border-[var(--ig-line)] group relative overflow-hidden">
                                <!-- Solid Tangerine/Chartreuse side indicator -->
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[var(--ig-ink)] group-hover:bg-[var(--ig-accent)] transition-colors"></div>

                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                                    <div>
                                        <h3 class="ig-display text-xl text-[var(--ig-ink)] group-hover:text-[var(--ig-accent)] transition-colors">{{ $item->startup_name }}</h3>
                                        <p class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mt-1">{{ $item->project_title }}</p>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="ig-chip text-[10px] font-bold">
                                            {{ $badge['emoji'] }} {{ $badge['label'] }}
                                        </span>
                                        @if($item->rating_received)
                                            <span class="ig-chip ig-chip-warn text-[10px] font-bold">
                                                ⭐ {{ number_format($item->rating_received, 1) }} / 5.0
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl p-4 text-xs text-[var(--ig-ink-2)] mb-4">
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Domain</span>
                                        <span class="font-bold text-[var(--ig-ink)]">{{ $item->domain ?? 'Software Development' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Role</span>
                                        <span class="font-bold text-[var(--ig-ink)]">{{ $item->role ?? 'Developer' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Date</span>
                                        <span class="font-bold text-[var(--ig-ink)]">{{ $item->completed_at ? $item->completed_at->format('M d, Y') : $item->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Status</span>
                                        <span class="font-bold text-emerald-700">✓ Accepted</span>
                                    </div>
                                </div>

                                <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-4 font-normal">{{ $item->auto_summary }}</p>

                                @if($item->hasEvidence())
                                    <div class="bg-white border border-[var(--ig-line)] rounded-xl p-4 mb-4">
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">📎 Code & Project Assets</p>
                                        <div class="flex flex-wrap gap-2">
                                            @if($item->github_url)
                                                <a href="{{ $item->github_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] text-xs font-bold text-[var(--ig-ink)] hover:border-[var(--ig-ink)] transition">
                                                    💻 Github Source
                                                </a>
                                            @endif
                                            @if($item->demo_url)
                                                <a href="{{ $item->demo_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] text-xs font-bold text-[var(--ig-ink)] hover:border-[var(--ig-ink)] transition">
                                                    🔗 Live Demo URL
                                                </a>
                                            @endif
                                        </div>

                                        @if($item->screenshots && count($item->screenshots) > 0)
                                            <div class="flex flex-wrap gap-2 mt-3">
                                                @foreach($item->screenshots as $screenshot)
                                                    @if(isset($screenshot['path']))
                                                        <a href="{{ asset('storage/' . $screenshot['path']) }}" target="_blank" class="block w-14 h-14 rounded-lg overflow-hidden border border-[var(--ig-line-2)] hover:border-[var(--ig-ink)] transition">
                                                            <img src="{{ asset('storage/' . $screenshot['path']) }}" alt="Screenshot" class="w-full h-full object-cover">
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[var(--ig-line)] pt-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($item->skills_demonstrated ?? [] as $skillName)
                                            <span class="ig-tag">{{ $skillName }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ig-card p-12 text-center text-[var(--ig-muted)] border-dashed">
                        <span class="text-4xl block mb-3">📂</span>
                        <p class="font-bold text-sm text-[var(--ig-ink)]">No ledger entries generated yet.</p>
                        <p class="text-xs mt-1">Once this student completes task submissions, verified entries will populate here.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Modals for startup Visitors -->
    @if(auth()->check() && auth()->user()->isStartup())
        <!-- Internship Offer Modal -->
        <div id="internship-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="toggleModal('internship-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle ig-card-dark border-none text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white bg-[var(--ig-surface-ink)]">
                    <h2 class="ig-display text-xl mb-4 text-white">💼 Pitch Internship Offer</h2>

                    <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="student_profile_id" value="{{ $profile->id }}">
                        <input type="hidden" name="offer_type" value="internship">
                        <input type="hidden" name="compensation_period" value="monthly">

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Frontend Development Intern" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Role Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job duties, expectations..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Monthly Stipend (₹)</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 15000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">End Date (Optional)</label>
                            <input type="date" name="end_date" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Perks & Deliverables</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Certificate, Flexible Hours, Work From Home" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('internship-modal')" class="ig-btn ig-btn-ghost text-xs text-white border-white/20 hover:bg-white/10 hover:text-white" style="padding: 10px 18px;">Cancel</button>
                            <button type="submit" class="ig-btn ig-btn-lime text-xs" style="padding: 10px 22px;">Send Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Job Offer Modal -->
        <div id="job-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="toggleModal('job-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle ig-card-dark border-none text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white bg-[var(--ig-surface-ink)]">
                    <h2 class="ig-display text-xl mb-4 text-white">🚀 Pitch Full-Time Job Offer</h2>

                    <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="student_profile_id" value="{{ $profile->id }}">
                        <input type="hidden" name="offer_type" value="job">

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Associate Backend Laravel Developer" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Role Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job responsibilities..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Compensation Amount (₹)</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 600000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Period</label>
                                <select name="compensation_period" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                                    <option value="annual">Annual CTC</option>
                                    <option value="monthly">Monthly Salary</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="start_date" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Contract Terms & Benefits</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Health Insurance, Annual Leave, Bonus Structure" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('job-modal')" class="ig-btn ig-btn-ghost text-xs text-white border-white/20 hover:bg-white/10 hover:text-white" style="padding: 10px 18px;">Cancel</button>
                            <button type="submit" class="ig-btn ig-btn-lime text-xs" style="padding: 10px 22px;">Send Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
