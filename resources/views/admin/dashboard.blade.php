<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations Control</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Admin <span class="ig-serif text-[var(--ig-accent)]">Dashboard.</span><br>
                    Ecosystem metrics & control panels.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <p class="text-sm text-[var(--ig-muted)] max-w-xs md:ml-auto">
                    Central coordination hub for talent verification, startup credentials, task moderation, and financial adjustments.
                </p>
            </div>
        </div>
        
        <!-- Alerts & Flags -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 ig-reveal">
            <div class="ig-card p-6 flex flex-col justify-between">
                <div>
                    <p class="ig-eyebrow mb-1">Pending Startups</p>
                    <p class="ig-stat-num text-4xl text-[var(--ig-accent)] mt-3">{{ $pendingStartups }}</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5">New business profiles waiting for document validation.</p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.startups') }}" class="ig-btn ig-btn-ghost w-full justify-center">
                        <span>Review Profiles</span>
                        <span class="arrow">→</span>
                    </a>
                </div>
            </div>
            <div class="ig-card p-6 flex flex-col justify-between">
                <div>
                    <p class="ig-eyebrow mb-1">Flagged Tasks</p>
                    <p class="ig-stat-num text-4xl text-red-600 mt-3">{{ $flaggedTasks }}</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5">Offers and deliverables reported or blocked by system rules.</p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.tasks') }}" class="ig-btn ig-btn-ghost w-full justify-center text-red-600 border-red-200/60 hover:text-white hover:bg-red-600 hover:border-red-600">
                        <span>Moderate Tasks</span>
                        <span class="arrow">→</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Admin Control Panels Grid -->
        <h2 class="ig-display text-2xl mb-6 pb-4 border-b border-[var(--ig-line)] ig-reveal">Control Panels</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ig-reveal">
            <!-- Manage Students -->
            <a href="{{ route('admin.students') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">01</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">Manage Students</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">View profiles, check activity, and verify accounts manually.</p>
                </div>
            </a>

            <!-- Manage Startups -->
            <a href="{{ route('admin.startups') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">02</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">Manage Startups</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Approve startup companies and view their billing details.</p>
                </div>
            </a>

            <!-- Verify Startups documents -->
            <a href="{{ route('admin.verifications') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">03</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">Verify Startups</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Review official business credentials and verify profiles.</p>
                </div>
            </a>

            <!-- AI ID Queue -->
            <a href="{{ route('admin.student-id-queue') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">04</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">🤖 AI ID Queue</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Review student ID submissions flagged by AI scanner.</p>
                </div>
            </a>

            <!-- Moderate Tasks -->
            <a href="{{ route('admin.tasks') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">05</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">Moderate Tasks</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Audit task details, stipends, and flags to maintain safety.</p>
                </div>
            </a>

            <!-- Manage Wallets -->
            <a href="{{ route('admin.wallets') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">06</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">Manage Wallets</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Audit user ledger histories and execute manual point/escrow adjustments.</p>
                </div>
            </a>

            <!-- Topup Requests -->
            <a href="{{ route('admin.topup.index') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">07</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">Topup Requests</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Approve or reject bank deposit verification tokens.</p>
                </div>
            </a>

            <!-- AI Debugger -->
            <a href="{{ route('admin.ai-debug') }}" class="ig-card-dark p-6 hover:scale-[1.02] transition-transform duration-350 border border-[var(--ig-line-2)] flex flex-col justify-between h-48 group">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-[var(--ig-muted)] font-bold group-hover:text-white transition">08</span>
                </div>
                <div>
                    <h3 class="ig-display text-lg text-white font-semibold">🤖 AI Debugger</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Run diagnostic calls to Gemini model APIs and check response structures.</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
