<x-app-layout>
    <div class="ig-container py-12 space-y-8 ig-anim-fade-up">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="ig-eyebrow mb-2">— Sourcing</p>
                <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                    Talent Discovery <span class="ig-serif text-[var(--ig-accent)]">Hub.</span>
                </h1>
                <p class="text-sm text-[var(--ig-muted)] mt-1.5">Search, filter, and directly hire students based on verified project ledgers.</p>
            </div>
            
            <!-- Matching position select box -->
            <form id="match-form" action="{{ route('startup.candidates') }}" method="GET" class="flex-shrink-0">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                @if(request('skill_id')) <input type="hidden" name="skill_id" value="{{ request('skill_id') }}"> @endif
                @if(request('college')) <input type="hidden" name="college" value="{{ request('college') }}"> @endif
                @if(request('min_iprs')) <input type="hidden" name="min_iprs" value="{{ request('min_iprs') }}"> @endif
                @if(request('bookmarked_only')) <input type="hidden" name="bookmarked_only" value="{{ request('bookmarked_only') }}"> @endif
                @if(request('availability'))
                    @foreach(request('availability') as $avail)
                        <input type="hidden" name="availability[]" value="{{ $avail }}">
                    @endforeach
                @endif
                
                <div class="bg-white rounded-2xl border border-[var(--ig-line-2)] p-3 shadow-sm flex items-center gap-2">
                    <span class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider pl-1">Target Match:</span>
                    <select name="position_match" onchange="this.form.submit()" class="text-xs font-bold text-[var(--ig-accent)] border-none focus:ring-0 p-0 pr-8 cursor-pointer bg-transparent">
                        <optgroup label="Your Posted Tasks">
                            @foreach($postedTasks as $task)
                                <option value="task_{{ $task->id }}" {{ $positionMatchKey === 'task_'.$task->id ? 'selected' : '' }}>
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="General Positions">
                            @foreach($defaultPositions as $key => $pos)
                                <option value="{{ $key }}" {{ $positionMatchKey === $key ? 'selected' : '' }}>
                                    {{ $pos['name'] }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </form>
        </div>

        @if(session()->has('success'))
            <div class="ig-banner ig-banner-success text-xs">
                <p class="font-bold text-emerald-950">{{ session('success') }}</p>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="ig-banner text-xs bg-red-50 border-red-200 text-red-950 font-bold">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tab Switcher -->
        <div class="flex border-b border-[var(--ig-line)] space-x-8 text-sm font-semibold">
            <a href="{{ route('startup.candidates', ['tab' => 'discover', 'position_match' => $positionMatchKey]) }}" 
               class="pb-4 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'discover' ? 'border-[var(--ig-ink)] text-[var(--ig-ink)] font-bold' : 'border-transparent text-[var(--ig-muted)] hover:text-[var(--ig-ink)]' }}">
                <span>✨ Discovery Board</span>
            </a>
            <a href="{{ route('startup.candidates', ['tab' => 'search', 'position_match' => $positionMatchKey]) }}" 
               class="pb-4 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'search' ? 'border-[var(--ig-ink)] text-[var(--ig-ink)] font-bold' : 'border-transparent text-[var(--ig-muted)] hover:text-[var(--ig-ink)]' }}">
                <span>🔍 Search Directory</span>
            </a>
        </div>

        @if($activeTab === 'discover')
            <!-- Discovery Hub lists -->
            <div class="space-y-12">
                <!-- Recommended for you -->
                @if($recommended->isNotEmpty())
                    <div class="space-y-5">
                        <div class="border-b border-[var(--ig-line)] pb-2">
                            <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                <span class="mr-2">🎯</span> Matches for Your Open Tasks
                            </h2>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Founders match list generated based on active posted requirements.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($recommended as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Top talent of the month -->
                @if($topTalent->isNotEmpty())
                    <div class="space-y-5">
                        <div class="border-b border-[var(--ig-line)] pb-2">
                            <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                <span class="mr-2">🏆</span> Top Placements of the Month
                            </h2>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Platform candidates with outstanding verified reputation rankings.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($topTalent as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Fastest growing -->
                @if($fastestGrowing->isNotEmpty())
                    <div class="space-y-5">
                        <div class="border-b border-[var(--ig-line)] pb-2">
                            <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                <span class="mr-2">📈</span> High Shipping Speed
                            </h2>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Students completing projects and tasks at lightning speeds.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($fastestGrowing as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Most reliable -->
                @if($mostReliable->isNotEmpty())
                    <div class="space-y-5">
                        <div class="border-b border-[var(--ig-line)] pb-2">
                            <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                <span class="mr-2">🛡️</span> Flawless Delivery Record
                            </h2>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Students with zero delay history and outstanding satisfaction reviews.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($mostReliable as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Domain Specific Talent Sections -->
                <div class="space-y-12">
                    <!-- 1. Top Software Developers -->
                    @if($topSoftware->isNotEmpty())
                        <div class="space-y-5">
                            <div class="border-b border-[var(--ig-line)] pb-2">
                                <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                    <span class="mr-2">💻</span> Top Software Developers
                                </h2>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">Ecosystem candidates with proven software engineering and coding records.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($topSoftware as $student)
                                    @include('startup.partials.candidate-card', ['student' => $student])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 2. Top Designers -->
                    @if($topDesigners->isNotEmpty())
                        <div class="space-y-5">
                            <div class="border-b border-[var(--ig-line)] pb-2">
                                <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                    <span class="mr-2">🎨</span> Top UI/UX Designers
                                </h2>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">Vetted UI/UX, product, and graphic designers with high satisfaction ratings.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($topDesigners as $student)
                                    @include('startup.partials.candidate-card', ['student' => $student])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 3. Top Digital Marketers -->
                    @if($topMarketers->isNotEmpty())
                        <div class="space-y-5">
                            <div class="border-b border-[var(--ig-line)] pb-2">
                                <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                    <span class="mr-2">📈</span> Top Digital Marketers
                                </h2>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">SEO, Social Media, and Performance Marketers with verified outcomes.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($topMarketers as $student)
                                    @include('startup.partials.candidate-card', ['student' => $student])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 4. Top Data & AI Talent -->
                    @if($topDataAi->isNotEmpty())
                        <div class="space-y-5">
                            <div class="border-b border-[var(--ig-line)] pb-2">
                                <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                    <span class="mr-2">🤖</span> Top Data & AI Talent
                                </h2>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">Data analysts, scientists, and Machine Learning engineers building AI integrations.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($topDataAi as $student)
                                    @include('startup.partials.candidate-card', ['student' => $student])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 5. Top Business Talent -->
                    @if($topBusiness->isNotEmpty())
                        <div class="space-y-5">
                            <div class="border-b border-[var(--ig-line)] pb-2">
                                <h2 class="ig-display text-xl text-[var(--ig-ink)] flex items-center">
                                    <span class="mr-2">💼</span> Top Business & Content Talent
                                </h2>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">Content writers, copywriters, and business analysts driving growth operations.</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach($topBusiness as $student)
                                    @include('startup.partials.candidate-card', ['student' => $student])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        @else
            <!-- Search directory layout -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Filters panel -->
                <div class="lg:col-span-1">
                    <div class="ig-card p-6 sticky top-24 bg-white">
                        <div class="flex justify-between items-center mb-6 border-b border-[var(--ig-line)] pb-3">
                            <h2 class="text-sm font-bold text-[var(--ig-ink)]">Filter Sourcing</h2>
                            <a href="{{ route('startup.candidates', ['tab' => 'search', 'position_match' => $positionMatchKey]) }}" class="text-xs text-[var(--ig-accent)] font-bold hover:underline">
                                Clear Filters
                            </a>
                        </div>
                        
                        <form action="{{ route('startup.candidates') }}" method="GET" class="space-y-6">
                            <input type="hidden" name="tab" value="search">
                            <input type="hidden" name="position_match" value="{{ $positionMatchKey }}">

                            <!-- Search string input -->
                            <div>
                                <label class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Search Input</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Snoop by name, bio keywords..." class="ig-input">
                            </div>

                            <!-- Domain Filter -->
                            <div>
                                <label for="filter_domain" class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Domain</label>
                                <select id="filter_domain" name="domain" class="ig-input">
                                    <option value="">All Domains</option>
                                    @foreach(\App\Models\StudentProfile::$domains as $domain => $roles)
                                        <option value="{{ $domain }}" {{ request('domain') == $domain ? 'selected' : '' }}>{{ $domain }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Role Filter -->
                            <div>
                                <label for="filter_role" class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Role</label>
                                <select id="filter_role" name="role" class="ig-input" {{ !request('domain') ? 'disabled' : '' }}>
                                    <option value="">All Roles</option>
                                    @if(request('domain') && isset(\App\Models\StudentProfile::$domains[request('domain')]))
                                        @foreach(\App\Models\StudentProfile::$domains[request('domain')] as $role)
                                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Skills dropdown -->
                            <div>
                                <label class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Primary Expertise</label>
                                <select name="skill_id" class="ig-input">
                                    <option value="">All Skills</option>
                                    @foreach($allSkills as $skill)
                                        <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- College input -->
                            <div>
                                <label class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Institution</label>
                                <input type="text" name="college" value="{{ request('college') }}" placeholder="e.g. Stanford University" class="ig-input">
                            </div>

                            <!-- IPRS range slider -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Min IPRS score</label>
                                    <span id="iprs-val" class="text-xs font-bold text-[var(--ig-accent)] bg-[var(--ig-accent-soft)] px-2 py-0.5 rounded font-mono">{{ request('min_iprs', 50) }}</span>
                                </div>
                                <input type="range" name="min_iprs" min="50" max="100" value="{{ request('min_iprs', 50) }}" oninput="document.getElementById('iprs-val').innerText = this.value" class="w-full h-1 bg-[var(--ig-line-2)] rounded-lg appearance-none cursor-pointer accent-[var(--ig-accent)]">
                            </div>

                            <!-- Availability status -->
                            <div>
                                <label class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-3">Availability</label>
                                <div class="space-y-2.5">
                                    @php
                                        $avails = [
                                            'open_to_work' => '🟢 Open to Work',
                                            'looking_for_internship' => '💼 Internship Seekers',
                                            'looking_for_job' => '🚀 Full-Time Seekers',
                                            'freelance_available' => '⚡ Freelance'
                                        ];
                                        $selectedAvails = request('availability', []);
                                    @endphp
                                    @foreach($avails as $value => $label)
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="availability[]" value="{{ $value }}" {{ in_array($value, $selectedAvails) ? 'checked' : '' }} class="w-4 h-4 text-[var(--ig-accent)] border-[var(--ig-line-2)] rounded focus:ring-[var(--ig-accent)]">
                                            <span class="text-xs text-[var(--ig-ink-2)] font-semibold">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Saved/Bookmarked Candidates -->
                            <div class="pt-4 border-t border-[var(--ig-line)]">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="bookmarked_only" value="1" {{ request('bookmarked_only') ? 'checked' : '' }} class="w-4 h-4 text-[var(--ig-accent)] border-[var(--ig-line-2)] rounded focus:ring-[var(--ig-accent)]">
                                    <span class="text-xs font-bold text-[var(--ig-ink)]">
                                        ⭐ Saved Talents Only
                                    </span>
                                </label>
                            </div>

                            <button type="submit" class="ig-btn ig-btn-primary w-full justify-center text-xs">
                                Apply Search Parameters
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sourcing grid results -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="flex justify-between items-center bg-white px-6 py-4 rounded-2xl border border-[var(--ig-line)] shadow-sm">
                        <span class="text-xs font-bold text-[var(--ig-muted)]">
                            Sourced: {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                        </span>
                        <span class="ig-chip text-[9px] font-bold uppercase tracking-wider">
                            Match Matrix: {{ $targetPositionName }}
                        </span>
                    </div>

                    @if($students->isEmpty())
                        <div class="ig-card p-16 text-center border-dashed">
                            <span class="text-4xl">🔍</span>
                            <h3 class="ig-display text-xl text-[var(--ig-ink)] mt-4">No matching candidates discovered</h3>
                            <p class="text-xs text-[var(--ig-muted)] mt-2 max-w-xs mx-auto">Try loosening some filter options or reset criteria search directory.</p>
                            <a href="{{ route('startup.candidates', ['tab' => 'search', 'position_match' => $positionMatchKey]) }}" class="ig-btn ig-btn-primary text-xs mt-6">
                                Reset Search Parameters
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($students as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="pt-6">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Candidate Slide-over Drawer Overlay -->
    <div id="student-drawer" class="fixed inset-0 z-50 overflow-hidden hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/45 transition-opacity" aria-hidden="true" onclick="closeDrawer()"></div>
            
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-2xl flex flex-col justify-between" id="drawer-panel">
                    
                    <!-- Drawer Header -->
                    <div class="px-6 py-6 border-b border-[var(--ig-line)] bg-[var(--ig-bg-2)] flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[var(--ig-surface-ink)] text-white font-black text-lg flex items-center justify-center rounded-2xl" id="drawer-avatar">
                                ST
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <h2 class="text-lg font-bold text-[var(--ig-ink)] font-poppins" id="drawer-name">Student Name</h2>
                                    <span id="drawer-verified" class="text-[var(--ig-azure)] text-xs font-bold hidden">✓ Verified</span>
                                </div>
                                <p class="text-xs text-[var(--ig-muted)] mt-1 font-semibold" id="drawer-college">College</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full" id="drawer-availability">
                                        Open to work
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="closeDrawer()" class="text-2xl text-[var(--ig-muted)] hover:text-[var(--ig-ink)] leading-none">&times;</button>
                    </div>

                    <!-- Scrollable Drawer Content -->
                    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
                        <!-- About Bio -->
                        <div>
                            <h3 class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Biography</h3>
                            <p class="text-xs text-[var(--ig-ink-2)] leading-relaxed font-normal" id="drawer-bio">
                                Details...
                            </p>
                        </div>

                        <!-- Skills -->
                        <div>
                            <h3 class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2.5">Key Skills Matrix</h3>
                            <div class="flex flex-wrap gap-1.5" id="drawer-skills">
                                <!-- dynamic skills -->
                            </div>
                        </div>

                        <!-- Detailed IPRS Scorecard -->
                        <div>
                            <div class="flex justify-between items-end mb-4">
                                <h3 class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Reputation score card</h3>
                                <div class="flex items-baseline gap-0.5 text-[var(--ig-ink)] font-black">
                                    <span class="text-2xl" id="drawer-overall">92</span>
                                    <span class="text-[10px] text-[var(--ig-muted)] font-medium">/100</span>
                                </div>
                            </div>
                            
                            <div class="space-y-2.5 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-2xl p-4 text-xs text-[var(--ig-ink-2)]">
                                <div class="flex items-center justify-between">
                                    <span>Trust score metric</span>
                                    <div class="flex-1 border-b border-dotted border-slate-300 mx-2"></div>
                                    <span class="font-bold text-[var(--ig-ink)]" id="drawer-trust">95%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Completion rate</span>
                                    <div class="flex-1 border-b border-dotted border-slate-300 mx-2"></div>
                                    <span class="font-bold text-[var(--ig-ink)]" id="drawer-completion">98%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>On-Time rate</span>
                                    <div class="flex-1 border-b border-dotted border-slate-300 mx-2"></div>
                                    <span class="font-bold text-[var(--ig-ink)]" id="drawer-ontime">94%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Satisfaction Rating</span>
                                    <div class="flex-1 border-b border-dotted border-slate-300 mx-2"></div>
                                    <span class="font-bold text-[var(--ig-ink)]" id="drawer-satisfaction">4.8/5</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Communication rating</span>
                                    <div class="flex-1 border-b border-dotted border-slate-300 mx-2"></div>
                                    <span class="font-bold text-[var(--ig-ink)]" id="drawer-communication">4.7/5</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-dashed border-slate-300 pt-2.5 mt-2.5 text-[var(--ig-azure)] font-bold">
                                    <span>Interview Performance (IPS)</span>
                                    <div class="flex-1 border-b border-dotted border-slate-300 mx-2"></div>
                                    <span class="font-black" id="drawer-ips">100%</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-[9px] text-[var(--ig-muted)] bg-white border border-[var(--ig-line-2)] rounded-xl p-3 mt-3 shadow-inner">
                                    <div class="flex justify-between">
                                        <span>Attended:</span>
                                        <span class="font-bold text-[var(--ig-ink)]" id="drawer-attended">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Success Rate:</span>
                                        <span class="font-bold text-[var(--ig-ink)]" id="drawer-success">100%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Strong Candidates:</span>
                                        <span class="font-bold text-[var(--ig-ink)]" id="drawer-strong">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>No Shows:</span>
                                        <span class="font-bold text-[var(--ig-rose)]" id="drawer-noshows">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Experience Ledger -->
                        <div>
                            <h3 class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Experience Ledger Timeline</h3>
                            <div class="space-y-4" id="drawer-ledger">
                                <!-- dynamic portfolio entries -->
                            </div>
                        </div>
                    </div>

                    <!-- Drawer Sticky Footer -->
                    <div class="border-t border-[var(--ig-line)] px-6 py-4 bg-[var(--ig-bg-2)] grid grid-cols-2 gap-4">
                        <button type="button" 
                                onclick="triggerOfferModal('internship')"
                                class="ig-btn ig-btn-ghost justify-center text-xs" style="padding: 10px;">
                            💼 Internship Offer
                        </button>
                        <button type="button" 
                                onclick="triggerOfferModal('job')"
                                class="ig-btn ig-btn-primary justify-center text-xs" style="padding: 10px;">
                            🚀 Full-Time Offer
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modals for extending hiring offers -->
    <!-- Internship Modal -->
    <div id="internship-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="toggleModal('internship-modal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-middle ig-card-dark border-none text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white bg-[var(--ig-surface-ink)]">
                <h2 class="ig-display text-xl mb-4 text-white">💼 Pitch Internship Offer to <span id="internship-student-name" class="text-[var(--ig-lime)]">Student</span></h2>
                
                <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_profile_id" id="internship-student-id">
                    <input type="hidden" name="offer_type" value="internship">
                    <input type="hidden" name="compensation_period" value="monthly">

                    <div>
                        <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Offer Title</label>
                        <input type="text" name="title" required placeholder="e.g. Frontend Development Intern" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Role Description</label>
                        <textarea name="description" required rows="3" placeholder="Outline job duties, deliverables..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">End Date (Optional)</label>
                            <input type="date" name="end_date" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Contract Terms & Benefits</label>
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

    <!-- Job Modal -->
    <div id="job-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="toggleModal('job-modal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-middle ig-card-dark border-none text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white bg-[var(--ig-surface-ink)]">
                <h2 class="ig-display text-xl mb-4 text-white">🚀 Pitch Full-Time Offer to <span id="job-student-name" class="text-[var(--ig-lime)]">Student</span></h2>
                
                <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_profile_id" id="job-student-id">
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="start_date" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>
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

    <!-- Drawer scripting controls -->
    <script>
        let selectedStudent = null;

        function openStudentDrawer(button) {
            const data = JSON.parse(button.getAttribute('data-student'));
            selectedStudent = data;

            document.getElementById('drawer-name').innerText = data.name;
            document.getElementById('drawer-avatar').innerText = data.initials;
            document.getElementById('drawer-college').innerText = '🏫 ' + data.college_name;
            document.getElementById('drawer-bio').innerText = data.bio;

            const verifiedBadge = document.getElementById('drawer-verified');
            if (data.is_verified) {
                verifiedBadge.classList.remove('hidden');
            } else {
                verifiedBadge.classList.add('hidden');
            }

            const availBadge = document.getElementById('drawer-availability');
            availBadge.innerText = '🟢 ' + data.availability_text;
            
            availBadge.className = 'inline-flex items-center text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border ';
            if (data.availability === 'open_to_work') {
                availBadge.classList.add('text-emerald-700', 'bg-emerald-50', 'border-emerald-250');
            } else if (data.availability === 'looking_for_internship') {
                availBadge.classList.add('text-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]', 'border-[var(--ig-accent)]/25');
            } else if (data.availability === 'looking_for_job') {
                availBadge.classList.add('text-blue-700', 'bg-blue-50', 'border-blue-250');
            } else {
                availBadge.classList.add('text-[var(--ig-lime-deep)]', 'bg-[var(--ig-lime)]/10', 'border-[var(--ig-lime)]/30');
            }

            document.getElementById('drawer-overall').innerText = parseFloat(data.overall_score).toFixed(0);
            document.getElementById('drawer-trust').innerText = parseFloat(data.trust_score).toFixed(0) + '%';
            document.getElementById('drawer-completion').innerText = parseFloat(data.completion_rate).toFixed(0) + '%';
            document.getElementById('drawer-ontime').innerText = parseFloat(data.on_time_rate).toFixed(0) + '%';
            document.getElementById('drawer-satisfaction').innerText = parseFloat(data.satisfaction_rating).toFixed(1) + '/5';
            document.getElementById('drawer-communication').innerText = parseFloat(data.communication_rating).toFixed(1) + '/5';
            document.getElementById('drawer-ips').innerText = parseFloat(data.interview_performance_score).toFixed(0) + '%';
            document.getElementById('drawer-attended').innerText = parseInt(data.interviews_attended);
            document.getElementById('drawer-success').innerText = parseFloat(data.interview_success_rate).toFixed(0) + '%';
            document.getElementById('drawer-strong').innerText = parseInt(data.strong_candidate_outcomes);
            document.getElementById('drawer-noshows').innerText = parseInt(data.no_shows);

            const skillsContainer = document.getElementById('drawer-skills');
            skillsContainer.innerHTML = '';
            if (data.skills && data.skills.length > 0) {
                data.skills.forEach(skill => {
                    const pill = document.createElement('span');
                    pill.className = 'text-[10px] font-semibold text-[var(--ig-accent)] bg-[var(--ig-accent-soft)] px-2 py-0.5 rounded-full border border-[var(--ig-accent)]/20';
                    pill.innerText = skill;
                    skillsContainer.appendChild(pill);
                });
            } else {
                skillsContainer.innerHTML = '<span class="text-xs text-gray-400 italic">No skills listed</span>';
            }

            const ledgerContainer = document.getElementById('drawer-ledger');
            ledgerContainer.innerHTML = '';
            if (data.projects && data.projects.length > 0) {
                data.projects.forEach(project => {
                    const item = document.createElement('div');
                    item.className = 'relative pl-5 border-l border-slate-200 py-1.5 text-xs';
                    item.innerHTML = `
                        <div class="absolute -left-[4px] top-2.5 w-2.5 h-2.5 bg-[var(--ig-accent)] rounded-full"></div>
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-900">${project.startup_name}</h4>
                            <span class="text-[9px] font-medium text-gray-500 font-mono">${project.date}</span>
                        </div>
                        <p class="text-[11px] text-gray-650 mt-0.5"><strong>Project:</strong> ${project.project_title}</p>
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center text-yellow-500 font-semibold">
                                ⭐ <span class="ml-1 text-gray-700">${parseFloat(project.rating).toFixed(1)}/5</span>
                            </div>
                            <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-250 px-2 py-0.5 rounded-full uppercase">Verified</span>
                        </div>
                    `;
                    ledgerContainer.appendChild(item);
                });
            } else {
                ledgerContainer.innerHTML = `
                    <div class="text-center py-6 text-gray-400">
                        <span class="text-3xl block mb-1">📂</span>
                        <p class="text-[10px]">No experience ledger records yet.</p>
                    </div>
                `;
            }

            const drawer = document.getElementById('student-drawer');
            const panel = document.getElementById('drawer-panel');
            
            drawer.classList.remove('hidden');
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
                panel.classList.add('translate-x-0');
            }, 50);
        }

        function closeDrawer() {
            const panel = document.getElementById('drawer-panel');
            const drawer = document.getElementById('student-drawer');

            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            
            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 300);
        }

        function triggerOfferModal(type) {
            if (!selectedStudent) return;
            closeDrawer();

            if (type === 'internship') {
                document.getElementById('internship-student-id').value = selectedStudent.id;
                document.getElementById('internship-student-name').innerText = selectedStudent.name;
                toggleModal('internship-modal');
            } else if (type === 'job') {
                document.getElementById('job-student-id').value = selectedStudent.id;
                document.getElementById('job-student-name').innerText = selectedStudent.name;
                toggleModal('job-modal');
            }
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        // Dynamic search filters domain & role synchronization
        const filterDomainSelect = document.getElementById('filter_domain');
        const filterRoleSelect = document.getElementById('filter_role');
        const filterDomainsData = @json(\App\Models\StudentProfile::$domains);
        const oldFilterRole = "{{ request('role') }}";

        if (filterDomainSelect && filterRoleSelect) {
            filterDomainSelect.addEventListener('change', function() {
                const selectedDomain = this.value;
                if (selectedDomain && filterDomainsData[selectedDomain]) {
                    filterRoleSelect.disabled = false;
                    let options = '<option value="">All Roles</option>';
                    filterDomainsData[selectedDomain].forEach(role => {
                        const selected = role === oldFilterRole ? 'selected' : '';
                        options += `<option value="${role}" ${selected}>${role}</option>`;
                    });
                    filterRoleSelect.innerHTML = options;
                } else {
                    filterRoleSelect.disabled = true;
                    filterRoleSelect.innerHTML = '<option value="">All Roles</option>';
                }
            });
        }
    </script>
</x-app-layout>
