<x-app-layout>
    <div class="ig-container py-12 ig-anim-fade-up">
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-6 text-sm">
                <p class="font-bold text-emerald-950">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-6 p-4 bg-red-50 border-red-200 text-sm text-red-900 font-bold">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="ig-banner mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-sm text-red-955 font-semibold shadow-sm">
                <p class="font-black text-red-955 mb-1.5">Please fix the following issues:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Student Bio & IPRS Rating -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Base Card -->
                <div class="ig-card p-6 bg-gradient-to-br from-white to-[var(--ig-bg-2)] border border-[var(--ig-line)]">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-[var(--ig-surface-ink)] flex items-center justify-center text-2xl font-black text-white uppercase shadow-lg font-poppins flex-shrink-0">
                            {{ substr($profile->user->name, 0, 2) }}
                        </div>
                        <div>
                            <h1 class="ig-display text-2xl text-[var(--ig-ink)] leading-tight">{{ $profile->user->name }}</h1>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                @if($profile->primary_domain)
                                    @php
                                        $emoji = '💻';
                                        if ($profile->primary_domain == 'UI/UX Design') $emoji = '🎨';
                                        elseif ($profile->primary_domain == 'Digital Marketing') $emoji = '📈';
                                        elseif ($profile->primary_domain == 'Data & AI') $emoji = '🤖';
                                        elseif ($profile->primary_domain == 'Content & Business') $emoji = '💼';
                                    @endphp
                                    <span class="ig-chip ig-chip-lime text-[10px] font-bold">{{ $emoji }} {{ $profile->primary_domain }}</span>
                                @else
                                    <span class="ig-chip text-[10px] font-medium">Declared Profile</span>
                                @endif
                                @if($profile->preferred_role)
                                    <span class="ig-chip text-[10px] font-semibold bg-[var(--ig-bg-2)] border border-[var(--ig-line)] text-[var(--ig-ink-2)]">{{ $profile->preferred_role }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-6 font-normal">{{ $profile->bio ?? 'No bio provided.' }}</p>

                    @php
                        $isContactUnlocked = $profile->contactDetailsUnlockedFor(auth()->user());
                    @endphp

                    <div class="border-t border-[var(--ig-line)] pt-5">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Academic Status</h3>
                        <p class="text-sm font-semibold text-[var(--ig-ink)]">🏫 {{ $profile->college_name ?? 'N/A' }}</p>
                        @if($isContactUnlocked && $profile->college_email)
                            <p class="text-xs text-[var(--ig-muted)] mt-1">{{ $profile->college_email }}</p>
                        @endif
                    </div>

                    <!-- Contact Details -->
                    @if($isContactUnlocked)
                        <div class="border-t border-[var(--ig-line)] pt-5">
                            <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">🔓 Contact Information</h3>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between py-1 border-b border-emerald-100">
                                    <span class="text-emerald-800 font-medium">Email</span>
                                    <span class="font-mono text-gray-900 font-bold select-all">{{ $profile->user->email }}</span>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-emerald-100">
                                    <span class="text-emerald-800 font-medium">Phone</span>
                                    <span class="font-mono text-gray-900 font-bold select-all">{{ $profile->phone_number ?? 'Not provided' }}</span>
                                </div>
                                @if($profile->portfolio_links && count($profile->portfolio_links) > 0)
                                    <div class="py-1">
                                        <span class="text-emerald-800 font-medium block mb-1">Portfolio & Social URLs</span>
                                        <div class="space-y-1">
                                            @foreach($profile->portfolio_links as $link)
                                                @if($link)
                                                    <a href="{{ $link }}" target="_blank" class="text-[var(--ig-accent)] hover:underline block truncate">{{ $link }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="border-t border-[var(--ig-line)] pt-5">
                            <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">🔒 Contact Information</h3>
                            <p class="text-[11px] text-[var(--ig-muted)] leading-relaxed mb-3">
                                Contact details (email, phone, and socials) are masked until a job/internship offer is accepted.
                            </p>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between py-1 border-b border-gray-200">
                                    <span class="text-[var(--ig-muted)]">Email</span>
                                    <span class="font-mono text-gray-400">••••••••@••••.•••</span>
                                </div>
                                <div class="flex items-center justify-between py-1 border-b border-gray-200">
                                    <span class="text-[var(--ig-muted)]">Phone</span>
                                    <span class="font-mono text-gray-400">+91 ••••• •••••</span>
                                </div>
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-[var(--ig-muted)]">Socials / Portfolio</span>
                                    <span class="font-mono text-gray-400">🔒 Masked</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- IPRS Score Card -->
                @php
                    $score = $profile->reputationScore;
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

                <!-- B2B Direct Offers -->
                @if(auth()->check() && auth()->user()->isStartup())
                    <div class="ig-card p-6 space-y-4">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Direct Acquisition Pipeline</h3>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Directly engage this student using verified work history and bypass standard interviews.</p>
                        
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

            <!-- Right Column: Skills & Experience Ledger -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Skills card -->
                <div class="ig-card p-6">
                    <h2 class="text-sm font-bold text-[var(--ig-ink)] mb-4 flex items-center">
                        <span class="mr-1.5">⚡</span> Verified Skill Badges
                    </h2>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->skills as $skill)
                            @if(in_array($skill->id, $verifiedSkills))
                                @php 
                                    $skillScore = $skillScores[$skill->id] ?? null; 
                                    $startupCount = $startupVerificationCounts[$skill->id] ?? 0;
                                    
                                    $badgeText = 'Verified';
                                    $badgeStyle = 'bg-[var(--ig-lime)] border-[var(--ig-lime-deep)] text-[var(--ig-ink)]';
                                    $badgeEmoji = '✅';
                                    
                                    if ($startupCount >= 5) {
                                        $badgeText = 'Expert';
                                        $badgeStyle = 'bg-amber-100 border-amber-300 text-amber-800';
                                        $badgeEmoji = '👑';
                                    } elseif ($startupCount >= 3) {
                                        $badgeText = 'Proficient';
                                        $badgeStyle = 'bg-blue-100 border-blue-300 text-blue-800';
                                        $badgeEmoji = '⭐';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold {{ $badgeStyle }} border">
                                    <span class="mr-1.5">{{ $badgeEmoji }}</span>
                                    {{ $skill->name }} 
                                    <span class="ml-1.5 bg-black/5 px-1.5 py-0.5 rounded-md text-[9px] font-mono font-medium">{{ $badgeText }}</span>
                                    @if($skillScore)
                                        <span class="ml-1 text-black/30 text-[9px] font-mono font-medium">· {{ $skillScore }}/100</span>
                                    @endif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-medium bg-[var(--ig-bg-2)] text-[var(--ig-ink-2)] border border-[var(--ig-line)]">
                                    {{ $skill->name }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Experience Ledger timeline -->
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-[var(--ig-line)] pb-4">
                        <h2 class="ig-display text-2xl text-[var(--ig-ink)] flex items-center">
                            <span class="mr-2">📂</span> Experience Ledger
                        </h2>
                        <span class="ig-chip ig-chip-lime text-xs font-bold">
                            {{ $profile->portfolio && $profile->portfolio->items ? $profile->portfolio->items->count() : 0 }} Verified Placements
                        </span>
                    </div>

                    @if($profile->portfolio && $profile->portfolio->items && $profile->portfolio->items->count() > 0)
                        <div class="space-y-6">
                            @foreach($profile->portfolio->items as $item)
                                @php
                                    $isPlacement = !empty($item->hiring_offer_id);
                                @endphp
                                <div class="ig-card p-6 hover:translate-y-[-2px] transition duration-350 shadow-sm border border-[var(--ig-line)] group relative overflow-hidden bg-white">
                                    <!-- Decorative Solid Side Bar -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[var(--ig-ink)] group-hover:bg-[var(--ig-accent)] transition-colors"></div>

                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="ig-display text-xl text-[var(--ig-ink)] group-hover:text-[var(--ig-accent)] transition-colors">{{ $item->startup_name }}</h3>
                                            <p class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mt-1">
                                                {{ $isPlacement ? 'Verified Hiring Placement' : 'Verified Experience Record' }}
                                            </p>
                                        </div>
                                        
                                        <!-- Rating -->
                                        @if($item->rating_received)
                                            <span class="ig-chip ig-chip-warn text-[10px] font-bold">
                                                ⭐ {{ number_format($item->rating_received, 1) }} / 5.0
                                            </span>
                                        @else
                                            <span class="ig-chip ig-chip-lime text-[10px] font-bold">
                                                {{ $isPlacement ? 'Placement Complete' : 'Verified Task' }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl p-4 text-xs text-[var(--ig-ink-2)] mb-4">
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Domain:</span>
                                            <span class="font-bold text-[var(--ig-ink)]">{{ $item->domain ?? 'Software Development' }}</span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Role:</span>
                                            <span class="font-bold text-[var(--ig-ink)]">{{ $item->role ?? 'Developer' }}</span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">{{ $isPlacement ? 'Offer Title:' : 'Project:' }}</span>
                                            <span class="font-bold text-[var(--ig-ink)]">{{ $item->project_title }}</span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Completed:</span>
                                            <span class="font-bold text-[var(--ig-ink)]">{{ $item->completed_at ? $item->completed_at->format('F d, Y') : $item->created_at->format('F d, Y') }}</span>
                                        </div>
                                    </div>

                                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-4 font-normal">{{ $item->auto_summary }}</p>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-[var(--ig-line)] pt-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($item->skills_demonstrated ?? [] as $skillName)
                                                <span class="ig-tag">{{ $skillName }}</span>
                                            @endforeach
                                        </div>

                                        <div class="flex items-center gap-4">
                                            @if($item->github_url)
                                                <a href="{{ $item->github_url }}" target="_blank" class="text-xs font-bold text-slate-800 hover:underline inline-flex items-center gap-1">
                                                    🐙 Code Repo
                                                </a>
                                            @endif
                                            @if($item->demo_url)
                                                <a href="{{ $item->demo_url }}" target="_blank" class="text-xs font-bold text-[var(--ig-accent)] hover:underline inline-flex items-center gap-1">
                                                    🔗 Live Demo
                                                </a>
                                            @endif
                                            @if($item->certificate_number)
                                                <a href="{{ route('certificates.verify', $item->certificate_number) }}" target="_blank" class="text-xs font-bold text-[var(--ig-accent)] hover:underline inline-flex items-center gap-1">
                                                    🎖️ View Credentials
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ig-card p-12 text-center text-[var(--ig-muted)] border-dashed">
                            <p class="font-bold text-sm text-[var(--ig-ink)]">This student hasn't completed any microtasks yet.</p>
                            <p class="text-xs mt-1">Once tasks are completed, they will automatically appear here as verified experience records.</p>
                        </div>
                    @endif
                </div>
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

                        <div class="flex items-start gap-2 pt-2 text-[var(--ig-lime)]/90 text-xs">
                            <input type="checkbox" name="agreement" id="agreement-check-intern" required value="1" class="mt-0.5 rounded text-[var(--ig-lime)] focus:ring-[var(--ig-lime)] bg-black/40 border-white/10">
                            <label for="agreement-check-intern" class="leading-tight font-semibold">
                                I confirm that this hiring process will be completed through InternGrowth (Payment Agreement).
                            </label>
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

                        <div class="flex items-start gap-2 pt-2 text-[var(--ig-lime)]/90 text-xs">
                            <input type="checkbox" name="agreement" id="agreement-check-job" required value="1" class="mt-0.5 rounded text-[var(--ig-lime)] focus:ring-[var(--ig-lime)] bg-black/40 border-white/10">
                            <label for="agreement-check-job" class="leading-tight font-semibold">
                                I confirm that this hiring process will be completed through InternGrowth (Payment Agreement).
                            </label>
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
