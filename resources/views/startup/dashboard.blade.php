<x-app-layout>
    <div class="ig-container py-12 ig-anim-fade-up space-y-8">
        
        <!-- Flash Messages & Validation Errors -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success text-sm">
                <p class="font-bold text-emerald-950">✓ {{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-950 font-bold shadow-sm">
                <p>⚠️ {{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="ig-banner mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-sm text-red-955 font-semibold shadow-sm">
                <p class="font-black text-red-950 mb-1.5">Please fix the following issues:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Welcome Header & Info -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="ig-eyebrow mb-2">— Startup Console</p>
                <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                    Welcome, <span class="ig-serif text-[var(--ig-accent)]">{{ $profile->company_name }}!</span>
                </h1>
                <p class="text-sm text-[var(--ig-muted)] mt-1.5">Manage your microtasks, student offers and wallet credentials.</p>
            </div>

            <!-- Messages Button -->
            <div class="flex-shrink-0">
                <a href="{{ route('messages.index') }}" class="ig-btn ig-btn-ghost">
                    💬 Inbox Messages
                </a>
            </div>
        </div>

        <!-- Simulated Skeleton Loader Wrapper -->
        <div id="dashboard-skeleton" class="space-y-8 animate-pulse">
            <!-- Banner Skeleton -->
            <div class="h-24 bg-slate-100 rounded-3xl w-full"></div>
            
            <!-- Stats Skeleton Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                <div class="h-36 bg-slate-100 rounded-3xl sm:col-span-2"></div>
                <div class="h-36 bg-slate-100 rounded-3xl"></div>
                <div class="h-36 bg-slate-100 rounded-3xl"></div>
                <div class="h-36 bg-slate-100 rounded-3xl"></div>
            </div>

            <!-- Main grid block Skeleton -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="h-64 bg-slate-100 rounded-3xl"></div>
                <div class="h-64 bg-slate-100 rounded-3xl lg:col-span-2"></div>
            </div>
        </div>

        <div id="dashboard-content" class="hidden space-y-8">
            {{-- Verification warning banner --}}
        @if(!$profile->is_verified)
            <div class="ig-banner ig-banner-warn">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full gap-4">
                    <div>
                        <p class="font-bold text-amber-955 text-sm">⚠️ Account Pending Verification</p>
                        <p class="text-xs text-amber-900 mt-0.5">
                            @if($profile->verification_status === 'pending' && $profile->verification_submitted_at)
                                Your verification documents are currently under administrative review.
                            @elseif($profile->verification_status === 'rejected')
                                Your registration details were rejected. Please check feedback and resubmit.
                            @else
                                Submit your legal incorporation paperwork to post active tasks.
                            @endif
                        </p>
                    </div>
                    @if($profile->verification_status !== 'pending' || !$profile->verification_submitted_at)
                        <a href="{{ route('startup.verification') }}" class="ig-btn ig-btn-accent text-xs" style="padding: 8px 18px;">
                            {{ $profile->verification_status === 'rejected' ? 'Resubmit' : 'Get Verified' }}
                        </a>
                    @endif
                </div>
            </div>
        @elseif(session()->has('startup_just_verified_' . auth()->id()))
            <div id="verified-alert" class="ig-banner ig-banner-success text-sm">
                <div>
                    <p class="font-bold text-emerald-955">🎉 Account Verified!</p>
                    <p class="text-emerald-900 mt-0.5">Your incorporation documents have been approved. You are ready to start listing tasks.</p>
                </div>
            </div>
            <script>
                const verifiedAlert = document.getElementById('verified-alert');
                if (verifiedAlert) {
                    setTimeout(() => {
                        verifiedAlert.style.transition = 'opacity 0.5s ease-out';
                        verifiedAlert.style.opacity = '0';
                        setTimeout(() => {
                            verifiedAlert.remove();
                            fetch('{{ route("startup.clear-verification-alert") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            });
                        }, 500);
                    }, 3000);
                }
            </script>
        @endif

        <!-- Stats Overview Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            <!-- Balance Card -->
            <div class="ig-card-dark p-6 relative overflow-hidden shadow-xl sm:col-span-2 flex flex-col justify-between min-h-[140px]">
                <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-30" style="background:var(--ig-accent)"></div>
                <div class="relative">
                    <p class="ig-eyebrow text-[10px] text-white/50 mb-1">Available Funds</p>
                    <p class="ig-display text-3xl sm:text-4xl text-white">₹{{ number_format($profile->wallet_balance, 2) }}</p>
                </div>
                <div class="flex gap-4 text-xs font-semibold relative z-10">
                    <a href="{{ route('wallet.index') }}" class="text-[var(--ig-lime)] hover:underline">Transactions →</a>
                    <a href="{{ route('wallet.topup') }}" class="text-white hover:underline">+ Top-up Account</a>
                </div>
            </div>

            <!-- Posted Tasks count -->
            <div class="ig-card p-5 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Posted Tasks</p>
                <p class="ig-display text-4xl text-[var(--ig-ink)] mt-3">{{ $tasks->count() }}</p>
                <p class="text-[10px] text-[var(--ig-faint)] mt-2 font-mono">active listings</p>
            </div>

            <!-- Total Applications count -->
            <div class="ig-card p-5 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Applications</p>
                <p class="ig-display text-4xl text-[var(--ig-ink)] mt-3">{{ $tasks->sum(fn($t) => $t->applications->count()) }}</p>
                <p class="text-[10px] text-[var(--ig-faint)] mt-2 font-mono">submitted resumes</p>
            </div>

            <!-- Credibility Rating -->
            <div class="ig-card p-5 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Credibility</p>
                <p class="ig-display text-4xl text-[var(--ig-ink)] mt-3">{{ number_format($profile->credibility_score * 100, 0) }}%</p>
                <p class="text-[10px] text-[var(--ig-faint)] mt-2 font-mono">reputation score</p>
            </div>
        </div>

        <!-- Startup Subscription & Usage Limits Widget -->
        <div class="ig-card p-6" style="border: 1px solid var(--ig-line);">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Left: Plan Info -->
                <div class="space-y-2" style="flex: 1;">
                    <p class="ig-eyebrow mb-1">— Plan & Subscriptions</p>
                    <div class="flex items-center gap-3">
                        @if(auth()->user()->isStartupGrowth())
                            <span class="text-xs font-black uppercase bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full border border-indigo-150">🚀 Startup Growth Active</span>
                            <span class="text-xs text-slate-500 font-semibold">{{ auth()->user()->activeSubscription()->daysRemaining() }} Days Remaining</span>
                        @else
                            <span class="text-xs font-black uppercase bg-slate-100 text-slate-700 px-3 py-1 rounded-full border border-slate-200">⚡ Startup Free Plan</span>
                            <span class="text-xs text-red-500 font-bold">Postings Limited</span>
                        @endif
                    </div>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5 leading-relaxed">
                        @if(auth()->user()->isStartupGrowth())
                            Your Startup Growth subscription is active. You have unlimited task postings, team analytics access, and waived success fees on all internships.
                        @else
                            You are currently on the Startup Free tier. You can list up to 3 active tasks and your first internship placement is free. Subsequent placements carry a success fee of ₹1,999.
                        @endif
                    </p>
                </div>

                <!-- Center: Usage Indicators -->
                <div class="flex-shrink-0 grid grid-cols-2 gap-6 border-l border-r border-[var(--ig-line)] px-8" style="min-width: 280px;">
                    <div>
                        <span class="text-[9px] uppercase font-black text-gray-400 block mb-1">Active Tasks</span>
                        @php
                            $activeTasksCount = \App\Models\Task::where('startup_profile_id', auth()->user()->startupProfile->id)
                                ->whereIn('status', ['posted', 'in_progress'])
                                ->count();
                        @endphp
                        <span class="text-xl font-extrabold text-slate-800">
                            {{ $activeTasksCount }} / {{ auth()->user()->isStartupGrowth() ? 'Unlimited' : '3' }}
                        </span>
                        @if(!auth()->user()->isStartupGrowth())
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1.5">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: {{ ($activeTasksCount / 3) * 100 }}%"></div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <span class="text-[9px] uppercase font-black text-gray-400 block mb-1">Hires Sent</span>
                        @php
                            $hiresCount = \App\Models\HiringOffer::where('startup_profile_id', auth()->user()->startupProfile->id)->count();
                        @endphp
                        <span class="text-xl font-extrabold text-slate-800">
                            {{ $hiresCount }} / {{ auth()->user()->isStartupGrowth() ? 'Unlimited' : '1' }}
                        </span>
                        @if(!auth()->user()->isStartupGrowth())
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1.5">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min(100, ($hiresCount / 1) * 100) }}%"></div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Upgrade / Management action buttons -->
                <div class="flex-shrink-0 flex flex-col gap-2.5 items-stretch" style="min-width: 185px;">
                    @if(auth()->user()->isStartupGrowth())
                        <a href="{{ route('pricing.index') }}" class="ig-btn ig-btn-ghost text-xs text-center font-bold py-2.5">Pricing Matrix</a>
                        <button type="button" onclick="openCancelSubscriptionModal()" class="ig-btn text-xs font-bold bg-red-50 hover:bg-red-100 text-red-650 px-5 py-2.5 rounded-xl border border-red-200/50 w-full text-center cursor-pointer">
                            Downgrade Plan
                        </button>
                    @else
                        <a href="{{ route('pricing.index') }}" class="ig-btn text-xs font-bold bg-indigo-600 text-white hover:bg-indigo-750 px-5 py-3 rounded-2xl w-full text-center block no-underline border-none">
                            Upgrade to Growth Plan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- ─── Sourcing Analytics & Performance Widgets ─── -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Avg Candidate Match Score -->
            <div class="ig-card p-6 flex flex-col justify-between border-t-4 border-t-[var(--ig-accent)] shadow-sm bg-gradient-to-br from-white to-[var(--ig-accent-soft)]/20">
                <div>
                    <p class="ig-eyebrow text-[10px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Avg Candidate Match Score</p>
                    <p class="ig-stat-num text-4xl text-[var(--ig-ink)] mt-3">{{ $averageMatchScore }}%</p>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-[var(--ig-accent)] h-full rounded-full transition-all duration-500" style="width: {{ $averageMatchScore }}%"></div>
                    </div>
                    <p class="text-[10px] text-[var(--ig-muted)] mt-2 font-semibold">Average compatibility score across all applications in funnel</p>
                </div>
            </div>

            <!-- Top Performing Domain -->
            <div class="ig-card p-6 flex flex-col justify-between shadow-sm">
                <div>
                    <p class="ig-eyebrow text-[10px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Top Performing Domain</p>
                    <p class="text-xl font-bold text-[var(--ig-ink)] mt-4 font-poppins leading-snug">{{ $topPerformingDomain }}</p>
                </div>
                <div class="mt-4 text-[10px] text-[var(--ig-muted)] font-semibold border-t border-[var(--ig-line-2)] pt-2.5 flex items-center gap-1.5">
                    🏆 Category yielding highest placement and completion rate
                </div>
            </div>

            <!-- Best Hiring Category -->
            <div class="ig-card p-6 flex flex-col justify-between border-b-4 border-b-[var(--ig-lime-deep)] shadow-sm bg-gradient-to-tr from-white to-[var(--ig-lime)]/10">
                <div>
                    <p class="ig-eyebrow text-[10px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Best Hiring Category</p>
                    <p class="text-xl font-bold text-[var(--ig-ink)] mt-4 font-poppins leading-snug">{{ $mostSuccessfulHiringCategory }}</p>
                </div>
                <div class="mt-4 text-[10px] text-[var(--ig-muted)] font-semibold border-t border-[var(--ig-line-2)] pt-2.5 flex items-center gap-1.5">
                    🎯 Preferred role with most successfully approved hires
                </div>
            </div>
        </div>

        <!-- Trust Breakdown & Reviews Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Trust Score Breakdown -->
            <div class="ig-card p-6 lg:col-span-1 space-y-4 bg-white">
                @php
                    $ts = $profile->trustScore;
                    $overall = $ts ? $ts->overall_score : ($profile->credibility_score * 100);
                    if ($overall <= 0) $overall = 100;
                @endphp
                <div class="flex justify-between items-center border-b border-[var(--ig-line)] pb-3">
                    <h3 class="text-sm font-bold text-[var(--ig-ink)]">🛡️ Trust Index Score</h3>
                    <span class="ig-chip ig-chip-lime font-mono text-xs font-bold">{{ number_format($overall, 0) }} / 100</span>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Verification (25%)</span>
                            <span class="text-[var(--ig-ink)] font-bold">{{ $ts ? number_format($ts->verification_score, 0) : ($profile->is_verified ? '100' : '0') }}%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[var(--ig-accent)] h-1.5 rounded-full" style="width: {{ $ts ? $ts->verification_score : ($profile->is_verified ? '100' : '0') }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Payment Reliability (25%)</span>
                            <span class="text-[var(--ig-ink)] font-bold">{{ $ts ? number_format($ts->payment_score, 0) : ($profile->wallet_balance < 0 ? '50' : '100') }}%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? '50' : '100') }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Rating Score (20%)</span>
                            <span class="text-[var(--ig-ink)] font-bold">{{ $ts ? number_format($ts->student_rating_score, 0) : '100' }}%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ $ts ? $ts->student_rating_score : '100' }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Offers & Placement (15%)</span>
                            <span class="text-[var(--ig-ink)] font-bold">{{ $ts ? number_format($ts->hiring_score, 0) : '100' }}%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[var(--ig-forest)] h-1.5 rounded-full" style="width: {{ $ts ? $ts->hiring_score : '100' }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Reviews Listing -->
            <div class="ig-card p-6 lg:col-span-2 space-y-4 bg-white">
                <h3 class="text-sm font-bold text-[var(--ig-ink)] border-b border-[var(--ig-line)] pb-3">⭐ Student Reviews ({{ $profile->reviews->count() }})</h3>
                <div class="space-y-4 max-h-[200px] overflow-y-auto pr-2">
                    @forelse($profile->reviews as $review)
                        <div class="border-b border-[var(--ig-line)] pb-4 last:border-none last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-sm text-[var(--ig-ink)]">{{ $review->student->user->name }}</h4>
                                    <p class="text-[10px] text-[var(--ig-muted)] mt-0.5">Project: {{ $review->task->title }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-yellow-500 text-xs font-bold">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating) ★ @else ☆ @endif
                                        @endfor
                                    </span>
                                    <p class="text-[10px] text-[var(--ig-faint)] mt-0.5">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-[var(--ig-ink-2)] italic leading-normal">"{{ $review->review }}"</p>
                        </div>
                    @empty
                        <div class="text-center py-8 text-[var(--ig-muted)] text-xs">
                            <span>No student reviews received yet.</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Recruitment & Conversion Funnel Analytics -->
        @php
            $allApplications = $tasks->flatMap(fn($t) => $t->applications);
            $appliedCount = $allApplications->count();
            $assignedCount = $allApplications->filter(fn($app) => in_array($app->status, ['approved', 'internship_offered', 'internship_accepted', 'hired']) || ($app->submission && $app->submission->status === 'accepted'))->count();
            $completedCount = $allApplications->filter(fn($app) => $app->submission && $app->submission->status === 'accepted')->count();
            $interviewedCount = $allApplications->filter(fn($app) => in_array($app->startup_hiring_outcome, ['interview_scheduled', 'interview_passed', 'interview_failed', 'hired_intern', 'hired_job']))->count();
            $hiredCount = $allApplications->filter(fn($app) => in_array($app->startup_hiring_outcome, ['hired_intern', 'hired_job']))->count() + $hiringOffers->where('status', 'accepted')->count();

            $completionRate = $assignedCount > 0 ? ($completedCount / $assignedCount) * 100 : 0;
            $hiringRate = $completedCount > 0 ? ($hiredCount / $completedCount) * 100 : 0;
        @endphp
        <div class="ig-card p-6 bg-white space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--ig-line)] pb-4">
                <div>
                    <p class="ig-eyebrow mb-1">— Pipeline Analytics</p>
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)]">Recruitment & Conversion Funnel</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="ig-chip ig-chip-lime text-[10px] font-bold uppercase tracking-wider">Conversion Rates</span>
                </div>
            </div>

            <!-- Funnel Stepper UI -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-center items-stretch">
                <!-- Step 1 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">1. Applied</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono">{{ $appliedCount }}</p>
                    </div>
                    <p class="text-[10px] text-[var(--ig-faint)] mt-2">Task applicants</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">2. Assigned</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono">{{ $assignedCount }}</p>
                    </div>
                    @if($appliedCount > 0)
                        <p class="text-[10px] text-[var(--ig-accent)] font-bold mt-2">{{ number_format(($assignedCount / $appliedCount) * 100, 0) }}% conversion</p>
                    @else
                        <p class="text-[10px] text-[var(--ig-faint)] mt-2">working candidates</p>
                    @endif
                </div>
                <!-- Step 3 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">3. Completed</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono">{{ $completedCount }}</p>
                    </div>
                    <p class="text-[10px] text-emerald-700 font-bold mt-2">{{ number_format($completionRate, 0) }}% completion</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">4. Interviewed</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono">{{ $interviewedCount }}</p>
                    </div>
                    @if($completedCount > 0)
                        <p class="text-[10px] text-[var(--ig-accent)] font-bold mt-2">{{ number_format(($interviewedCount / $completedCount) * 100, 0) }}% conversion</p>
                    @else
                        <p class="text-[10px] text-[var(--ig-faint)] mt-2">pipeline selection</p>
                    @endif
                </div>
                <!-- Step 5 -->
                <div class="bg-emerald-50/40 border border-emerald-150 rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-emerald-800 block mb-1">5. Hired / Placed</span>
                        <p class="ig-display text-3xl text-emerald-750 font-mono">{{ $hiredCount }}</p>
                    </div>
                    <p class="text-[10px] text-emerald-700 font-bold mt-2">{{ number_format($hiringRate, 0) }}% hiring success</p>
                </div>
            </div>
        </div>

        <!-- Domain-Specific Hiring & Acquisition Analytics -->
        <div class="ig-card p-6 bg-white space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--ig-line)] pb-4">
                <div>
                    <p class="ig-eyebrow mb-1">— Acquisition Analytics</p>
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)]">Multi-Domain Ecosystem Analytics</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="ig-chip ig-chip-accent text-[10px] font-bold uppercase tracking-wider">Live Metrics</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Applications & Success Rates Grid -->
                <div class="lg:col-span-2 space-y-5">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-1 flex items-center gap-2">
                        📊 Applications and Placement Success by Domain
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach(['Software Development' => '💻', 'UI/UX Design' => '🎨', 'Digital Marketing' => '📈', 'Data & AI' => '🤖', 'Content & Business' => '✍️'] as $domName => $domIcon)
                            @php
                                $apps = $applicationsByDomain[$domName] ?? 0;
                                $hires = $hiringSuccessByDomain[$domName] ?? 0;
                                $maxApps = max(1, max(array_values($applicationsByDomain)));
                                $progressWidth = min(100, ($apps / $maxApps) * 100);
                            @endphp
                            <div class="bg-[var(--ig-bg)]/40 hover:bg-[var(--ig-bg)]/70 transition border border-[var(--ig-line)] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">{{ $domIcon }}</span>
                                        <span class="font-bold text-sm text-[var(--ig-ink)]">{{ $domName }}</span>
                                    </div>
                                    <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-2 overflow-hidden">
                                        <div class="bg-[var(--ig-accent)] h-2 rounded-full transition-all duration-500" style="width: {{ $progressWidth }}%"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6 text-right sm:text-left">
                                    <div class="min-w-[80px]">
                                        <p class="text-[10px] uppercase font-bold text-[var(--ig-muted)]">Applications</p>
                                        <p class="text-base font-bold text-[var(--ig-ink)] font-mono">{{ $apps }}</p>
                                    </div>
                                    <div class="min-w-[80px] border-l border-[var(--ig-line-2)] pl-4">
                                        <p class="text-[10px] uppercase font-bold text-[var(--ig-muted)]">Hired / Placed</p>
                                        <p class="text-base font-bold text-emerald-700 font-mono flex items-center gap-1">
                                            {{ $hires }}
                                            @if($hires > 0)
                                                <span class="text-xs text-emerald-600 font-normal">🎉</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Domain Rankings & Performers -->
                <div class="lg:col-span-1 bg-[var(--ig-bg)]/30 border border-[var(--ig-line)] rounded-2xl p-5 space-y-4">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider border-b border-[var(--ig-line)] pb-3 flex items-center gap-2">
                        🏆 Top Performing Domains
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($topPerformingDomains as $index => $domName)
                            @php
                                $domIcons = [
                                    'Software Development' => '💻',
                                    'UI/UX Design' => '🎨',
                                    'Digital Marketing' => '📈',
                                    'Data & AI' => '🤖',
                                    'Content & Business' => '✍️'
                                ];
                                $icon = $domIcons[$domName] ?? '💼';
                                $rank = $index + 1;
                                $hires = $hiringSuccessByDomain[$domName] ?? 0;
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-[var(--ig-line-2)] hover:border-[var(--ig-ink)] transition duration-200">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs font-mono
                                        {{ $rank === 1 ? 'bg-amber-100 text-amber-800 border border-amber-300' : ($rank === 2 ? 'bg-slate-100 text-slate-800 border border-slate-300' : 'bg-orange-50 text-orange-800') }}">
                                        #{{ $rank }}
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs">{{ $icon }}</span>
                                            <span class="font-bold text-xs text-[var(--ig-ink)]">{{ $domName }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="ig-chip {{ $hires > 0 ? 'ig-chip-success' : 'ig-chip' }} text-[9px] font-bold font-mono">
                                        {{ $hires }} Hires
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiring Pipeline / Sent Offers -->
        <div class="ig-card p-6 overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)]">Direct Acquisition Offers</h2>
                    <p class="text-[10px] text-[var(--ig-muted)] font-semibold mt-1 uppercase tracking-wider">🔒 Non-Circumvention Policy: Direct hiring of InternGrowth candidates off-platform within 12 months is subject to a flat ₹20,000 buyout fee.</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[var(--ig-line)] pb-4 text-[var(--ig-muted)]">
                            <th class="ig-eyebrow pb-4">Candidate</th>
                            <th class="ig-eyebrow pb-4">Offer Type</th>
                            <th class="ig-eyebrow pb-4">Compensation</th>
                            <th class="ig-eyebrow pb-4">Start Date</th>
                            <th class="ig-eyebrow pb-4">Status</th>
                            <th class="ig-eyebrow pb-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)]">
                        @forelse($hiringOffers as $offer)
                            <tr class="hover:bg-[var(--ig-bg)] transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[var(--ig-surface-ink)] text-white font-bold flex items-center justify-center text-xs uppercase">
                                            {{ substr($offer->student->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('students.public-profile', $offer->student_profile_id) }}" target="_blank" class="font-bold text-[var(--ig-ink)] hover:text-[var(--ig-accent)] transition">
                                                {{ $offer->student->user->name }}
                                            </a>
                                            <p class="text-[10px] text-[var(--ig-muted)]">{{ $offer->student->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span class="ig-chip text-[10px] font-bold uppercase tracking-wider {{ $offer->offer_type === 'internship' ? 'ig-chip-accent' : 'ig-chip-ink' }}">
                                        {{ $offer->offer_type }}
                                    </span>
                                </td>
                                <td class="py-4 font-semibold text-[var(--ig-ink)]">
                                     @if($offer->status === 'countered')
                                         <div class="flex flex-col">
                                             <span class="line-through text-[11px] text-[var(--ig-muted)]">₹{{ number_format($offer->compensation, 0) }}</span>
                                             <span class="text-xs font-bold text-amber-600">Counter: ₹{{ number_format($offer->counter_compensation, 0) }}/{{ $offer->compensation_period === 'annual' ? 'yr' : 'mo' }}</span>
                                             @if($offer->counter_note)
                                                 <span class="text-[9px] font-normal text-[var(--ig-muted)] italic max-w-[150px] truncate" title="{{ $offer->counter_note }}">"{{ $offer->counter_note }}"</span>
                                             @endif
                                         </div>
                                     @else
                                         ₹{{ number_format($offer->compensation, 0) }}/{{ $offer->compensation_period === 'annual' ? 'yr' : 'mo' }}
                                     @endif
                                 </td>
                                 <td class="py-4 text-[var(--ig-muted)]">
                                     {{ $offer->start_date->format('M d, Y') }}
                                 </td>
                                 <td class="py-4">
                                     @if($offer->status === 'pending')
                                         <span class="ig-chip text-[10px] font-bold ig-chip-warn">
                                             Pending
                                         </span>
                                     @elseif($offer->status === 'countered')
                                         <span class="ig-chip text-[10px] font-bold ig-chip-warn bg-yellow-50/50 border border-yellow-200 text-yellow-800">
                                             Counter Proposed
                                         </span>
                                     @elseif($offer->status === 'pending_joining')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-warn">
                                            Pending Joining
                                        </span>
                                    @elseif($offer->status === 'joined')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-success">
                                            Joined / Active
                                        </span>
                                    @elseif($offer->status === 'completed')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-lime">
                                            Completed
                                        </span>
                                    @elseif($offer->status === 'rejected')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-danger">
                                            Declined
                                        </span>
                                    @elseif($offer->status === 'withdrawn')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-ink">
                                            Withdrawn
                                        </span>
                                    @elseif($offer->status === 'cancelled_by_student')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-danger">
                                            Cancelled by Student
                                        </span>
                                    @elseif($offer->status === 'withdrawn_by_startup')
                                        <span class="ig-chip text-[10px] font-bold ig-chip-danger">
                                            Cancelled by Startup
                                        </span>
                                    @else
                                        <span class="ig-chip text-[10px] font-bold">
                                            {{ str_replace('_', ' ', $offer->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    @if($offer->status === 'pending')
                                        <form method="POST" action="{{ route('startup.offers.withdraw', $offer->id) }}" onsubmit="return confirm('Are you sure you want to withdraw this offer?');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[var(--ig-rose)] font-bold hover:underline bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition text-[10px]">
                                                Withdraw
                                            </button>
                                        </form>
                                    @elseif($offer->status === 'countered')
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('startup.offers.counter.accept', $offer->id) }}" onsubmit="return confirm('Are you sure you want to accept this counter offer? This will reserve the success fee.');" class="inline">
                                                @csrf
                                                <button type="submit" class="text-[var(--ig-lime-deep)] font-bold hover:underline bg-emerald-50 hover:bg-emerald-100 border border-emerald-250 px-2.5 py-1.5 rounded-lg transition text-[10px]">
                                                    Accept Counter
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('startup.offers.counter.reject', $offer->id) }}" onsubmit="return confirm('Are you sure you want to decline this counter offer?');" class="inline">
                                                @csrf
                                                <button type="submit" class="text-[var(--ig-rose)] font-bold hover:underline bg-red-50 hover:bg-red-100 border border-red-250 px-2.5 py-1.5 rounded-lg transition text-[10px]">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($offer->status === 'pending_joining')
                                        @if($offer->startup_joining_status === 'pending')
                                            <div class="flex gap-2">
                                                <form method="POST" action="{{ route('offers.cancel-joining', $offer->id) }}" onsubmit="return confirm('Are you sure the candidate did not join? This will refund your reserved success fee of ₹{{ number_format($offer->reserved_fee, 2) }}.');" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[var(--ig-rose)] font-bold hover:underline bg-red-50 hover:bg-red-100 border border-red-250 px-2.5 py-1.5 rounded-lg transition text-[10px]">
                                                        Did Not Join
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('offers.confirm-joining', $offer->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[var(--ig-lime-deep)] font-bold hover:underline bg-emerald-50 hover:bg-emerald-100 border border-emerald-250 px-2.5 py-1.5 rounded-lg transition text-[10px]">
                                                        Joined
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($offer->startup_joining_status === 'joined')
                                            <div class="flex flex-col gap-1.5 items-start">
                                                <span class="text-[10px] text-[var(--ig-muted)] italic">Awaiting Student Confirm...</span>
                                                <form method="POST" action="{{ route('offers.cancel-joining', $offer->id) }}" onsubmit="return confirm('Are you sure you want to cancel this placement?');" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[var(--ig-rose)] font-bold hover:underline bg-red-50 hover:bg-red-100 border border-red-250 px-2.5 py-1.5 rounded-lg transition text-[10px]">
                                                        Withdraw Placement
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @elseif($offer->status === 'joined')
                                        <button onclick="document.getElementById('complete-modal-{{ $offer->id }}').showModal()" class="ig-btn ig-btn-lime text-[10px] py-1.5 px-3">
                                            Mark Completed
                                        </button>
                                        
                                        <dialog id="complete-modal-{{ $offer->id }}" class="rounded-2xl p-6 bg-white border border-[var(--ig-line)] max-w-md w-full shadow-2xl backdrop:bg-black/50 text-left">
                                            <div class="space-y-4">
                                                <h3 class="ig-display text-xl text-[var(--ig-ink)]">Complete Internship</h3>
                                                <p class="text-xs text-[var(--ig-muted)] font-poppins">Mark the placement for <strong>{{ $offer->student->user->name }}</strong> as completed. This will generate an Experience Certificate and update their profile.</p>
                                                
                                                <form method="POST" action="{{ route('offers.complete-internship', $offer->id) }}" class="space-y-4">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-xs font-bold text-[var(--ig-muted)] mb-1 uppercase">Hiring Success Rating</label>
                                                        <select name="rating" required class="w-full rounded-xl border-[var(--ig-line)] text-xs p-2.5 bg-white text-[var(--ig-ink)]">
                                                            <option value="excellent">Excellent (5.0 / 5.0)</option>
                                                            <option value="good" selected>Good (4.0 / 5.0)</option>
                                                            <option value="average">Average (3.0 / 5.0)</option>
                                                            <option value="poor">Poor (2.0 / 5.0)</option>
                                                            <option value="terminated">Terminated (2.0 / 5.0)</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block text-xs font-bold text-[var(--ig-muted)] mb-1 uppercase">Completion Notes / Review</label>
                                                        <textarea name="notes" placeholder="Describe the student's performance, achievements, and responsibilities..." class="w-full rounded-xl border-[var(--ig-line)] text-xs p-2.5 h-24 text-[var(--ig-ink)]" max="1000"></textarea>
                                                    </div>
                                                    
                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" onclick="document.getElementById('complete-modal-{{ $offer->id }}').close()" class="ig-btn text-xs" style="background:transparent;border:1px solid var(--ig-line-2);color:var(--ig-ink)">Cancel</button>
                                                        <button type="submit" class="ig-btn ig-btn-lime text-xs">Complete & Certify</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </dialog>
                                    @elseif($offer->status === 'completed')
                                        <span class="text-xs text-[var(--ig-lime)] font-semibold flex items-center gap-1">
                                            <span>✓ Certified</span>
                                        </span>
                                    @else
                                        <span class="text-gray-400 font-medium">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[var(--ig-muted)] text-sm">
                                    No direct hiring offers extended yet. Use the Talent Discovery Hub to pitch to top talent.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My Tasks Section -->
        <div class="ig-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="ig-display text-2xl text-[var(--ig-ink)]">My Posted Tasks</h2>
                @if($profile->is_verified)
                    <a href="{{ route('tasks.create') }}" class="ig-btn ig-btn-primary text-xs" style="padding: 10px 22px;">
                        + Post New Task
                    </a>
                @else
                    <button disabled class="ig-btn text-xs opacity-50 cursor-not-allowed" style="padding: 10px 22px;" title="Account verification required to post tasks">
                        + Post New Task
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse($tasks as $task)
                    @php
                        $approvedApp = $task->applications()->where('status', 'approved')->first();
                        $completedApp = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->first();
                    @endphp
                    <div class="border border-[var(--ig-line)] rounded-2xl p-5 hover:border-[var(--ig-ink)] transition duration-200 group">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2.5 mb-2">
                                    <h3 class="font-bold text-lg text-[var(--ig-ink)] group-hover:text-[var(--ig-accent)] transition-colors">{{ $task->title }}</h3>
                                    @if($task->status === 'completed' || $completedApp)
                                        <span class="ig-chip ig-chip-success text-[9px] font-bold">✓ Completed</span>
                                    @elseif($approvedApp)
                                        <span class="ig-chip ig-chip-warn text-[9px] font-bold">⏳ In Progress</span>
                                    @else
                                        <span class="ig-chip ig-chip-lime text-[9px] font-bold">📢 Open</span>
                                    @endif
                                </div>
                                
                                @if($completedApp)
                                    <p class="text-xs text-emerald-800 mb-2 font-medium">
                                        ✓ Completed by: <strong>{{ $completedApp->student->user->name }}</strong>
                                    </p>
                                @elseif($approvedApp)
                                    <p class="text-xs text-amber-800 mb-2 font-medium">
                                        ⏳ Assigned to: <strong>{{ $approvedApp->student->user->name }}</strong>
                                    </p>
                                @endif
                                
                                <p class="text-xs text-[var(--ig-muted)] font-mono">
                                    {{ $task->applications->count() }} applications · @if($task->stipend) ₹{{ number_format($task->stipend, 0) }} stipend @else Experience Task @endif
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('tasks.show', $task->id) }}" class="ig-btn ig-btn-ghost text-xs" style="padding: 8px 16px;">
                                    View Applications
                                </a>
                                @if($task->applications()->where('status', 'approved')->count() === 0)
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="ig-btn ig-btn-ghost text-xs hover:border-[var(--ig-ink)]" style="padding: 8px 16px;">
                                        Edit
                                    </a>
                                @else
                                    <span class="ig-chip text-[10px] font-medium" title="Cannot edit after approving an application">Locked</span>
                                @endif
                                @if($task->applications->count() === 0)
                                    <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ig-btn text-xs text-white bg-[var(--ig-rose)] border-none hover:bg-red-700" style="padding: 8px 16px;">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <x-empty-states.no-tasks />
                @endforelse
            </div>
        </div>

        <!-- Completed Tasks History -->
        @if($completedTasks->count() > 0)
            <div class="ig-card p-6 space-y-6">
                <h2 class="ig-display text-2xl text-[var(--ig-ink)] flex items-center space-x-2 border-b border-[var(--ig-line)] pb-4">
                    <span>✓ Completed Tasks History</span>
                </h2>
                <div class="space-y-4">
                    @foreach($completedTasks as $task)
                        @php
                            $completedApp = $task->applications->filter(function($app) {
                                return $app->submission && $app->submission->status === 'accepted';
                            })->first();
                        @endphp
                        @if($completedApp)
                            <div class="border border-emerald-150 bg-emerald-50/20 rounded-2xl p-5 hover:shadow-sm transition">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3.5 mb-2">
                                            <h3 class="font-bold text-lg text-[var(--ig-ink)]">{{ $task->title }}</h3>
                                            <span class="ig-chip ig-chip-success text-[9px] font-bold">Completed</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 text-xs text-[var(--ig-ink-2)] bg-white/60 border border-[var(--ig-line-2)] rounded-xl p-4">
                                            <div>
                                                <p>Completed by: <strong>{{ $completedApp->student->user->name }}</strong></p>
                                                <p class="mt-1">Student Reliability: <span class="text-emerald-700 font-bold">{{ number_format($completedApp->student->reliability_score * 100, 0) }}%</span></p>
                                                @if($task->stipend)
                                                    <p class="mt-1">Stipend Released: <strong>₹{{ number_format($task->stipend, 0) }}</strong></p>
                                                @else
                                                    <p class="mt-1">Escrow Released: <strong>Experience Task</strong></p>
                                                @endif
                                            </div>
                                            <div>
                                                <p>Completed on: <strong>{{ $completedApp->submission->updated_at->format('M d, Y') }}</strong></p>
                                                @if($task->ratings && $task->ratings->where('student_profile_id', $completedApp->student_profile_id)->first())
                                                    @php
                                                        $rating = $task->ratings->where('student_profile_id', $completedApp->student_profile_id)->first();
                                                    @endphp
                                                    <p class="mt-1">Your Rating:
                                                        <span class="text-yellow-500 font-bold">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $rating->rating) ★ @else ☆ @endif
                                                            @endfor
                                                        </span>
                                                    </p>
                                                    @if($rating->review)
                                                        <p class="mt-1.5 text-[11px] text-[var(--ig-muted)] italic">"{{ Str::limit($rating->review, 80) }}"</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        @if($completedApp->submission->submission_url)
                                            <p class="text-xs text-[var(--ig-muted)] mb-3">
                                                Submission Proof: 
                                                <a href="{{ $completedApp->submission->submission_url }}" target="_blank" class="text-[var(--ig-azure)] font-bold hover:underline">
                                                    View Completed Work →
                                                </a>
                                            </p>
                                        @endif

                                        <div class="flex items-center gap-3 mt-4 text-xs font-semibold">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="text-[var(--ig-accent)] hover:underline">
                                                Full Details →
                                            </a>
                                            <a href="{{ route('messages.index') }}" class="text-[var(--ig-muted)] hover:underline">
                                                Message Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
    </div>

    <!-- Custom Subscription Downgrade Confirmation Modal -->
    <div id="cancelSubscriptionModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <!-- Backdrop overlay -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCancelSubscriptionModal()"></div>

        <!-- Modal panel -->
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-md border border-slate-100 ig-anim-scale-in z-10">
            <div class="p-6">
                <!-- Icon & Title -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 text-2xl shadow-inner">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-950 text-base" id="modal-title">Downgrade to Startup Free</h3>
                        <p class="text-xs text-slate-550 mt-0.5">Are you sure you want to cancel your Growth plan?</p>
                    </div>
                </div>

                <!-- Warning Content -->
                <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 text-xs text-red-800 leading-relaxed mb-6">
                    <p class="font-bold mb-1">Downgrading will restrict:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Unlimited task postings (maximum capped to 3 active tasks)</li>
                        <li>Waived placements (subsequent internship hires carry ₹1,999 success fee)</li>
                        <li>Advanced talent search ranking and matching tools</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeCancelSubscriptionModal()" class="flex-1 ig-btn ig-btn-ghost py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                        Keep Growth Active
                    </button>
                    <form method="POST" action="{{ route('pricing.cancel') }}" class="flex-1 m-0">
                        @csrf
                        <button type="submit" class="w-full ig-btn justify-center text-xs font-bold bg-red-600 hover:bg-red-700 text-white border-none py-3 rounded-2xl cursor-pointer">
                            Confirm Downgrade
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCancelSubscriptionModal() {
            const modal = document.getElementById('cancelSubscriptionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeCancelSubscriptionModal() {
            const modal = document.getElementById('cancelSubscriptionModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                const skeleton = document.getElementById('dashboard-skeleton');
                const content = document.getElementById('dashboard-content');
                if (skeleton && content) {
                    skeleton.classList.add('hidden');
                    content.classList.remove('hidden');
                    content.classList.add('ig-anim-fade-up');
                }
            }, 500);
        });
    </script>

    </div><!-- Closing dashboard-content -->

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tasks.create') }}" class="fixed bottom-6 right-6 flex items-center justify-center gap-2 px-5 py-3.5 bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold rounded-full shadow-2xl hover:scale-105 transition-all z-40 group no-underline" style="color: #ffffff !important;">
        <span class="text-lg">+</span>
        <span class="text-xs uppercase tracking-wider">Post Task</span>
    </a>
</x-app-layout>
