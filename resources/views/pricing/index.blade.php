<x-app-layout>
    <div class="ig-container py-12">
        <!-- Hero Section -->
        <div class="text-center max-w-2xl mx-auto mb-16 ig-anim-fade-up">
            <p class="ig-eyebrow mb-2">— Monetization Model</p>
            <h1 class="ig-display text-4xl md:text-6xl font-bold leading-tight text-[var(--ig-ink)]">
                One Platform. <br>
                <span class="ig-serif text-[var(--ig-accent)]">Learn, Work, Get Hired.</span>
            </h1>
            <p class="text-sm text-[var(--ig-muted)] mt-4">
                InternGrowth is free to start. Upgrade to unlock advanced AI-powered career coaching, verified badges, and startup growth operations.
            </p>
        </div>

        @if(session('error'))
            <div class="ig-banner mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-900 font-bold shadow-sm"><p>⚠️ {{ session('error') }}</p></div>
        @endif

        <div x-data="{ planType: 'student' }" class="space-y-12">
            <!-- Tabs -->
            <div class="flex justify-center">
                <div class="bg-[var(--ig-bg-2)] p-1 rounded-2xl border border-[var(--ig-line)] inline-flex">
                    <button @click="planType = 'student'" :class="planType === 'student' ? 'bg-white shadow-sm text-[var(--ig-ink)]' : 'text-[var(--ig-muted)]'"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">Student Plans</button>
                    <button @click="planType = 'startup'" :class="planType === 'startup' ? 'bg-white shadow-sm text-[var(--ig-ink)]' : 'text-[var(--ig-muted)]'"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">Startup Plans</button>
                </div>
            </div>

            <!-- Student Pricing Cards -->
            <div x-show="planType === 'student'" class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="ig-card p-8 flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                    <div>
                        <p class="ig-eyebrow mb-2">— Student Free</p>
                        <h3 class="font-extrabold text-2xl text-[var(--ig-ink)]">Free Tier</h3>
                        <div class="my-6">
                            <span class="text-4xl font-black text-[var(--ig-ink)] font-poppins">₹0</span>
                            <span class="text-xs text-[var(--ig-muted)] font-medium">/ forever</span>
                        </div>
                        <ul class="space-y-3.5 text-xs text-[var(--ig-ink-2)]" style="line-height: 1.5;">
                            <li class="flex items-center gap-2">✓ Unlimited profile creation</li>
                            <li class="flex items-center gap-2">✓ Apply to marketplace tasks & internships</li>
                            <li class="flex items-center gap-2">✓ Shareable portfolio generation</li>
                            <li class="flex items-center gap-2">✓ Earn IPRS scores</li>
                            <li class="flex items-center gap-2">✓ Standard experience certificates</li>
                        </ul>
                    </div>
                    <div class="mt-8 pt-6 border-t border-[var(--ig-line)]">
                        @if($user && $user->isStudent() && !$user->isStudentPro())
                            <span class="w-full text-center block bg-emerald-50 text-emerald-800 font-bold py-3 rounded-2xl text-xs border border-emerald-100">Your Current Plan</span>
                        @else
                            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost w-full justify-center text-xs py-3 font-bold">Start Free</a>
                        @endif
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="ig-card p-8 flex flex-col justify-between relative overflow-hidden" style="border: 2px solid var(--ig-accent);">
                    <div class="absolute -top-3 -right-3 bg-[var(--ig-accent)] text-white text-[8px] uppercase tracking-widest font-black py-2.5 px-6 rounded-bl-2xl">Most Popular</div>
                    <div>
                        <p class="ig-eyebrow mb-2" style="color:var(--ig-accent);">— Student Premium</p>
                        <h3 class="font-extrabold text-2xl text-[var(--ig-ink)]">InternGrowth Pro</h3>
                        <div class="my-6">
                            <span class="text-4xl font-black text-[var(--ig-ink)] font-poppins">₹99</span>
                            <span class="text-xs text-[var(--ig-muted)] font-medium">/ month</span>
                        </div>
                        <ul class="space-y-3.5 text-xs text-[var(--ig-ink-2)]" style="line-height: 1.5;">
                            <li class="flex items-center gap-2 text-indigo-700 font-bold">⭐ AI Career Pathing & Coach chatbot</li>
                            <li class="flex items-center gap-2 text-indigo-700 font-bold">⭐ Technical Assessments & Verified Badges</li>
                            <li class="flex items-center gap-2">✓ Mock Interview Preparation tools</li>
                            <li class="flex items-center gap-2">✓ Advanced analytics & portfolio ranking</li>
                            <li class="flex items-center gap-2">✓ Premium profile highlight on startup search</li>
                            <li class="flex items-center gap-2">✓ Exclusive streak multipliers (+15% IPRS)</li>
                        </ul>
                    </div>
                    <div class="mt-8 pt-6 border-t border-[var(--ig-line)]">
                        @if($user && $user->isStudentPro())
                            <div class="space-y-3">
                                <span class="w-full text-center block bg-emerald-50 text-emerald-800 font-bold py-3 rounded-2xl text-xs border border-emerald-100">Pro Active ({{ $activeSubscription ? $activeSubscription->daysRemaining() : 0 }} days left)</span>
                                <form method="POST" action="{{ route('pricing.cancel') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-center font-bold text-xs text-red-500 hover:underline bg-transparent border-none cursor-pointer">Cancel Subscription</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('pricing.upgrade') }}">
                                @csrf
                                <input type="hidden" name="plan" value="student_pro">
                                <input type="hidden" name="billing_cycle" value="monthly">
                                <button type="submit" class="ig-btn w-full justify-center text-xs py-3 font-bold bg-[var(--ig-accent)] text-white hover:bg-violet-700 border-none shadow-md">
                                    Upgrade to Pro (Demo Mode)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Startup Pricing Cards -->
            <div x-show="planType === 'startup'" class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto" style="display:none;">
                <!-- Free Plan -->
                <div class="ig-card p-8 flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                    <div>
                        <p class="ig-eyebrow mb-2">— Startup Standard</p>
                        <h3 class="font-extrabold text-2xl text-[var(--ig-ink)]">Startup Free</h3>
                        <div class="my-6">
                            <span class="text-4xl font-black text-[var(--ig-ink)] font-poppins">₹0</span>
                            <span class="text-xs text-[var(--ig-muted)] font-medium">/ month</span>
                        </div>
                        <ul class="space-y-3.5 text-xs text-[var(--ig-ink-2)]" style="line-height: 1.5;">
                            <li class="flex items-center gap-2">✓ First internship hire free (success fee waived)</li>
                            <li class="flex items-center gap-2">✓ Maximum 3 active task postings</li>
                            <li class="flex items-center gap-2">✓ Standard candidate search</li>
                            <li class="flex items-center gap-2">✓ Basic support</li>
                        </ul>
                    </div>
                    <div class="mt-8 pt-6 border-t border-[var(--ig-line)]">
                        @if($user && $user->isStartup() && !$user->isStartupGrowth())
                            <span class="w-full text-center block bg-emerald-50 text-emerald-800 font-bold py-3 rounded-2xl text-xs border border-emerald-100">Your Current Plan</span>
                        @else
                            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost w-full justify-center text-xs py-3 font-bold">Start Free</a>
                        @endif
                    </div>
                </div>

                <!-- Growth Plan -->
                <div class="ig-card p-8 flex flex-col justify-between relative overflow-hidden" style="border: 2px solid var(--ig-accent);">
                    <div class="absolute -top-3 -right-3 bg-[var(--ig-accent)] text-white text-[8px] uppercase tracking-widest font-black py-2.5 px-6 rounded-bl-2xl">Recommended</div>
                    <div>
                        <p class="ig-eyebrow mb-2" style="color:var(--ig-accent);">— Startup Scaling</p>
                        <h3 class="font-extrabold text-2xl text-[var(--ig-ink)]">Startup Growth</h3>
                        <div class="my-6">
                            <span class="text-4xl font-black text-[var(--ig-ink)] font-poppins">₹999</span>
                            <span class="text-xs text-[var(--ig-muted)] font-medium">/ month</span>
                        </div>
                        <ul class="space-y-3.5 text-xs text-[var(--ig-ink-2)]" style="line-height: 1.5;">
                            <li class="flex items-center gap-2 text-indigo-700 font-bold">✓ Unlimited task postings</li>
                            <li class="flex items-center gap-2 text-indigo-700 font-bold">✓ Unlimited internship offers</li>
                            <li class="flex items-center gap-2">✓ Advanced candidate filtering filters</li>
                            <li class="flex items-center gap-2">✓ AI-powered matching candidate ranking</li>
                            <li class="flex items-center gap-2">✓ Team workspace management</li>
                            <li class="flex items-center gap-2">✓ Priority live chat support</li>
                        </ul>
                    </div>
                    <div class="mt-8 pt-6 border-t border-[var(--ig-line)]">
                        @if($user && $user->isStartupGrowth())
                            <div class="space-y-3">
                                <span class="w-full text-center block bg-emerald-50 text-emerald-800 font-bold py-3 rounded-2xl text-xs border border-emerald-100">Growth Active ({{ $activeSubscription ? $activeSubscription->daysRemaining() : 0 }} days left)</span>
                                <form method="POST" action="{{ route('pricing.cancel') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-center font-bold text-xs text-red-500 hover:underline bg-transparent border-none cursor-pointer">Cancel Subscription</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('pricing.upgrade') }}">
                                @csrf
                                <input type="hidden" name="plan" value="startup_growth">
                                <input type="hidden" name="billing_cycle" value="monthly">
                                <button type="submit" class="ig-btn w-full justify-center text-xs py-3 font-bold bg-[var(--ig-accent)] text-white hover:bg-violet-700 border-none shadow-md">
                                    Upgrade to Growth (Demo Mode)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="ig-card p-8 flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                    <div>
                        <p class="ig-eyebrow mb-2">— Large Scale Recruiting</p>
                        <h3 class="font-extrabold text-2xl text-[var(--ig-ink)]">Enterprise</h3>
                        <div class="my-6">
                            <span class="text-2xl font-black text-[var(--ig-ink)] font-poppins">Custom</span>
                            <span class="text-xs text-[var(--ig-muted)] font-medium"> / contact us</span>
                        </div>
                        <ul class="space-y-3.5 text-xs text-[var(--ig-ink-2)]" style="line-height: 1.5;">
                            <li class="flex items-center gap-2">✓ Dedicated account manager</li>
                            <li class="flex items-center gap-2">✓ Bulk campus hiring campaigns</li>
                            <li class="flex items-center gap-2">✓ Direct university integrations</li>
                            <li class="flex items-center gap-2">✓ White-labeled placement portal</li>
                            <li class="flex items-center gap-2">✓ Custom webhooks and API feeds</li>
                        </ul>
                    </div>
                    <div class="mt-8 pt-6 border-t border-[var(--ig-line)]">
                        <a href="mailto:sales@interngrowth.com?subject=Enterprise Inquiry" class="ig-btn ig-btn-ghost w-full justify-center text-xs py-3 font-bold text-center">Contact Sales</a>
                    </div>
                </div>
            </div>

            <!-- Revenue Explanation Section -->
            <div class="ig-card p-8 bg-slate-50 border border-slate-200 rounded-3xl max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div>
                        <span class="ig-chip ig-chip-accent mb-2">Platform Fees</span>
                        <h3 class="font-bold text-xl text-[var(--ig-ink)]">Transparency in Placements</h3>
                        <p class="text-xs text-[var(--ig-muted)] mt-2 leading-relaxed">
                            InternGrowth aligns its incentives with yours. We make money when you successfully hire or convert talent.
                        </p>
                    </div>
                    <div class="space-y-3 text-xs" style="font-size:12px;">
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500 font-medium">First Internship Hire</span>
                            <span class="font-bold text-[#059669]">FREE (₹0)</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500 font-medium">Subsequent Internship Placements</span>
                            <span class="font-bold text-slate-800">₹1,999 success fee</span>
                        </div>
                        <div class="flex justify-between pb-2">
                            <span class="text-slate-500 font-medium">Full-Time PPO Conversion Fee</span>
                            <span class="font-bold text-slate-800">5% of first month's CTC</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="max-w-4xl mx-auto pt-6">
                <h3 class="ig-display text-2xl text-center text-[var(--ig-ink)] mb-8">Frequently Asked Questions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1">Why is the first internship hire free?</h4>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">To let startups test candidate quality and experience our verified escrow protection systems with zero entry barrier.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1">Can students use InternGrowth forever for free?</h4>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Yes! Applying to tasks, building portfolios, earning basic experience certificates, and completing projects is completely free.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1">What happens if a startup cancels an internship?</h4>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Any locked escrow stipend is refunded back to the startup's wallet instantly, subject to review of student progress by our moderators.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1">How are success fees protected?</h4>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Success fees are locked in escrow along with the stipend when a startup issues a contract, ensuring mutual guarantee and transparent billing.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
