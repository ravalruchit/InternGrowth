<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['compact' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['compact' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(auth()->check() && auth()->user()->isStudent()): ?>
    <?php
        $student = auth()->user()->studentProfile;
        $score = $student->reputationScore;
        $iprs = $score ? round($score->overall_score) : 50;
        $verifiedTasks = $score ? $score->total_verified_projects : 0;
        $reviewsCount = $student->ratings()->count();
        $internshipOffers = $student->hiringOffers()->where('offer_type', 'internship')->count();
    ?>

    <?php if($compact): ?>
        <!-- Compact Banner Mode -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-950 via-slate-900 to-purple-950 rounded-2xl border border-indigo-500/20 px-5 py-4 shadow-lg backdrop-blur-md text-white transition hover:scale-[1.005] hover:shadow-indigo-500/5 flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-md shadow-indigo-500/10 flex-shrink-0">
                <span class="text-lg">💡</span>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-black uppercase tracking-widest text-indigo-300 mb-0.5">Reputation Tip</h4>
                <p class="text-xs text-slate-300 leading-relaxed font-normal">
                    Every verified task completed through InternGrowth strengthens your IPRS (<span class="font-bold text-white"><?php echo e($iprs); ?></span>), Experience Ledger (<span class="font-bold text-white"><?php echo e($verifiedTasks); ?></span> tasks), and future hiring opportunities.
                </p>
            </div>
        </div>
    <?php else: ?>
        <!-- Full Premium Reputation Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 rounded-3xl border border-indigo-500/25 p-6 md:p-8 shadow-2xl backdrop-blur-md text-white transition-all duration-300 hover:scale-[1.005] hover:shadow-indigo-500/10 group">
            <!-- Background Decorative Gradients -->
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all duration-500"></div>
            <div class="absolute -left-16 -bottom-16 w-36 h-36 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500"></div>

            <div class="flex flex-col md:flex-row gap-6 items-start relative z-10">
                <!-- Badge Icon -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>

                <div class="flex-1 space-y-5">
                    <!-- Header -->
                    <div>
                        <h3 class="text-xl md:text-2xl font-black tracking-tight text-white mb-2.5">
                            🚀 <span class="bg-gradient-to-r from-indigo-200 via-purple-200 to-pink-200 bg-clip-text text-transparent font-black">Your Reputation Is Your Resume</span>
                        </h3>
                        <p class="text-sm text-slate-350 leading-relaxed font-normal">
                            Every verified task completed through InternGrowth contributes to your professional growth. Build your IPRS Reputation Score, Experience Ledger, Startup Reviews, Internship History, and Job Opportunities through real work. Startups trust verified experience more than traditional resumes.
                        </p>
                    </div>

                    <!-- Personalized Statistics Section -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-200"><?php echo e($iprs); ?></span>
                            <span class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Current IPRS</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-200"><?php echo e($verifiedTasks); ?></span>
                            <span class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Verified Tasks</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-200"><?php echo e($reviewsCount); ?></span>
                            <span class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Startup Reviews</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-200"><?php echo e($internshipOffers); ?></span>
                            <span class="text-[9px] font-bold text-indigo-200 uppercase tracking-wider">Internship Offers</span>
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
                    <div class="bg-indigo-950/40 border border-indigo-900/50 rounded-2xl p-4 text-xs text-indigo-200 leading-relaxed font-medium">
                        <span class="font-black text-white uppercase tracking-wider block mb-1">📢 Important Notice</span>
                        Work completed outside InternGrowth cannot be verified and will not contribute to your IPRS, Experience Ledger, Startup Reviews, or Hiring History. Keep your work and hiring journey on the platform to maximize your professional profile and future opportunities.
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/components/reputation-card.blade.php ENDPATH**/ ?>