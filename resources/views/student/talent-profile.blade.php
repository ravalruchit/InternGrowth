<x-app-layout>
    <div class="min-h-screen bg-slate-950">

        <!-- ═══════════════════════════════════════════════════════════
             HERO HEADER
        ═══════════════════════════════════════════════════════════ -->
        <div class="relative overflow-hidden">
            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-slate-950 to-slate-900"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-600/10 via-transparent to-transparent"></div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <!-- Avatar -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-3xl sm:text-4xl font-black text-white uppercase shadow-2xl shadow-indigo-500/30 ring-2 ring-indigo-500/20">
                            {{ substr($profile->user->name, 0, 2) }}
                        </div>

                        <div>
                            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $profile->user->name }}</h1>

                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                @if($profile->is_verified)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/15 text-indigo-400 border border-indigo-500/25">
                                        🎓 Verified Academic Profile
                                    </span>
                                @endif
                                @if($profile->college_name)
                                    <span class="text-xs text-slate-500 font-medium">{{ $profile->college_name }}</span>
                                @endif
                            </div>

                            @if($profile->bio)
                                <p class="text-sm text-slate-400 mt-3 max-w-lg leading-relaxed">{{ $profile->bio }}</p>
                            @endif

                            <p class="text-[11px] text-slate-600 mt-2 font-medium">Member since {{ $profile->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <!-- Share Profile Button -->
                    <div class="flex-shrink-0">
                        <x-share-profile-button :url="route('talent.profile', $portfolio->custom_slug)" />
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 -mt-4">

            <!-- ═══════════════════════════════════════════════════════════
                 HIRING SUMMARY STATS GRID
            ═══════════════════════════════════════════════════════════ -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4 mb-8">
                @php
                    $stats = [
                        ['label' => 'Total Projects', 'value' => $hiringSummary['total_projects'], 'icon' => '📂', 'color' => 'indigo'],
                        ['label' => 'Avg Startup Rating', 'value' => $hiringSummary['avg_startup_rating'] ? $hiringSummary['avg_startup_rating'] . '/5' : 'N/A', 'icon' => '⭐', 'color' => 'amber'],
                        ['label' => 'Verified Skills', 'value' => $hiringSummary['verified_skills'], 'icon' => '⚡', 'color' => 'emerald'],
                        ['label' => 'Offers Received', 'value' => $hiringSummary['offers_received'], 'icon' => '💼', 'color' => 'purple'],
                        ['label' => 'Certificates', 'value' => $hiringSummary['certificates'], 'icon' => '🎓', 'color' => 'sky'],
                    ];
                @endphp

                @foreach($stats as $stat)
                    <div class="bg-slate-900/80 backdrop-blur-sm border border-slate-800/80 rounded-2xl p-4 sm:p-5 text-center hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">{{ $stat['icon'] }}</div>
                        <div class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $stat['value'] }}</div>
                        <div class="text-[10px] sm:text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                <!-- ═══════════════════════════════════════════════════════
                     LEFT COLUMN: IPRS + Skills + Startup CTAs
                ═══════════════════════════════════════════════════════ -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- IPRS Reputation Score Card -->
                    @php
                        $overall = $score ? $score->overall_score : 50.00;
                    @endphp
                    <div class="bg-gradient-to-br from-indigo-950/80 to-slate-900 border border-indigo-500/20 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                        <div class="text-center mb-6">
                            <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Reputation Score (IPRS)</h3>
                            <div class="relative flex items-center justify-center mt-4">
                                <div class="text-5xl font-black text-white tracking-tight">{{ round($overall) }}<span class="text-indigo-400 text-2xl font-bold">/100</span></div>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-2 font-medium">Verified Professional Trust Rank</p>
                        </div>

                        <div class="space-y-3.5 border-t border-slate-800/60 pt-5">
                            @php
                                $metrics = [
                                    ['label' => 'Trust Score', 'value' => $score ? round($score->trust_score) : 50, 'color' => 'indigo'],
                                    ['label' => 'Task Completion Rate', 'value' => $score ? round($score->completion_rate) : 100, 'color' => 'emerald'],
                                    ['label' => 'On-Time Delivery', 'value' => $score ? round($score->on_time_rate) : 100, 'color' => 'amber'],
                                    ['label' => 'Startup Satisfaction', 'value' => $score ? round($score->satisfaction_rating * 20) : 100, 'color' => 'indigo'],
                                    ['label' => 'Interview Performance', 'value' => $score ? round($score->interview_performance_score) : 100, 'color' => 'purple'],
                                ];
                            @endphp

                            @foreach($metrics as $metric)
                                <div>
                                    <div class="flex justify-between text-xs font-medium mb-1">
                                        <span class="text-slate-400">{{ $metric['label'] }}</span>
                                        <span class="text-white font-semibold">{{ $metric['value'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-{{ $metric['color'] }}-500 h-1.5 rounded-full transition-all duration-700" style="width: {{ min($metric['value'], 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Mini Interview stats grid -->
                            @if($score && $score->interviews_attended > 0)
                                <div class="grid grid-cols-2 gap-2 text-[11px] bg-slate-900 border border-slate-800/80 rounded-xl p-3 text-slate-400 mt-2">
                                    <div>
                                        <span class="block text-slate-500 font-medium">Attended</span>
                                        <span class="font-bold text-white">{{ $score->interviews_attended }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-500 font-medium">Success Rate</span>
                                        <span class="font-bold text-white">{{ number_format($score->interview_success_rate, 0) }}%</span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-500 font-medium">Strong Outcomes</span>
                                        <span class="font-bold text-white">{{ $score->strong_candidate_outcomes }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-500 font-medium">No Shows</span>
                                        <span class="font-bold {{ $score->no_shows > 0 ? 'text-rose-400' : 'text-white' }}">{{ $score->no_shows }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ═══════════════ STARTUP VERIFIED SKILLS ═══════════════ -->
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                        <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider mb-4 flex items-center">
                            <span class="mr-2">⚡</span> Startup Verified Skills
                        </h3>

                        <div class="space-y-2.5">
                            @foreach($profile->skills as $skill)
                                @php
                                    $isVerified = in_array($skill->id, $verifiedSkills);
                                    $startupCount = $startupVerificationCounts[$skill->id] ?? 0;
                                    $skillScore = $skillScores[$skill->id] ?? null;
                                @endphp

                                @if($isVerified)
                                    <div class="flex items-center justify-between bg-indigo-500/5 border border-indigo-500/15 rounded-xl px-4 py-3 group hover:border-indigo-500/30 transition-all duration-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                                            <span class="text-sm font-semibold text-white">{{ $skill->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($startupCount > 0)
                                                <span class="text-[10px] font-bold text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-md">
                                                    ✓ {{ $startupCount }} {{ Str::plural('Startup', $startupCount) }}
                                                </span>
                                            @else
                                                <span class="text-[10px] font-bold text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-md">
                                                    ✓ Verified
                                                </span>
                                            @endif
                                            @if($skillScore)
                                                <span class="text-[10px] font-bold text-slate-400 bg-slate-800 px-2 py-0.5 rounded-md">
                                                    {{ $skillScore }}/100
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between bg-slate-800/30 border border-slate-800/50 rounded-xl px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-slate-600"></div>
                                            <span class="text-sm font-medium text-slate-500">{{ $skill->name }}</span>
                                        </div>
                                        <span class="text-[10px] font-medium text-slate-600 bg-slate-800/50 px-2 py-0.5 rounded-md">Declared</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- B2B Startup CTAs (for logged-in startups) -->
                    @if(auth()->check() && auth()->user()->isStartup())
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl text-white space-y-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Acquisition Pipeline</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Directly engage this student using verified work history and bypass standard interviews.</p>

                            <button onclick="toggleModal('internship-modal')" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-500/20 text-sm transition duration-150">
                                💼 Offer Internship
                            </button>
                            <button onclick="toggleModal('job-modal')" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 px-4 rounded-xl border border-slate-700 text-sm transition duration-150">
                                🚀 Offer Job
                            </button>
                        </div>
                    @endif
                </div>

                <!-- ═══════════════════════════════════════════════════════
                     RIGHT COLUMN: VERIFIED WORK PORTFOLIO
                ═══════════════════════════════════════════════════════ -->
                <div class="lg:col-span-2 space-y-6">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h2 class="text-lg font-black text-white flex items-center tracking-tight">
                            <span class="mr-2">📂</span> Verified Work Portfolio
                        </h2>
                        <span class="text-xs text-indigo-400 font-bold bg-indigo-500/10 border border-indigo-500/20 px-3 py-1.5 rounded-full">
                            {{ $portfolio->items->count() }} Verified {{ Str::plural('Project', $portfolio->items->count()) }}
                        </span>
                    </div>

                    @if($portfolio->items->count() > 0)
                        <div class="space-y-5">
                            @foreach($portfolio->items as $item)
                                @php $badge = $item->badgeLabel(); @endphp
                                <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl hover:border-slate-700/60 transition-all duration-300 group backdrop-blur-sm">
                                    <!-- Top edge accent -->
                                    <div class="h-1 bg-gradient-to-r from-{{ $badge['color'] }}-500 to-{{ $badge['color'] }}-600"></div>

                                    <div class="p-6">
                                        <!-- Header Row -->
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                                            <div>
                                                <h3 class="text-lg font-black text-white tracking-tight group-hover:text-indigo-300 transition-colors duration-200">{{ $item->startup_name }}</h3>
                                                <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">{{ $item->project_title }}</p>
                                            </div>

                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <!-- Verification Badge -->
                                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-{{ $badge['color'] }}-500/10 text-{{ $badge['color'] }}-400 border border-{{ $badge['color'] }}-500/20">
                                                    {{ $badge['emoji'] }} {{ $badge['label'] }}
                                                </span>

                                                <!-- Rating -->
                                                @if($item->rating_received)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                        ⭐ {{ number_format($item->rating_received, 1) }}/5
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Details Grid -->
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-800/30 border border-slate-800/50 rounded-xl p-4 mb-4 text-sm">
                                            <div>
                                                <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Role</span>
                                                <span class="text-xs font-bold text-slate-300">
                                                    @if(!empty($item->skills_demonstrated) && is_array($item->skills_demonstrated) && count($item->skills_demonstrated) > 0)
                                                        {{ $item->skills_demonstrated[0] }} Developer
                                                    @else
                                                        Developer
                                                    @endif
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Verified By</span>
                                                <span class="text-xs font-bold text-indigo-400 flex items-center">
                                                    <svg class="w-3 h-3 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Startup Founder
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Completed</span>
                                                <span class="text-xs font-bold text-slate-300">{{ $item->completed_at ? $item->completed_at->format('M d, Y') : $item->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Status</span>
                                                <span class="text-xs font-bold text-emerald-400">✓ Verified</span>
                                            </div>
                                        </div>

                                        <!-- Auto Summary -->
                                        <p class="text-sm text-slate-400 leading-relaxed mb-4">{{ $item->auto_summary }}</p>

                                        <!-- ═══════ PROJECT EVIDENCE ═══════ -->
                                        @if($item->hasEvidence())
                                            <div class="bg-slate-800/30 border border-slate-800/50 rounded-xl p-4 mb-4">
                                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">📎 Project Evidence</h4>
                                                <div class="flex flex-wrap gap-3">
                                                    @if($item->github_url)
                                                        <a href="{{ $item->github_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-xs font-bold text-slate-300 hover:text-white hover:border-slate-600 transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                                            GitHub Repository
                                                        </a>
                                                    @endif
                                                    @if($item->demo_url)
                                                        <a href="{{ $item->demo_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-xs font-bold text-slate-300 hover:text-white hover:border-slate-600 transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                            Live Demo
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- Screenshots Gallery -->
                                                @if($item->screenshots && count($item->screenshots) > 0)
                                                    <div class="flex flex-wrap gap-2 mt-3">
                                                        @foreach($item->screenshots as $screenshot)
                                                            @if(isset($screenshot['path']))
                                                                <a href="{{ asset('storage/' . $screenshot['path']) }}" target="_blank" class="block w-16 h-16 rounded-lg overflow-hidden border border-slate-700 hover:border-indigo-500 transition-all duration-200">
                                                                    <img src="{{ asset('storage/' . $screenshot['path']) }}" alt="{{ $screenshot['name'] ?? 'Screenshot' }}" class="w-full h-full object-cover">
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Skills Tags + Certificate -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-slate-800/50 pt-4">
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($item->skills_demonstrated ?? [] as $skillName)
                                                    <span class="bg-slate-800/80 text-slate-400 text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-slate-700/50">{{ $skillName }}</span>
                                                @endforeach
                                            </div>

                                            @if($item->certificate_number)
                                                <a href="{{ route('certificates.verify', $item->certificate_number) }}" target="_blank" class="inline-flex items-center text-xs text-indigo-400 hover:text-indigo-300 font-bold transition gap-1">
                                                    📜 View Certificate
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-900/80 border border-slate-800/60 rounded-2xl p-12 text-center backdrop-blur-sm">
                            <div class="text-4xl mb-4">📂</div>
                            <p class="text-sm font-bold text-slate-400">This student hasn't completed any verified projects yet.</p>
                            <p class="text-xs text-slate-600 mt-2">Once tasks are accepted, they will automatically appear here as verified portfolio entries.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         HIRING OFFER MODALS (for logged-in startups)
    ═══════════════════════════════════════════════════════════ -->
    @if(auth()->check() && auth()->user()->isStartup())
        <!-- 💼 Internship Offer Modal -->
        <div id="internship-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 transition-opacity" aria-hidden="true" onclick="toggleModal('internship-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 text-white">💼 Extend Internship Offer</h2>

                    <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="student_profile_id" value="{{ $profile->id }}">
                        <input type="hidden" name="offer_type" value="internship">
                        <input type="hidden" name="compensation_period" value="monthly">

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Frontend Development Intern" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Role Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job duties and goals..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Monthly Stipend (₹)</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 8000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">End Date (Optional)</label>
                                <input type="date" name="end_date" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contract Terms & Perks</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Certificate, Flexible Hours, Work From Home" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('internship-modal')" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition duration-150">Send Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 🚀 Job Offer Modal -->
        <div id="job-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 transition-opacity" aria-hidden="true" onclick="toggleModal('job-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 text-white">🚀 Extend Full-Time Job Offer</h2>

                    <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="student_profile_id" value="{{ $profile->id }}">
                        <input type="hidden" name="offer_type" value="job">

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Junior Backend Laravel Developer" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Job Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job responsibilities..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Compensation Amount</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 600000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Period</label>
                                <select name="compensation_period" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                                    <option value="annual">Annual CTC</option>
                                    <option value="monthly">Monthly Salary</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contract Terms & Benefits</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Health Insurance, Annual Leave, Bonus Structure" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('job-modal')" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition duration-150">Send Offer</button>
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
