@props(['compact' => false])

@if(auth()->check() && auth()->user()->isStudent())
    @php
        $student = auth()->user()->studentProfile;
        $score = $student->reputationScore;
        $iprs = $score ? round($score->overall_score) : 50;
        $verifiedTasks = $score ? $score->total_verified_projects : 0;
        $reviewsCount = $student->ratings()->count();
        $internshipOffers = $student->hiringOffers()->where('offer_type', 'internship')->count();
    @endphp

    @if($compact)
        <!-- Compact Banner Mode -->
        <div class="relative overflow-hidden bg-[var(--ig-surface-ink)] rounded-2xl border border-[var(--ig-line-2)] px-5 py-4 shadow-lg text-white transition hover:scale-[1.005] flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--ig-accent)] to-[#E03E0B] flex items-center justify-center shadow-md shadow-[var(--ig-accent)]/10 flex-shrink-0">
                <span class="text-lg">💡</span>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-black uppercase tracking-widest text-[var(--ig-accent)] mb-0.5">Reputation Tip</h4>
                <p class="text-xs text-slate-300 leading-relaxed font-normal">
                    Work speaks louder than resumes. Every verified task builds your IPRS, expands your experience record, and increases visibility to hiring startups.
                </p>
            </div>
        </div>
    @else
        <!-- Full Premium Reputation Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-[var(--ig-ink)] via-slate-900 to-[var(--ig-accent)]/10 rounded-3xl border border-[var(--ig-accent)]/25 p-6 md:p-8 shadow-2xl backdrop-blur-md text-white transition-all duration-300 hover:scale-[1.005] hover:shadow-[var(--ig-accent)]/10 group">
            <!-- Background Decorative Gradients -->
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-[var(--ig-lime)]/10 rounded-full blur-2xl group-hover:bg-[var(--ig-lime)]/20 transition-all duration-500"></div>
            <div class="absolute -left-16 -bottom-16 w-36 h-36 bg-[var(--ig-accent)]/10 rounded-full blur-2xl group-hover:bg-[var(--ig-accent)]/20 transition-all duration-500"></div>

            <div class="flex flex-col md:flex-row gap-6 items-start relative z-10">
                <!-- Badge Icon -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[var(--ig-accent)] to-[#E03E0B] flex items-center justify-center shadow-lg shadow-[var(--ig-accent)]/20 flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>

                <div class="flex-1 space-y-5">
                    <!-- Header -->
                    <div>
                        <h3 class="text-xl md:text-2xl font-black tracking-tight text-white mb-2.5">
                            🚀 <span class="bg-gradient-to-r from-[var(--ig-accent)] via-orange-100 to-[var(--ig-accent-soft)] bg-clip-text text-transparent font-black">Your Reputation Is Your Resume</span>
                        </h3>
                        <p class="text-sm text-slate-350 leading-relaxed font-normal">
                            Every verified task completed through InternGrowth contributes to your professional growth. Build your IPRS Reputation Score, Experience Ledger, Startup Reviews, Internship History, and Job Opportunities through real work. Startups trust verified experience more than traditional resumes.
                        </p>
                    </div>

                    <!-- Personalized Statistics Section -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--ig-accent)] to-[var(--ig-accent-soft)]">{{ $iprs }}</span>
                            <span class="text-[9px] font-bold text-[var(--ig-accent-soft)] uppercase tracking-wider">Current IPRS</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--ig-accent)] to-[var(--ig-accent-soft)]">{{ $verifiedTasks }}</span>
                            <span class="text-[9px] font-bold text-[var(--ig-accent-soft)] uppercase tracking-wider">Verified Tasks</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--ig-accent)] to-[var(--ig-accent-soft)]">{{ $reviewsCount }}</span>
                            <span class="text-[9px] font-bold text-[var(--ig-accent-soft)] uppercase tracking-wider">Startup Reviews</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[var(--ig-accent)] to-[var(--ig-accent-soft)]">{{ $internshipOffers }}</span>
                            <span class="text-[9px] font-bold text-[var(--ig-accent-soft)] uppercase tracking-wider">Internship Offers</span>
                        </div>
                    </div>

                    <!-- Benefits List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2.5 text-xs md:text-sm font-semibold text-slate-200">
                        <div class="flex items-center space-x-2">
                            <span class="text-emerald-400 font-bold text-base">✓</span>
                            <span>Increase your IPRS Reputation Score</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-emerald-400 font-bold text-base">✓</span>
                            <span>Build a Verified Experience Ledger</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-emerald-400 font-bold text-base">✓</span>
                            <span>Earn Startup Reviews & Recommendations</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-emerald-400 font-bold text-base">✓</span>
                            <span>Unlock Better Internship Opportunities</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-emerald-400 font-bold text-base">✓</span>
                            <span>Improve Job Placement Chances</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-emerald-400 font-bold text-base">✓</span>
                            <span>Create a Professional Work History</span>
                        </div>
                    </div>

                    <!-- Important Notice -->
                    <div class="bg-[var(--ig-accent-soft)]/10 border border-[var(--ig-accent)]/15 rounded-2xl p-4 text-xs text-[var(--ig-accent-soft)] leading-relaxed font-medium">
                        <span class="font-black text-white uppercase tracking-wider block mb-1">📢 Important Notice</span>
                        Work completed outside InternGrowth cannot be verified and will not contribute to your IPRS, Experience Ledger, Startup Reviews, or Hiring History. Keep your work and hiring journey on the platform to maximize your professional profile and future opportunities.
                    </div>               </div>
                </div>
            </div>
        </div>
    @endif
@endif
