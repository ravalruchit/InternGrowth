<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="ig-container py-12 ig-anim-fade-up space-y-8">
        
        <!-- Welcome Header & Info -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="ig-eyebrow mb-2">— Startup Console</p>
                <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                    Welcome, <span class="ig-serif text-[var(--ig-accent)]"><?php echo e($profile->company_name); ?>!</span>
                </h1>
                <p class="text-sm text-[var(--ig-muted)] mt-1.5">Manage your microtasks, student offers and wallet credentials.</p>
            </div>

            <!-- Messages Button -->
            <div class="flex-shrink-0">
                <a href="<?php echo e(route('messages.index')); ?>" class="ig-btn ig-btn-ghost">
                    💬 Inbox Messages
                </a>
            </div>
        </div>

        
        <?php if(!$profile->is_verified): ?>
            <div class="ig-banner ig-banner-warn">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full gap-4">
                    <div>
                        <p class="font-bold text-amber-955 text-sm">⚠️ Account Pending Verification</p>
                        <p class="text-xs text-amber-900 mt-0.5">
                            <?php if($profile->verification_status === 'pending' && $profile->verification_submitted_at): ?>
                                Your verification documents are currently under administrative review.
                            <?php elseif($profile->verification_status === 'rejected'): ?>
                                Your registration details were rejected. Please check feedback and resubmit.
                            <?php else: ?>
                                Submit your legal incorporation paperwork to post active tasks.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if($profile->verification_status !== 'pending' || !$profile->verification_submitted_at): ?>
                        <a href="<?php echo e(route('startup.verification')); ?>" class="ig-btn ig-btn-accent text-xs" style="padding: 8px 18px;">
                            <?php echo e($profile->verification_status === 'rejected' ? 'Resubmit' : 'Get Verified'); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif(session()->has('startup_just_verified_' . auth()->id())): ?>
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
                            fetch('<?php echo e(route("startup.clear-verification-alert")); ?>', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Content-Type': 'application/json'
                                }
                            });
                        }, 500);
                    }, 3000);
                }
            </script>
        <?php endif; ?>

        <!-- Stats Overview Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            <!-- Balance Card -->
            <div class="ig-card-dark p-6 relative overflow-hidden shadow-xl sm:col-span-2 flex flex-col justify-between min-h-[140px]">
                <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-30" style="background:var(--ig-accent)"></div>
                <div class="relative">
                    <p class="ig-eyebrow text-[10px] text-white/50 mb-1">Available Funds</p>
                    <p class="ig-display text-3xl sm:text-4xl text-white">₹<?php echo e(number_format($profile->wallet_balance, 2)); ?></p>
                </div>
                <div class="flex gap-4 text-xs font-semibold relative z-10">
                    <a href="<?php echo e(route('wallet.index')); ?>" class="text-[var(--ig-lime)] hover:underline">Transactions →</a>
                    <a href="<?php echo e(route('wallet.topup')); ?>" class="text-white hover:underline">+ Top-up Account</a>
                </div>
            </div>

            <!-- Posted Tasks count -->
            <div class="ig-card p-5 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Posted Tasks</p>
                <p class="ig-display text-4xl text-[var(--ig-ink)] mt-3"><?php echo e($tasks->count()); ?></p>
                <p class="text-[10px] text-[var(--ig-faint)] mt-2 font-mono">active listings</p>
            </div>

            <!-- Total Applications count -->
            <div class="ig-card p-5 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Applications</p>
                <p class="ig-display text-4xl text-[var(--ig-ink)] mt-3"><?php echo e($tasks->sum(fn($t) => $t->applications->count())); ?></p>
                <p class="text-[10px] text-[var(--ig-faint)] mt-2 font-mono">submitted resumes</p>
            </div>

            <!-- Credibility Rating -->
            <div class="ig-card p-5 flex flex-col justify-between">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Credibility</p>
                <p class="ig-display text-4xl text-[var(--ig-ink)] mt-3"><?php echo e(number_format($profile->credibility_score * 100, 0)); ?>%</p>
                <p class="text-[10px] text-[var(--ig-faint)] mt-2 font-mono">reputation score</p>
            </div>
        </div>

        <!-- Trust Breakdown & Reviews Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Trust Score Breakdown -->
            <div class="ig-card p-6 lg:col-span-1 space-y-4 bg-white">
                <?php
                    $ts = $profile->trustScore;
                    $overall = $ts ? $ts->overall_score : ($profile->credibility_score * 100);
                    if ($overall <= 0) $overall = 100;
                ?>
                <div class="flex justify-between items-center border-b border-[var(--ig-line)] pb-3">
                    <h3 class="text-sm font-bold text-[var(--ig-ink)]">🛡️ Trust Index Score</h3>
                    <span class="ig-chip ig-chip-lime font-mono text-xs font-bold"><?php echo e(number_format($overall, 0)); ?> / 100</span>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Verification (25%)</span>
                            <span class="text-[var(--ig-ink)] font-bold"><?php echo e($ts ? number_format($ts->verification_score, 0) : ($profile->is_verified ? '100' : '0')); ?>%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[var(--ig-accent)] h-1.5 rounded-full" style="width: <?php echo e($ts ? $ts->verification_score : ($profile->is_verified ? '100' : '0')); ?>%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Payment Reliability (25%)</span>
                            <span class="text-[var(--ig-ink)] font-bold"><?php echo e($ts ? number_format($ts->payment_score, 0) : ($profile->wallet_balance < 0 ? '50' : '100')); ?>%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: <?php echo e($ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? '50' : '100')); ?>%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Rating Score (20%)</span>
                            <span class="text-[var(--ig-ink)] font-bold"><?php echo e($ts ? number_format($ts->student_rating_score, 0) : '100'); ?>%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-amber-400 h-1.5 rounded-full" style="width: <?php echo e($ts ? $ts->student_rating_score : '100'); ?>%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-[var(--ig-muted)] mb-1">
                            <span>Offers & Placement (15%)</span>
                            <span class="text-[var(--ig-ink)] font-bold"><?php echo e($ts ? number_format($ts->hiring_score, 0) : '100'); ?>%</span>
                        </div>
                        <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[var(--ig-forest)] h-1.5 rounded-full" style="width: <?php echo e($ts ? $ts->hiring_score : '100'); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Reviews Listing -->
            <div class="ig-card p-6 lg:col-span-2 space-y-4 bg-white">
                <h3 class="text-sm font-bold text-[var(--ig-ink)] border-b border-[var(--ig-line)] pb-3">⭐ Student Reviews (<?php echo e($profile->reviews->count()); ?>)</h3>
                <div class="space-y-4 max-h-[200px] overflow-y-auto pr-2">
                    <?php $__empty_1 = true; $__currentLoopData = $profile->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-b border-[var(--ig-line)] pb-4 last:border-none last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-sm text-[var(--ig-ink)]"><?php echo e($review->student->user->name); ?></h4>
                                    <p class="text-[10px] text-[var(--ig-muted)] mt-0.5">Project: <?php echo e($review->task->title); ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-yellow-500 text-xs font-bold">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $review->rating): ?> ★ <?php else: ?> ☆ <?php endif; ?>
                                        <?php endfor; ?>
                                    </span>
                                    <p class="text-[10px] text-[var(--ig-faint)] mt-0.5"><?php echo e($review->created_at->format('M d, Y')); ?></p>
                                </div>
                            </div>
                            <p class="text-xs text-[var(--ig-ink-2)] italic leading-normal">"<?php echo e($review->review); ?>"</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8 text-[var(--ig-muted)] text-xs">
                            <span>No student reviews received yet.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Recruitment & Conversion Funnel Analytics -->
        <?php
            $allApplications = $tasks->flatMap(fn($t) => $t->applications);
            $appliedCount = $allApplications->count();
            $assignedCount = $allApplications->filter(fn($app) => in_array($app->status, ['approved', 'internship_offered', 'internship_accepted', 'hired']) || ($app->submission && $app->submission->status === 'accepted'))->count();
            $completedCount = $allApplications->filter(fn($app) => $app->submission && $app->submission->status === 'accepted')->count();
            $interviewedCount = $allApplications->filter(fn($app) => in_array($app->startup_hiring_outcome, ['interview_scheduled', 'interview_passed', 'interview_failed', 'hired_intern', 'hired_job']))->count();
            $hiredCount = $allApplications->filter(fn($app) => in_array($app->startup_hiring_outcome, ['hired_intern', 'hired_job']))->count() + $hiringOffers->where('status', 'accepted')->count();

            $completionRate = $assignedCount > 0 ? ($completedCount / $assignedCount) * 100 : 0;
            $hiringRate = $completedCount > 0 ? ($hiredCount / $completedCount) * 100 : 0;
        ?>
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
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono"><?php echo e($appliedCount); ?></p>
                    </div>
                    <p class="text-[10px] text-[var(--ig-faint)] mt-2">Task applicants</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">2. Assigned</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono"><?php echo e($assignedCount); ?></p>
                    </div>
                    <?php if($appliedCount > 0): ?>
                        <p class="text-[10px] text-[var(--ig-accent)] font-bold mt-2"><?php echo e(number_format(($assignedCount / $appliedCount) * 100, 0)); ?>% conversion</p>
                    <?php else: ?>
                        <p class="text-[10px] text-[var(--ig-faint)] mt-2">working candidates</p>
                    <?php endif; ?>
                </div>
                <!-- Step 3 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">3. Completed</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono"><?php echo e($completedCount); ?></p>
                    </div>
                    <p class="text-[10px] text-emerald-700 font-bold mt-2"><?php echo e(number_format($completionRate, 0)); ?>% completion</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-[var(--ig-bg)]/30 border border-[var(--ig-line-2)] rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--ig-muted)] block mb-1">4. Interviewed</span>
                        <p class="ig-display text-3xl text-[var(--ig-ink)] font-mono"><?php echo e($interviewedCount); ?></p>
                    </div>
                    <?php if($completedCount > 0): ?>
                        <p class="text-[10px] text-[var(--ig-accent)] font-bold mt-2"><?php echo e(number_format(($interviewedCount / $completedCount) * 100, 0)); ?>% conversion</p>
                    <?php else: ?>
                        <p class="text-[10px] text-[var(--ig-faint)] mt-2">pipeline selection</p>
                    <?php endif; ?>
                </div>
                <!-- Step 5 -->
                <div class="bg-emerald-50/40 border border-emerald-150 rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-emerald-800 block mb-1">5. Hired / Placed</span>
                        <p class="ig-display text-3xl text-emerald-750 font-mono"><?php echo e($hiredCount); ?></p>
                    </div>
                    <p class="text-[10px] text-emerald-700 font-bold mt-2"><?php echo e(number_format($hiringRate, 0)); ?>% hiring success</p>
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
                        <?php $__currentLoopData = ['Software Development' => '💻', 'UI/UX Design' => '🎨', 'Digital Marketing' => '📈', 'Data & AI' => '🤖', 'Content & Business' => '✍️']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domName => $domIcon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $apps = $applicationsByDomain[$domName] ?? 0;
                                $hires = $hiringSuccessByDomain[$domName] ?? 0;
                                $maxApps = max(1, max(array_values($applicationsByDomain)));
                                $progressWidth = min(100, ($apps / $maxApps) * 100);
                            ?>
                            <div class="bg-[var(--ig-bg)]/40 hover:bg-[var(--ig-bg)]/70 transition border border-[var(--ig-line)] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg"><?php echo e($domIcon); ?></span>
                                        <span class="font-bold text-sm text-[var(--ig-ink)]"><?php echo e($domName); ?></span>
                                    </div>
                                    <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-2 overflow-hidden">
                                        <div class="bg-[var(--ig-accent)] h-2 rounded-full transition-all duration-500" style="width: <?php echo e($progressWidth); ?>%"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6 text-right sm:text-left">
                                    <div class="min-w-[80px]">
                                        <p class="text-[10px] uppercase font-bold text-[var(--ig-muted)]">Applications</p>
                                        <p class="text-base font-bold text-[var(--ig-ink)] font-mono"><?php echo e($apps); ?></p>
                                    </div>
                                    <div class="min-w-[80px] border-l border-[var(--ig-line-2)] pl-4">
                                        <p class="text-[10px] uppercase font-bold text-[var(--ig-muted)]">Hired / Placed</p>
                                        <p class="text-base font-bold text-emerald-700 font-mono flex items-center gap-1">
                                            <?php echo e($hires); ?>

                                            <?php if($hires > 0): ?>
                                                <span class="text-xs text-emerald-600 font-normal">🎉</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Domain Rankings & Performers -->
                <div class="lg:col-span-1 bg-[var(--ig-bg)]/30 border border-[var(--ig-line)] rounded-2xl p-5 space-y-4">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider border-b border-[var(--ig-line)] pb-3 flex items-center gap-2">
                        🏆 Top Performing Domains
                    </h3>
                    
                    <div class="space-y-3">
                        <?php $__currentLoopData = $topPerformingDomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $domName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
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
                            ?>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-[var(--ig-line-2)] hover:border-[var(--ig-ink)] transition duration-200">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs font-mono
                                        <?php echo e($rank === 1 ? 'bg-amber-100 text-amber-800 border border-amber-300' : ($rank === 2 ? 'bg-slate-100 text-slate-800 border border-slate-300' : 'bg-orange-50 text-orange-800')); ?>">
                                        #<?php echo e($rank); ?>

                                    </span>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs"><?php echo e($icon); ?></span>
                                            <span class="font-bold text-xs text-[var(--ig-ink)]"><?php echo e($domName); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="ig-chip <?php echo e($hires > 0 ? 'ig-chip-success' : 'ig-chip'); ?> text-[9px] font-bold font-mono">
                                        <?php echo e($hires); ?> Hires
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiring Pipeline / Sent Offers -->
        <div class="ig-card p-6 overflow-hidden">
            <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-6">Direct Acquisition Offers</h2>
            
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
                        <?php $__empty_1 = true; $__currentLoopData = $hiringOffers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-[var(--ig-bg)] transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[var(--ig-surface-ink)] text-white font-bold flex items-center justify-center text-xs uppercase">
                                            <?php echo e(substr($offer->student->user->name, 0, 2)); ?>

                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('students.public-profile', $offer->student_profile_id)); ?>" target="_blank" class="font-bold text-[var(--ig-ink)] hover:text-[var(--ig-accent)] transition">
                                                <?php echo e($offer->student->user->name); ?>

                                            </a>
                                            <p class="text-[10px] text-[var(--ig-muted)]"><?php echo e($offer->student->user->email); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span class="ig-chip text-[10px] font-bold uppercase tracking-wider <?php echo e($offer->offer_type === 'internship' ? 'ig-chip-accent' : 'ig-chip-ink'); ?>">
                                        <?php echo e($offer->offer_type); ?>

                                    </span>
                                </td>
                                <td class="py-4 font-semibold text-[var(--ig-ink)]">
                                    ₹<?php echo e(number_format($offer->compensation, 0)); ?>/<?php echo e($offer->compensation_period === 'annual' ? 'yr' : 'mo'); ?>

                                </td>
                                <td class="py-4 text-[var(--ig-muted)]">
                                    <?php echo e($offer->start_date->format('M d, Y')); ?>

                                </td>
                                <td class="py-4">
                                    <span class="ig-chip text-[10px] font-bold <?php echo e($offer->status === 'accepted' ? 'ig-chip-success' : ($offer->status === 'pending' ? 'ig-chip-warn' : 'ig-chip-danger')); ?>">
                                        <?php echo e($offer->status); ?>

                                    </span>
                                </td>
                                <td class="py-4">
                                    <?php if($offer->status === 'pending'): ?>
                                        <form method="POST" action="<?php echo e(route('startup.offers.withdraw', $offer->id)); ?>" onsubmit="return confirm('Are you sure you want to withdraw this offer?');" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-[var(--ig-rose)] font-bold hover:underline bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1 rounded-lg transition text-[10px]">
                                                Withdraw
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-400 font-medium">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[var(--ig-muted)] text-sm">
                                    No direct hiring offers extended yet. Use the Talent Discovery Hub to pitch to top talent.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My Tasks Section -->
        <div class="ig-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="ig-display text-2xl text-[var(--ig-ink)]">My Posted Tasks</h2>
                <?php if($profile->is_verified): ?>
                    <a href="<?php echo e(route('tasks.create')); ?>" class="ig-btn ig-btn-primary text-xs" style="padding: 10px 22px;">
                        + Post New Task
                    </a>
                <?php else: ?>
                    <button disabled class="ig-btn text-xs opacity-50 cursor-not-allowed" style="padding: 10px 22px;" title="Account verification required to post tasks">
                        + Post New Task
                    </button>
                <?php endif; ?>
            </div>

            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $approvedApp = $task->applications()->where('status', 'approved')->first();
                        $completedApp = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->first();
                    ?>
                    <div class="border border-[var(--ig-line)] rounded-2xl p-5 hover:border-[var(--ig-ink)] transition duration-200 group">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2.5 mb-2">
                                    <h3 class="font-bold text-lg text-[var(--ig-ink)] group-hover:text-[var(--ig-accent)] transition-colors"><?php echo e($task->title); ?></h3>
                                    <?php if($task->status === 'completed' || $completedApp): ?>
                                        <span class="ig-chip ig-chip-success text-[9px] font-bold">✓ Completed</span>
                                    <?php elseif($approvedApp): ?>
                                        <span class="ig-chip ig-chip-warn text-[9px] font-bold">⏳ In Progress</span>
                                    <?php else: ?>
                                        <span class="ig-chip ig-chip-lime text-[9px] font-bold">📢 Open</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($completedApp): ?>
                                    <p class="text-xs text-emerald-800 mb-2 font-medium">
                                        ✓ Completed by: <strong><?php echo e($completedApp->student->user->name); ?></strong>
                                    </p>
                                <?php elseif($approvedApp): ?>
                                    <p class="text-xs text-amber-800 mb-2 font-medium">
                                        ⏳ Assigned to: <strong><?php echo e($approvedApp->student->user->name); ?></strong>
                                    </p>
                                <?php endif; ?>
                                
                                <p class="text-xs text-[var(--ig-muted)] font-mono">
                                    <?php echo e($task->applications->count()); ?> applications · <?php if($task->stipend): ?> ₹<?php echo e(number_format($task->stipend, 0)); ?> stipend <?php else: ?> Experience Task <?php endif; ?>
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="ig-btn ig-btn-ghost text-xs" style="padding: 8px 16px;">
                                    View Applications
                                </a>
                                <?php if($task->applications()->where('status', 'approved')->count() === 0): ?>
                                    <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="ig-btn ig-btn-ghost text-xs hover:border-[var(--ig-ink)]" style="padding: 8px 16px;">
                                        Edit
                                    </a>
                                <?php else: ?>
                                    <span class="ig-chip text-[10px] font-medium" title="Cannot edit after approving an application">Locked</span>
                                <?php endif; ?>
                                <?php if($task->applications->count() === 0): ?>
                                    <form method="POST" action="<?php echo e(route('tasks.destroy', $task->id)); ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="ig-btn text-xs text-white bg-[var(--ig-rose)] border-none hover:bg-red-700" style="padding: 8px 16px;">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12 text-[var(--ig-muted)]">
                        <svg class="mx-auto h-10 w-10 text-[var(--ig-faint)] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="font-bold text-sm text-[var(--ig-ink)]">No tasks posted yet.</p>
                        <?php if($profile->is_verified): ?>
                            <a href="<?php echo e(route('tasks.create')); ?>" class="text-[var(--ig-accent)] font-semibold text-xs hover:underline mt-1 inline-block">Post your first task →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Completed Tasks History -->
        <?php if($completedTasks->count() > 0): ?>
            <div class="ig-card p-6 space-y-6">
                <h2 class="ig-display text-2xl text-[var(--ig-ink)] flex items-center space-x-2 border-b border-[var(--ig-line)] pb-4">
                    <span>✓ Completed Tasks History</span>
                </h2>
                <div class="space-y-4">
                    <?php $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $completedApp = $task->applications->filter(function($app) {
                                return $app->submission && $app->submission->status === 'accepted';
                            })->first();
                        ?>
                        <?php if($completedApp): ?>
                            <div class="border border-emerald-150 bg-emerald-50/20 rounded-2xl p-5 hover:shadow-sm transition">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3.5 mb-2">
                                            <h3 class="font-bold text-lg text-[var(--ig-ink)]"><?php echo e($task->title); ?></h3>
                                            <span class="ig-chip ig-chip-success text-[9px] font-bold">Completed</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 text-xs text-[var(--ig-ink-2)] bg-white/60 border border-[var(--ig-line-2)] rounded-xl p-4">
                                            <div>
                                                <p>Completed by: <strong><?php echo e($completedApp->student->user->name); ?></strong></p>
                                                <p class="mt-1">Student Reliability: <span class="text-emerald-700 font-bold"><?php echo e(number_format($completedApp->student->reliability_score * 100, 0)); ?>%</span></p>
                                                <?php if($task->stipend): ?>
                                                    <p class="mt-1">Stipend Released: <strong>₹<?php echo e(number_format($task->stipend, 0)); ?></strong></p>
                                                <?php else: ?>
                                                    <p class="mt-1">Escrow Released: <strong>Experience Task</strong></p>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p>Completed on: <strong><?php echo e($completedApp->submission->updated_at->format('M d, Y')); ?></strong></p>
                                                <?php if($task->ratings && $task->ratings->where('student_profile_id', $completedApp->student_profile_id)->first()): ?>
                                                    <?php
                                                        $rating = $task->ratings->where('student_profile_id', $completedApp->student_profile_id)->first();
                                                    ?>
                                                    <p class="mt-1">Your Rating:
                                                        <span class="text-yellow-500 font-bold">
                                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                                <?php if($i <= $rating->rating): ?> ★ <?php else: ?> ☆ <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </span>
                                                    </p>
                                                    <?php if($rating->review): ?>
                                                        <p class="mt-1.5 text-[11px] text-[var(--ig-muted)] italic">"<?php echo e(Str::limit($rating->review, 80)); ?>"</p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if($completedApp->submission->submission_url): ?>
                                            <p class="text-xs text-[var(--ig-muted)] mb-3">
                                                Submission Proof: 
                                                <a href="<?php echo e($completedApp->submission->submission_url); ?>" target="_blank" class="text-[var(--ig-azure)] font-bold hover:underline">
                                                    View Completed Work →
                                                </a>
                                            </p>
                                        <?php endif; ?>

                                        <div class="flex items-center gap-3 mt-4 text-xs font-semibold">
                                            <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="text-[var(--ig-accent)] hover:underline">
                                                Full Details →
                                            </a>
                                            <a href="<?php echo e(route('messages.index')); ?>" class="text-[var(--ig-muted)] hover:underline">
                                                Message Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/startup/dashboard.blade.php ENDPATH**/ ?>