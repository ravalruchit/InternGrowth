<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Talent Operations</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    My <span class="ig-serif text-[var(--ig-accent)]">Team.</span><br>
                    Post-hire management and progress tracking.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('startup.dashboard') }}" class="ig-btn ig-btn-ghost">
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

        <!-- Row 1: Analytics Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 ig-reveal is-in">
            <!-- Active Interns -->
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500">Active Interns</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $analytics['active_interns'] }}</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Currently working in team</p>
                </div>
            </div>
            <!-- Completed Internships -->
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500">Completed Internships</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $analytics['completed_internships'] }}</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Successfully certified alumni</p>
                </div>
            </div>
            <!-- Avg Rating -->
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500">Average Student Rating</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">⭐ {{ $analytics['avg_rating'] }} / 5.0</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Based on weekly feedbacks</p>
                </div>
            </div>
            <!-- Conversion Rate -->
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500">Full-Time Conversion Rate</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $analytics['conversion_rate'] }}%</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">{{ $analytics['conversions'] }} converted employees</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div x-data="{ activeTab: 'active' }" class="space-y-6">
            <div class="flex space-x-2 border-b border-[var(--ig-line)] pb-1">
                <button @click="activeTab = 'active'" 
                        :class="activeTab === 'active' ? 'border-[var(--ig-accent)] text-[var(--ig-accent)] font-bold' : 'border-transparent text-gray-500 hover:text-gray-900'" 
                        class="px-4 py-2 border-b-2 text-sm transition font-medium">
                    Active Interns ({{ $activeOffers->count() }})
                </button>
                <button @click="activeTab = 'previous'" 
                        :class="activeTab === 'previous' ? 'border-[var(--ig-accent)] text-[var(--ig-accent)] font-bold' : 'border-transparent text-gray-500 hover:text-gray-900'" 
                        class="px-4 py-2 border-b-2 text-sm transition font-medium">
                    Previous Hires & Alumni ({{ $previousOffers->count() }})
                </button>
            </div>

            <!-- Tab 1: Active Interns -->
            <div x-show="activeTab === 'active'" class="space-y-6">
                @forelse($activeOffers as $offer)
                    <div class="ig-card p-6 md:p-8 hover:shadow-md transition duration-300">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                            
                            <!-- Left Block: Intern Info & Progress -->
                            <div class="lg:col-span-8 space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-full bg-[var(--ig-accent-soft)] flex items-center justify-center font-bold text-[var(--ig-accent)] text-xl border border-[var(--ig-accent)]/10 shadow-inner uppercase">
                                        {{ substr($offer->student->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-2xl text-[var(--ig-ink)]">{{ $offer->student->user->name }}</h3>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->role }} — <span class="font-semibold text-[var(--ig-accent)]">{{ $offer->domain }}</span></p>
                                    </div>
                                    <span class="ml-auto ig-chip ig-chip-accent">IPRS: {{ round($offer->student->reputationScore->overall_score ?? 50) }}</span>
                                </div>

                                <!-- Progress Percentage Bar -->
                                <div class="space-y-1.5 pt-2">
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-gray-500">Internship Progress</span>
                                        <span class="text-[var(--ig-accent)]">{{ $offer->progress_pct }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-[var(--ig-accent-soft)] to-[var(--ig-accent)] h-2 rounded-full transition-all duration-500" style="width: {{ $offer->progress_pct }}%"></div>
                                    </div>
                                </div>

                                <!-- Key placement parameters & Verified Task Stats -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-semibold bg-slate-50 border border-slate-100 rounded-xl p-4">
                                    <div>
                                        <p class="text-[9px] uppercase text-gray-400">— Streak</p>
                                        <p class="text-gray-800 font-bold mt-0.5">🔥 {{ $offer->current_streak ?? 0 }} Days</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase text-gray-400">— Approved Tasks</p>
                                        <p class="text-emerald-700 font-bold mt-0.5">✅ {{ $offer->approved_tasks_count }} Tasks</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase text-gray-400">— Performance</p>
                                        <p class="text-sky-700 font-bold mt-0.5">⭐ {{ $offer->performance_score }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase text-gray-400">— Stipend</p>
                                        <p class="text-gray-800 font-bold mt-0.5">₹{{ number_format($offer->compensation, 0) }} / {{ $offer->compensation_period }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Block: Operational actions -->
                            <div class="lg:col-span-4 flex flex-col gap-3">
                                <p class="text-xs uppercase font-extrabold text-[var(--ig-muted)] tracking-wider mb-1">— Operations Center</p>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('students.public-profile', $offer->student_profile_id) }}" target="_blank" class="ig-btn ig-btn-ghost justify-center text-xs py-2">
                                        📁 Profile
                                    </a>
                                    <a href="{{ route('messages.create', ['studentId' => $offer->student_profile_id, 'startupId' => $offer->startup_profile_id, 'taskId' => $offer->source_task_id ?? '']) }}" class="ig-btn ig-btn-ghost justify-center text-xs py-2">
                                        💬 Message
                                    </a>
                                </div>

                                <a href="{{ route('startup.team.work', $offer->id) }}" class="ig-btn justify-center text-xs py-2.5 font-bold shadow-sm text-center">
                                    💼 Open Workspace
                                </a>

                                <!-- Trigger complete internship -->
                                <button onclick="toggleCompletionForm({{ $offer->id }})" class="ig-btn justify-center text-xs py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold border-none mt-2">
                                    ✓ Complete & Certify Internship
                                </button>

                                <!-- Completion rating form -->
                                <div id="completion-form-{{ $offer->id }}" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 mt-2 space-y-4">
                                    <form method="POST" action="{{ route('offers.complete-internship', $offer->id) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Evaluate Performance</label>
                                            <select name="rating" required class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent">
                                                <option value="excellent">🏆 Excellent (5/5)</option>
                                                <option value="good" selected>🌟 Good (4/5)</option>
                                                <option value="average">⭐ Average (3/5)</option>
                                                <option value="poor">⚠️ Poor (2/5)</option>
                                                <option value="terminated">❌ Terminated (1/5)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Feedback Notes / Review</label>
                                            <textarea name="notes" placeholder="Review details to be displayed on student certificate and experience ledger..." rows="3" required
                                                      class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs resize-none"></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition text-center shadow-sm">
                                            Certify & Close Internship
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                        <p class="font-bold text-base text-[var(--ig-ink)]">No active interns.</p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1">Hired candidates who confirm joining will display here as active members.</p>
                    </div>
                @endforelse
            </div>

            <!-- Tab 2: Previous Hires -->
            <div x-show="activeTab === 'previous'" class="space-y-6" style="display: none;">
                @forelse($previousOffers as $offer)
                    <div class="ig-card p-6 md:p-8 hover:shadow-sm transition duration-300">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            
                            <!-- Left: Profile and stats -->
                            <div class="lg:col-span-8 space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-lg uppercase border border-slate-200">
                                        {{ substr($offer->student->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-xl text-[var(--ig-ink)]">{{ $offer->student->user->name }}</h3>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->role }} — Completed {{ $offer->completed_at ? $offer->completed_at->format('M Y') : '' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-x-8 gap-y-2 text-xs font-semibold text-gray-500">
                                    <p>Performance Score: <strong class="text-[var(--ig-accent)]">{{ $offer->internship_score }}/100</strong></p>
                                    <p>Final Grade: <strong class="text-emerald-700">{{ ucfirst($offer->hiring_success_rating) }}</strong></p>
                                    <p>Duration: <strong>{{ $offer->start_date->format('M Y') }} — {{ $offer->completed_at ? $offer->completed_at->format('M Y') : '' }}</strong></p>
                                </div>
                            </div>

                            <!-- Right: Actions -->
                            <div class="lg:col-span-4 flex flex-col gap-2">
                                @if($offer->converted_to_full_time)
                                    <span class="w-full text-center inline-block bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-extrabold py-2.5 px-4 rounded-xl">
                                        🎉 Converted to Full-Time Employee
                                    </span>
                                @else
                                    <a href="{{ route('startup.team.convert.form', $offer->id) }}" class="w-full ig-btn justify-center text-xs py-2.5 bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold border-none shadow-sm text-center">
                                        💼 Offer Full-Time Conversion
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                        <p class="font-bold text-base text-[var(--ig-ink)]">No previous certified hires.</p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1">Once you complete internships, candidate history will render here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function toggleCompletionForm(offerId) {
            const container = document.getElementById('completion-form-' + offerId);
            if (container) {
                container.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
