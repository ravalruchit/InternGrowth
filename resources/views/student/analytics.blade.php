<x-app-layout>
    <style>
        .theme-card-label {
            position: relative;
            border: 2px solid var(--ig-line) !important;
            border-radius: 12px !important;
            padding: 12px !important;
            transition: all 0.25s ease !important;
            cursor: pointer;
            background-color: var(--ig-surface);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .theme-card-label:hover {
            border-color: rgba(255, 79, 25, 0.4) !important;
        }
        .theme-card-label.active-theme {
            border-color: var(--ig-accent) !important;
            background-color: rgba(255, 79, 25, 0.04) !important;
            box-shadow: 0 0 0 1px var(--ig-accent) !important;
        }
        .theme-check-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--ig-accent);
            color: #ffffff;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(255, 79, 25, 0.3);
            border: 1px solid #ffffff;
        }
        .theme-card-label.active-theme .theme-check-badge {
            display: flex;
        }
    </style>

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
                <a href="#resume-studio" class="ig-btn ig-btn-primary">
                    <span>Resume Studio</span>
                    <span class="arrow">↓</span>
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12 ig-anim-fade-up ig-delay-1">
            <div class="ig-card p-6">
                <p class="ig-eyebrow mb-1">Tasks Completed</p>
                <p class="ig-stat-num text-4xl mt-3" data-counter="{{ $analytics['completed_tasks'] }}">0</p>
                <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-2">{{ $analytics['pending_tasks'] }} in progress</p>
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

        <!-- Resume Studio Section -->
        <div id="resume-studio" class="ig-card p-8 mb-12 ig-reveal scroll-mt-24">
            <div class="border-b border-[var(--ig-line)] pb-5 mb-8">
                <p class="ig-eyebrow mb-1">Resume Studio 2.0</p>
                <h3 class="ig-display text-3xl text-[var(--ig-ink)]">Verified Experience Resume Engine</h3>
                <p class="text-xs text-[var(--ig-muted)] mt-1">Configure your styling preferences, select templates, and download verified resumes.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Controls -->
                <div class="lg:col-span-5 space-y-6">
                    <form id="resume-settings-form" onchange="saveResumeSettings()">
                        @csrf
                        
                        <!-- Theme selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[var(--ig-muted)] mb-3">1. Select Template Theme</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="theme-card-label select-none relative group {{ $profile->resume_theme === 'ats' ? 'active-theme' : '' }}">
                                    <input type="radio" name="resume_theme" value="ats" class="sr-only" {{ $profile->resume_theme === 'ats' ? 'checked' : '' }} onchange="updateThemeSelection()">
                                    <span class="theme-check-badge">✓</span>
                                    <span class="font-bold text-xs text-[var(--ig-ink)]">ATS Professional</span>
                                    <span class="text-[9px] text-[var(--ig-muted)] mt-1">B&W, Single column, Corporate optimized</span>
                                </label>

                                <label class="theme-card-label select-none relative group {{ $profile->resume_theme === 'startup' ? 'active-theme' : '' }}">
                                    <input type="radio" name="resume_theme" value="startup" class="sr-only" {{ $profile->resume_theme === 'startup' ? 'checked' : '' }} onchange="updateThemeSelection()">
                                    <span class="theme-check-badge">✓</span>
                                    <span class="font-bold text-xs text-[var(--ig-ink)]">Startup Modern</span>
                                    <span class="text-[9px] text-[var(--ig-muted)] mt-1">Colored badges, Project-focused</span>
                                </label>

                                <label class="theme-card-label select-none relative group {{ $profile->resume_theme === 'verified' ? 'active-theme' : '' }}">
                                    <input type="radio" name="resume_theme" value="verified" class="sr-only" {{ $profile->resume_theme === 'verified' ? 'checked' : '' }} onchange="updateThemeSelection()">
                                    <span class="theme-check-badge">✓</span>
                                    <span class="font-bold text-xs text-[var(--ig-ink)]">InternGrowth Verified</span>
                                    <span class="text-[9px] text-[var(--ig-muted)] mt-1">IPRS scores, Earnings, Trust badges</span>
                                </label>

                                <label class="theme-card-label select-none relative group {{ $profile->resume_theme === 'developer' ? 'active-theme' : '' }}">
                                    <input type="radio" name="resume_theme" value="developer" class="sr-only" {{ $profile->resume_theme === 'developer' ? 'checked' : '' }} onchange="updateThemeSelection()">
                                    <span class="theme-check-badge">✓</span>
                                    <span class="font-bold text-xs text-[var(--ig-ink)]">Developer Portfolio</span>
                                    <span class="text-[9px] text-[var(--ig-muted)] mt-1">Two-column, GitHub, LeetCode focus</span>
                                </label>
                            </div>
                        </div>

                        <!-- Customization Toggles -->
                        <div class="pt-4 border-t border-[var(--ig-line)]">
                            <label class="block text-xs font-bold uppercase tracking-wider text-[var(--ig-muted)] mb-3">2. Toggle Customizations</label>
                            <div class="space-y-3">
                                <label class="flex items-center justify-between cursor-pointer select-none">
                                    <span class="text-xs text-[var(--ig-ink-2)] font-semibold">Include IPRS Reputation Score</span>
                                    <input type="checkbox" name="show_iprs" value="1" {{ $profile->show_iprs ? 'checked' : '' }} class="rounded text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]">
                                </label>

                                <label class="flex items-center justify-between cursor-pointer select-none">
                                    <span class="text-xs text-[var(--ig-ink-2)] font-semibold">Include Stipend Earnings</span>
                                    <input type="checkbox" name="show_stipends" value="1" {{ $profile->show_stipends ? 'checked' : '' }} class="rounded text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]">
                                </label>

                                <label class="flex items-center justify-between cursor-pointer select-none">
                                    <span class="text-xs text-[var(--ig-ink-2)] font-semibold">Include Startup Ratings</span>
                                    <input type="checkbox" name="show_ratings" value="1" {{ $profile->show_ratings ? 'checked' : '' }} class="rounded text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]">
                                </label>

                                <label class="flex items-center justify-between cursor-pointer select-none">
                                    <span class="text-xs text-[var(--ig-ink-2)] font-semibold">Include Social & Professional Links</span>
                                    <input type="checkbox" name="show_social_links" value="1" {{ $profile->show_social_links ? 'checked' : '' }} class="rounded text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]">
                                </label>

                                <label class="flex items-center justify-between cursor-pointer select-none">
                                    <span class="text-xs text-[var(--ig-ink-2)] font-semibold">Include Profile Photo (if supported by theme)</span>
                                    <input type="checkbox" name="show_profile_photo" value="1" {{ $profile->show_profile_photo ? 'checked' : '' }} class="rounded text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]">
                                </label>

                                <label class="flex items-center justify-between cursor-pointer select-none pt-2 border-t border-[var(--ig-line)]/50">
                                    <span class="text-xs text-[var(--ig-ink-2)] font-semibold text-[var(--ig-rose)]">Hide Low-Rated Projects (under 4.0 stars)</span>
                                    <input type="checkbox" id="hide_low_rated" value="1" class="rounded text-[var(--ig-rose)] focus:ring-[var(--ig-rose)]">
                                </label>
                            </div>
                        </div>
                    </form>

                    <div class="pt-4 border-t border-[var(--ig-line)] flex items-center gap-3">
                        <button onclick="triggerPrint()" class="ig-btn ig-btn-primary flex-1 justify-center py-3">
                            <svg style="width: 18px; height: 18px;" class="flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print / Export PDF</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Preview -->
                <div class="lg:col-span-7 flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[var(--ig-muted)]">Live Resume Preview</label>
                        <a id="full-preview-btn" href="{{ route('student.cv.download', ['theme' => $profile->resume_theme]) }}" target="_blank" class="ig-btn py-1.5 px-4 text-xs bg-[var(--ig-accent)] text-white hover:bg-[#E03E0B] flex items-center gap-1.5">
                            <span>Open Full Preview ↗</span>
                        </a>
                    </div>
                    <div class="flex-1 bg-neutral-900 border border-[var(--ig-line)] rounded-2xl overflow-hidden min-h-[480px] flex flex-col relative">
                        <!-- Top browser bar representation -->
                        <div class="h-8 bg-neutral-800 border-b border-neutral-700 flex items-center px-4 gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        </div>
                        <iframe id="resume-preview-iframe" src="{{ route('student.cv.download', ['theme' => $profile->resume_theme]) }}" class="w-full flex-1 border-0 bg-white"></iframe>
                    </div>
                </div>
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
                            @if($task->task->stipend)
                                <p class="ig-display text-2xl text-[var(--ig-lime-deep)] font-bold">₹{{ number_format($task->task->stipend, 0) }}</p>
                            @else
                                <p class="text-xs text-[var(--ig-muted)] font-medium">Experience Task</p>
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

    <script>
        function updateThemeSelection() {
            document.querySelectorAll('.theme-card-label').forEach(label => {
                const radio = label.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    label.classList.add('active-theme');
                } else {
                    label.classList.remove('active-theme');
                }
            });
        }

        function saveResumeSettings() {
            const form = document.getElementById('resume-settings-form');
            const formData = new FormData(form);
            
            // Add unchecked checkboxes manually as false/0
            const checkboxes = ['show_iprs', 'show_stipends', 'show_ratings', 'show_social_links', 'show_profile_photo'];
            checkboxes.forEach(cb => {
                if (!formData.has(cb)) {
                    formData.append(cb, 0);
                }
            });

            // Ensure show_certificates is always updated to 0/false bcz we removed it
            formData.append('show_certificates', 0);

            fetch("{{ route('student.cv.settings') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const theme = document.querySelector('input[name="resume_theme"]:checked').value;
                    const hideLow = document.getElementById('hide_low_rated').checked ? 1 : 0;
                    let previewUrl = "{{ route('student.cv.download') }}?theme=" + theme + "&hide_low_rated=" + hideLow;
                    document.getElementById('resume-preview-iframe').src = previewUrl;
                    
                    const fullPreviewBtn = document.getElementById('full-preview-btn');
                    if (fullPreviewBtn) {
                        fullPreviewBtn.href = previewUrl;
                    }
                    
                    updateThemeSelection();
                }
            })
            .catch(error => console.error('Error saving settings:', error));
        }

        function triggerPrint() {
            const iframe = document.getElementById('resume-preview-iframe');
            if (iframe) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        }

        // Initialize auto-refresh on hide_low_rated click
        document.getElementById('hide_low_rated').addEventListener('change', saveResumeSettings);

        // Initial setup on page load
        document.addEventListener('DOMContentLoaded', updateThemeSelection);
    </script>
</x-app-layout>
