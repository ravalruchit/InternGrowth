<x-app-layout>
    <div class="ig-container max-w-4xl py-12 space-y-8 ig-anim-fade-up">
        <!-- Header -->
        <div class="mb-4">
            <p class="ig-eyebrow mb-2">— Support Desk</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Get in <span class="ig-serif text-[var(--ig-accent)]">Touch.</span>
            </h1>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Left Info Panel -->
            <div class="ig-card p-6 bg-[var(--ig-bg-2)] border-[var(--ig-line)] flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-[var(--ig-ink)] mb-6">Contact Channels</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="p-2.5 rounded-xl bg-white border border-[var(--ig-line)]">
                                <svg class="w-6 h-6 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-[var(--ig-ink)] text-sm">General Email</p>
                                <a href="mailto:info@interngrowth.com" class="text-[var(--ig-azure)] hover:underline text-sm font-medium">info@interngrowth.com</a>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="p-2.5 rounded-xl bg-white border border-[var(--ig-line)]">
                                <svg class="w-6 h-6 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-[var(--ig-ink)] text-sm">Headquarters</p>
                                <p class="text-sm text-[var(--ig-muted)] font-medium leading-relaxed">InternGrowth Platform<br>India</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Info Panel -->
            <div class="ig-card p-6 bg-white border border-[var(--ig-line)] flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-[var(--ig-ink)] mb-6">Quick Resources</h3>
                    <p class="text-sm text-[var(--ig-muted)] mb-4 leading-relaxed">Need help navigating? Check out our quick navigation hubs:</p>
                    <ul class="space-y-3 font-semibold text-sm">
                        <li>
                            <a href="{{ route('dashboard') }}" class="text-[var(--ig-ink)] hover:text-[var(--ig-accent)] transition-colors flex items-center gap-1.5">
                                ⚡ Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tasks.index') }}" class="text-[var(--ig-ink)] hover:text-[var(--ig-accent)] transition-colors flex items-center gap-1.5">
                                📂 Browse Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('leaderboard') }}" class="text-[var(--ig-ink)] hover:text-[var(--ig-accent)] transition-colors flex items-center gap-1.5">
                                🏆 Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Banner CTA -->
        <div class="ig-card-dark p-8 rounded-xl text-center bg-gradient-to-br from-[var(--ig-surface-ink)] to-[#1b2027] space-y-4">
            <h3 class="ig-display text-2xl sm:text-3xl text-white">Have Questions?</h3>
            <p class="text-sm text-[#FAF6EB]/70 max-w-md mx-auto">We're here to help you get growing. Reach out to our customer operations team anytime.</p>
            <a href="mailto:info@interngrowth.com" class="ig-btn ig-btn-lime inline-flex mt-2">
                Send us an Email
            </a>
        </div>
    </div>
</x-app-layout>
