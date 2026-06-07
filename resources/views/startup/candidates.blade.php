<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Talent Discovery Hub 🚀</h1>
                <p class="text-gray-500 mt-1">Discover, vet, and hire top-rated student talent based on verified experience.</p>
            </div>
            
            <!-- Position Matching Selector -->
            <form id="match-form" action="{{ route('startup.candidates') }}" method="GET" class="flex-shrink-0">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <!-- Keep existing query filters -->
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
                
                <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm flex items-center space-x-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider pl-1">AI Match Position:</span>
                    <select name="position_match" onchange="this.form.submit()" class="text-sm font-extrabold text-indigo-600 border-none focus:ring-0 p-0 pr-8 cursor-pointer bg-transparent">
                        <optgroup label="Your Posted Tasks / Openings">
                            @foreach($postedTasks as $task)
                                <option value="task_{{ $task->id }}" {{ $positionMatchKey === 'task_'.$task->id ? 'selected' : '' }}>
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Standard Role Profiles">
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
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-sm text-emerald-800 font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl text-sm text-rose-800 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tab Switcher -->
        <div class="flex border-b border-gray-200 mb-8 space-x-8">
            <a href="{{ route('startup.candidates', ['tab' => 'discover', 'position_match' => $positionMatchKey]) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition flex items-center space-x-2 {{ $activeTab === 'discover' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <span>✨ Discovery Hub</span>
            </a>
            <a href="{{ route('startup.candidates', ['tab' => 'search', 'position_match' => $positionMatchKey]) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition flex items-center space-x-2 {{ $activeTab === 'search' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <span>🔍 Search Directory</span>
            </a>
        </div>

        @if($activeTab === 'discover')
            <!-- ================= DISCOVERY HUB VIEW ================= -->
            <div class="space-y-12">
                
                <!-- 1. Recommended For You -->
                @if($recommended->isNotEmpty())
                    <div>
                        <div class="mb-5 flex justify-between items-end border-b border-gray-100 pb-2">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                    <span class="mr-2">🎯</span> Recommended For You
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Students matched against active tasks posted by your startup.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($recommended as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 2. Top Talent of the Month -->
                @if($topTalent->isNotEmpty())
                    <div>
                        <div class="mb-5 flex justify-between items-end border-b border-gray-100 pb-2">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                    <span class="mr-2">🏆</span> Top Talent of the Month
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Candidates with the highest overall IPRS scores on the platform.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($topTalent as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 3. Fastest Growing Students -->
                @if($fastestGrowing->isNotEmpty())
                    <div>
                        <div class="mb-5 flex justify-between items-end border-b border-gray-100 pb-2">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                    <span class="mr-2">📈</span> Fastest Growing Students
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Highly active students with the highest number of completed projects.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($fastestGrowing as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 4. Most Reliable Candidates -->
                @if($mostReliable->isNotEmpty())
                    <div>
                        <div class="mb-5 flex justify-between items-end border-b border-gray-100 pb-2">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                    <span class="mr-2">🛡️</span> Most Reliable Candidates
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Students with a flawless track record in completion rate and on-time task delivery.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($mostReliable as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 5. Specialized Grids (Side-by-side) -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Top PHP Devs -->
                    <div>
                        <div class="mb-5 border-b border-gray-100 pb-2">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <span class="mr-2">🐘</span> Top PHP & Laravel Developers
                            </h2>
                            <p class="text-xs text-gray-500 mt-1">Vetted developers demonstrating high competency in backend code.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($topPhp as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>

                    <!-- Top UI Designers -->
                    <div>
                        <div class="mb-5 border-b border-gray-100 pb-2">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <span class="mr-2">🎨</span> Top UI/UX Designers
                            </h2>
                            <p class="text-xs text-gray-500 mt-1">Highly creative students focused on modern interfaces and Figma prototyping.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($topUi as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- ================= SEARCH DIRECTORY VIEW ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- 🛠️ Sidebar Filters Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm sticky top-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-bold text-gray-900">Filters</h2>
                            <a href="{{ route('startup.candidates', ['tab' => 'search', 'position_match' => $positionMatchKey]) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold">
                                Reset All
                            </a>
                        </div>
                        
                        <form action="{{ route('startup.candidates') }}" method="GET" class="space-y-6">
                            <input type="hidden" name="tab" value="search">
                            <input type="hidden" name="position_match" value="{{ $positionMatchKey }}">

                            <!-- Search -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Search Candidates</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, bio..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <span class="absolute left-3.5 top-3.5 text-gray-400 text-sm">🔍</span>
                                </div>
                            </div>

                            <!-- Skill Filter -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Required Skill</label>
                                <select name="skill_id" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">All Skills</option>
                                    @foreach($allSkills as $skill)
                                        <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- College Filter -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">College / University</label>
                                <input type="text" name="college" value="{{ request('college') }}" placeholder="e.g. Stanford University" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <!-- Min IPRS Filter -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Min IPRS Score</label>
                                    <span id="iprs-val" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ request('min_iprs', 50) }}</span>
                                </div>
                                <input type="range" name="min_iprs" min="50" max="100" value="{{ request('min_iprs', 50) }}" oninput="document.getElementById('iprs-val').innerText = this.value" class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                            </div>

                            <!-- Availability Status checkboxes -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Availability</label>
                                <div class="space-y-2.5">
                                    @php
                                        $avails = [
                                            'open_to_work' => 'Open to Work',
                                            'looking_for_internship' => 'Looking for Internship',
                                            'looking_for_job' => 'Looking for Full-Time',
                                            'freelance_available' => 'Freelance Available'
                                        ];
                                        $selectedAvails = request('availability', []);
                                    @endphp
                                    @foreach($avails as $value => $label)
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="availability[]" value="{{ $value }}" {{ in_array($value, $selectedAvails) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 bg-gray-50 border-gray-300 rounded focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700 font-medium">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bookmarked checkbox -->
                            <div class="pt-4 border-t border-gray-100">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="bookmarked_only" value="1" {{ request('bookmarked_only') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 bg-gray-50 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm text-gray-900 font-bold flex items-center">
                                        ⭐ Saved Candidates Only
                                    </span>
                                </label>
                            </div>

                            <!-- Apply Button -->
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-2xl text-sm transition duration-150 shadow-sm shadow-indigo-100">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 🧑‍🎓 Candidates Grid Panel -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Search Result Summary -->
                    <div class="flex justify-between items-center bg-gray-50 px-6 py-3.5 rounded-2xl border border-gray-100">
                        <span class="text-sm font-semibold text-gray-600">
                            Showing {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} candidates
                        </span>
                        <span class="text-xs font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-wider">
                            Matched against: {{ $targetPositionName }}
                        </span>
                    </div>

                    @if($students->isEmpty())
                        <div class="bg-white rounded-3xl border border-gray-200 p-16 text-center shadow-sm">
                            <span class="text-5xl">🔍</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-4">No matching candidates found</h3>
                            <p class="text-gray-500 mt-2 max-w-sm mx-auto">Try broadening your filters or resetting the search parameters to discover other top student talent.</p>
                            <a href="{{ route('startup.candidates', ['tab' => 'search', 'position_match' => $positionMatchKey]) }}" class="mt-6 inline-block bg-indigo-600 text-white font-bold py-2 px-6 rounded-xl text-sm hover:bg-indigo-700 transition">
                                Reset Search Filters
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($students as $student)
                                @include('startup.partials.candidate-card', ['student' => $student])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- 📊 Candidate Profile Preview Slide Drawer -->
    <div id="student-drawer" class="fixed inset-0 z-50 overflow-hidden hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Background Overlay -->
            <div class="absolute inset-0 bg-slate-950/40 transition-opacity" aria-hidden="true" onclick="closeDrawer()"></div>
            
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto w-screen max-w-md transform translate-x-full transition-transform duration-300 ease-in-out bg-white shadow-2xl flex flex-col justify-between" id="drawer-panel">
                    
                    <!-- Drawer Header & Profile Summary -->
                    <div class="px-6 py-6 border-b border-gray-100 bg-gray-50 flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 bg-indigo-100 text-indigo-700 font-extrabold text-xl flex items-center justify-center rounded-2xl" id="drawer-avatar">
                                ST
                            </div>
                            <div>
                                <div class="flex items-center space-x-1.5">
                                    <h2 class="text-xl font-bold text-gray-900" id="drawer-name">Candidate Name</h2>
                                    <span id="drawer-verified" class="text-blue-500 text-sm hidden" title="Academic Verified Profile">✔️</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 font-medium" id="drawer-college">College Name</p>
                                <div class="mt-2.5">
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full" id="drawer-availability">
                                        Open to work
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="closeDrawer()" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold leading-none">&times;</button>
                    </div>

                    <!-- Scrollable Drawer Content -->
                    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
                        <!-- About Bio -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Biography</h3>
                            <p class="text-sm text-gray-600 leading-relaxed" id="drawer-bio">
                                Developer bio details...
                            </p>
                        </div>

                        <!-- Skills -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Key Skills</h3>
                            <div class="flex flex-wrap gap-1.5" id="drawer-skills">
                                <!-- dynamic skills -->
                            </div>
                        </div>

                        <!-- Detailed IPRS Scorecard (Dot alignments for mobile friendly layout) -->
                        <div>
                            <div class="flex justify-between items-end mb-4">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">IPRS Reputation Scorecard</h3>
                                <div class="flex items-baseline space-x-1 text-gray-900 font-black">
                                    <span class="text-2xl" id="drawer-overall">92</span>
                                    <span class="text-xs text-gray-400 font-medium">/100</span>
                                </div>
                            </div>
                            
                            <div class="space-y-2.5 bg-gray-50 border border-gray-100 rounded-2xl p-4">
                                <!-- Trust Score -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 font-medium">Trust Score</span>
                                    <div class="flex-1 border-b border-dotted border-gray-200 mx-3"></div>
                                    <span class="font-bold text-gray-900" id="drawer-trust">95%</span>
                                </div>
                                <!-- Completion Rate -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 font-medium">Completion Rate</span>
                                    <div class="flex-1 border-b border-dotted border-gray-200 mx-3"></div>
                                    <span class="font-bold text-gray-900" id="drawer-completion">98%</span>
                                </div>
                                <!-- On-Time Rate -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 font-medium">On-Time Rate</span>
                                    <div class="flex-1 border-b border-dotted border-gray-200 mx-3"></div>
                                    <span class="font-bold text-gray-900" id="drawer-ontime">94%</span>
                                </div>
                                <!-- Satisfaction Rating -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 font-medium">Satisfaction Rating</span>
                                    <div class="flex-1 border-b border-dotted border-gray-200 mx-3"></div>
                                    <span class="font-bold text-gray-900" id="drawer-satisfaction">4.8/5</span>
                                </div>
                                <!-- Communication Rating -->
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 font-medium">Communication Rating</span>
                                    <div class="flex-1 border-b border-dotted border-gray-200 mx-3"></div>
                                    <span class="font-bold text-gray-900" id="drawer-communication">4.7/5</span>
                                </div>
                                <!-- Interview Performance (IPS) -->
                                <div class="flex items-center justify-between text-sm border-t border-dashed border-gray-200 pt-2 mt-2">
                                    <span class="text-indigo-650 font-bold">Interview Performance (IPS)</span>
                                    <div class="flex-1 border-b border-dotted border-gray-200 mx-3"></div>
                                    <span class="font-black text-indigo-600" id="drawer-ips">100%</span>
                                </div>
                                <!-- Stats Grid -->
                                <div class="grid grid-cols-2 gap-2 text-[10px] text-gray-500 bg-white border border-gray-100 rounded-xl p-2.5 mt-2 shadow-inner">
                                    <div class="flex justify-between">
                                        <span>Attended:</span>
                                        <span class="font-bold text-gray-950" id="drawer-attended">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Success Rate:</span>
                                        <span class="font-bold text-gray-950" id="drawer-success">100%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Strong Outcomes:</span>
                                        <span class="font-bold text-gray-950" id="drawer-strong">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>No Shows:</span>
                                        <span class="font-bold text-red-500" id="drawer-noshows">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Experience Ledger (Vertical Timeline) -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Experience Ledger</h3>
                            <div class="space-y-4" id="drawer-ledger">
                                <!-- dynamic portfolio items -->
                            </div>
                        </div>
                    </div>

                    <!-- Drawer Sticky Footer CTAs -->
                    <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 grid grid-cols-2 gap-4">
                        <button type="button" 
                                onclick="triggerOfferModal('internship')"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl text-xs text-center transition">
                            💼 Internship Offer
                        </button>
                        <button type="button" 
                                onclick="triggerOfferModal('job')"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-xs text-center transition">
                            🚀 Full-Time Job Offer
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 💼 Internship Offer Modal -->
    <div id="internship-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-950/60 transition-opacity" aria-hidden="true" onclick="toggleModal('internship-modal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-middle bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white">
                <h2 class="text-lg font-bold mb-4 text-white">💼 Extend Internship Offer to <span id="internship-student-name" class="text-indigo-400">Student</span></h2>
                
                <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_profile_id" id="internship-student-id">
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
                        <button type="button" onclick="toggleModal('internship-modal')" class="bg-slate-800 hover:bg-slate-750 border border-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150">Cancel</button>
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
                <h2 class="text-lg font-bold mb-4 text-white">🚀 Extend Full-Time Job Offer to <span id="job-student-name" class="text-indigo-400">Student</span></h2>
                
                <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_profile_id" id="job-student-id">
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
                        <button type="button" onclick="toggleModal('job-modal')" class="bg-slate-800 hover:bg-slate-755 border border-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition duration-150">Send Offer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Javascript Actions and Drawer Controls -->
    <script>
        let selectedStudent = null;

        function openStudentDrawer(button) {
            const data = JSON.parse(button.getAttribute('data-student'));
            selectedStudent = data;

            // Populate header details
            document.getElementById('drawer-name').innerText = data.name;
            document.getElementById('drawer-avatar').innerText = data.initials;
            document.getElementById('drawer-college').innerText = '🏫 ' + data.college_name;
            document.getElementById('drawer-bio').innerText = data.bio;

            // Verified Badge
            const verifiedBadge = document.getElementById('drawer-verified');
            if (data.is_verified) {
                verifiedBadge.classList.remove('hidden');
            } else {
                verifiedBadge.classList.add('hidden');
            }

            // Availability badge
            const availBadge = document.getElementById('drawer-availability');
            availBadge.innerText = '🟢 ' + data.availability_text;
            
            // Availability styles based on value
            availBadge.className = 'inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border ';
            if (data.availability === 'open_to_work') {
                availBadge.classList.add('text-emerald-700', 'bg-emerald-50', 'border-emerald-200');
            } else if (data.availability === 'looking_for_internship') {
                availBadge.classList.add('text-indigo-700', 'bg-indigo-50', 'border-indigo-200');
            } else if (data.availability === 'looking_for_job') {
                availBadge.classList.add('text-blue-700', 'bg-blue-50', 'border-blue-200');
            } else {
                availBadge.classList.add('text-purple-700', 'bg-purple-50', 'border-purple-200');
            }

            // Detailed IPRS Scorecard values
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

            // Key Skills list
            const skillsContainer = document.getElementById('drawer-skills');
            skillsContainer.innerHTML = '';
            if (data.skills && data.skills.length > 0) {
                data.skills.forEach(skill => {
                    const pill = document.createElement('span');
                    pill.className = 'text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-150';
                    pill.innerText = skill;
                    skillsContainer.appendChild(pill);
                });
            } else {
                skillsContainer.innerHTML = '<span class="text-xs text-gray-400 italic">No skills listed</span>';
            }

            // Experience Ledger timeline list
            const ledgerContainer = document.getElementById('drawer-ledger');
            ledgerContainer.innerHTML = '';
            if (data.projects && data.projects.length > 0) {
                data.projects.forEach(project => {
                    const item = document.createElement('div');
                    item.className = 'relative pl-6 border-l-2 border-indigo-100 py-1.5';
                    item.innerHTML = `
                        <div class="absolute -left-[6px] top-2.5 w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-900 text-sm">${project.startup_name}</h4>
                            <span class="text-[10px] font-medium text-gray-500">${project.date}</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-0.5"><strong>Project:</strong> ${project.project_title}</p>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <div class="flex items-center text-yellow-500 font-semibold">
                                ⭐ <span class="ml-1 text-gray-700">${parseFloat(project.rating).toFixed(1)}/5</span>
                            </div>
                            <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full uppercase">Verified</span>
                        </div>
                    `;
                    ledgerContainer.appendChild(item);
                });
            } else {
                ledgerContainer.innerHTML = `
                    <div class="text-center py-6 text-gray-400">
                        <span class="text-3xl block mb-1">📂</span>
                        <p class="text-xs">No experience ledger records yet.</p>
                    </div>
                `;
            }

            // Show drawer wrapper and trigger slide in transition
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
    </script>
</x-app-layout>
