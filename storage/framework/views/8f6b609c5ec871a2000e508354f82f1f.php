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
    <div class="ig-container">

        <!-- ─── Header ─── -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Studio · <?php echo e(now()->format('l, M d')); ?></p>
                <h1 class="ig-display text-4xl md:text-6xl">
                    Welcome back, <span class="ig-serif text-[var(--ig-accent)]"><?php echo e(Str::words(auth()->user()->name, 1, '')); ?>.</span>
                </h1>
                <p class="text-[var(--ig-ink-2)] mt-3 max-w-xl">Here's your reputation, your work, and what's next on your shipping queue.</p>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="<?php echo e(route('tasks.index')); ?>" class="ig-btn ig-btn-primary">
                    <span>Browse open tasks</span><span class="arrow">→</span>
                </a>
            </div>
        </div>

        <!-- ─── Verification banner ─── -->
        <?php if(!$profile->is_verified): ?>
            <div class="ig-banner ig-banner-warn mb-8 ig-anim-fade-up ig-delay-1">
                <svg class="w-6 h-6 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1">
                    <h3 class="ig-display text-xl mb-1">Verify your college email</h3>
                    <p class="text-sm text-[var(--ig-ink-2)]">You currently see only 5 tasks. Verify your college email to unlock the full marketplace.</p>
                </div>
                <a href="<?php echo e(route('student.verification')); ?>" class="ig-btn ig-btn-primary"><span>Verify now</span><span class="arrow">→</span></a>
            </div>
        <?php else: ?>
            <div class="ig-banner ig-banner-success mb-8 ig-anim-fade-up ig-delay-1">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold">Verified student · <?php echo e($profile->college_name); ?></p>
                    <p class="text-[12px] text-[var(--ig-muted)] mt-0.5">Full access · all tasks unlocked</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- ─── Pending Offers ─── -->
        <?php if($hiringOffers && $hiringOffers->count() > 0): ?>
            <section class="mb-12 ig-reveal">
                <div class="ig-section-head">
                    <div>
                        <p class="ig-eyebrow mb-2">— You've got mail</p>
                        <h2 class="ig-display text-3xl">Pending offers <span class="text-[var(--ig-muted)]">· <?php echo e($hiringOffers->count()); ?></span></h2>
                    </div>
                </div>
                <div class="space-y-4">
                    <?php $__currentLoopData = $hiringOffers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ig-card-dark p-7 relative overflow-hidden">
                            <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full blur-3xl opacity-30" style="background:var(--ig-accent)"></div>
                            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                <div class="md:col-span-2">
                                    <span class="ig-chip ig-chip-lime"><?php echo e(strtoupper($offer->offer_type)); ?> OFFER</span>
                                    <h3 class="ig-display text-3xl text-white mt-3"><?php echo e($offer->title); ?></h3>
                                    <p class="ig-mono text-[12px] mt-1" style="color:#9C9580">from <span class="text-white font-semibold"><?php echo e($offer->startup->company_name); ?></span></p>
                                    <p class="text-sm mt-4 leading-relaxed" style="color:#C9C1AE"><?php echo e(Str::limit($offer->description, 180)); ?></p>
                                </div>
                                <div class="md:text-right">
                                    <p class="ig-eyebrow mb-1" style="color:#9C9580">Compensation</p>
                                    <p class="ig-stat-num text-4xl text-[var(--ig-lime)]">₹<?php echo e(number_format($offer->compensation, 0)); ?></p>
                                    <p class="ig-mono text-[11px] mt-1" style="color:#9C9580">per <?php echo e($offer->compensation_period === 'annual' ? 'year' : 'month'); ?></p>
                                    <div class="flex md:justify-end gap-2 mt-5">
                                        <form method="POST" action="<?php echo e(route('student.offers.reject', $offer->id)); ?>"><?php echo csrf_field(); ?>
                                            <button class="ig-btn" style="background:transparent;color:#C9C1AE;border:1px solid rgba(255,255,255,.15)">Decline</button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('student.offers.accept', $offer->id)); ?>"><?php echo csrf_field(); ?>
                                            <button class="ig-btn ig-btn-lime"><span>Accept</span><span class="arrow">→</span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- ─── Scheduled Interviews ─── -->
        <?php if($interviews && $interviews->count() > 0): ?>
            <section class="mb-12 ig-reveal">
                <div class="ig-section-head">
                    <div>
                        <p class="ig-eyebrow mb-2">— Upcoming Rounds</p>
                        <h2 class="ig-display text-3xl text-[var(--ig-ink)]">Interview Schedule <span class="text-[var(--ig-muted)]">· <?php echo e($interviews->count()); ?></span></h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $interviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interview): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ig-card p-6 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="ig-chip <?php echo e($interview->status === 'accepted' ? 'ig-chip-success' : 'ig-chip-warn'); ?>">
                                        <?php echo e(strtoupper($interview->status)); ?>

                                    </span>
                                    <span class="text-xs text-[var(--ig-muted)] font-semibold"><?php echo e($interview->duration_minutes); ?> min</span>
                                </div>
                                <h3 class="ig-display text-xl mt-3 text-[var(--ig-ink)]"><?php echo e($interview->title); ?></h3>
                                <p class="ig-mono text-xs mt-1 text-[var(--ig-muted)]">
                                    with <span class="text-[var(--ig-ink)] font-bold"><?php echo e($interview->startup->company_name); ?></span>
                                    <?php if($interview->task): ?>
                                        for <span class="italic"><?php echo e($interview->task->title); ?></span>
                                    <?php endif; ?>
                                </p>
                                
                                <div class="mt-4 space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span>📅</span>
                                        <span class="font-bold text-[var(--ig-ink)]"><?php echo e($interview->scheduled_at->format('M d, Y \a\t g:i A')); ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>📍</span>
                                        <span class="text-[var(--ig-ink-2)]">
                                            <?php if($interview->status === 'accepted'): ?>
                                                <?php if(filter_var($interview->location, FILTER_VALIDATE_URL)): ?>
                                                    <a href="<?php echo e($interview->location); ?>" target="_blank" class="text-[var(--ig-accent)] hover:underline font-semibold"><?php echo e($interview->location); ?></a>
                                                <?php else: ?>
                                                    <?php echo e($interview->location); ?>

                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-[var(--ig-muted)] italic">Hidden until accepted</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <?php if($interview->agenda): ?>
                                        <div class="bg-[var(--ig-bg-2)]/30 border border-[var(--ig-line-2)] rounded-xl p-3 text-[11.5px] leading-relaxed text-[var(--ig-ink-2)] mt-2">
                                            <strong>Agenda:</strong> <?php echo e($interview->agenda); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="pt-3 border-t border-[var(--ig-line)] flex items-center justify-between gap-3">
                                <?php if($interview->status === 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('student.interviews.reject', $interview->id)); ?>" class="flex-1">
                                        <?php echo csrf_field(); ?>
                                        <button class="ig-btn w-full justify-center" style="background:transparent;border:1px solid var(--ig-line-2);color:var(--ig-ink-2);padding:8px 12px;font-size:12px;">Decline</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('student.interviews.accept', $interview->id)); ?>" class="flex-1">
                                        <?php echo csrf_field(); ?>
                                        <button class="ig-btn ig-btn-lime w-full justify-center" style="padding:8px 12px;font-size:12px;">Accept</button>
                                    </form>
                                <?php else: ?>
                                    <a href="<?php echo e(route('messages.show', $interview->conversation_id)); ?>" class="ig-btn ig-btn-ghost w-full justify-center text-xs">
                                        💬 View in Chat
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- ─── Main Grid: IPRS + Ledger ─── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">

            <!-- IPRS Scorecard -->
            <div class="lg:col-span-8 ig-card-dark p-8 relative overflow-hidden ig-reveal">
                <div class="absolute -top-32 -right-32 w-[400px] h-[400px] rounded-full blur-3xl opacity-20" style="background:var(--ig-accent)"></div>

                <div class="relative">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <p class="ig-eyebrow" style="color:#9C9580">— IPRS Scorecard</p>
                            <h2 class="ig-display text-2xl text-white mt-2">Your Professional Reputation</h2>
                        </div>
                        <span class="ig-chip ig-chip-lime">VERIFIED</span>
                    </div>

                    <?php $score = optional($profile->reputationScore)->overall_score ?? 50; ?>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                        <div class="md:col-span-4">
                            <p class="ig-stat-num text-7xl md:text-8xl text-white" data-counter="<?php echo e((int)$score); ?>">0</p>
                            <p class="ig-mono text-[11px] mt-2" style="color:#9C9580">out of 100</p>
                            <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[12px]" style="color:#C9C1AE">
                                <?php if($score >= 90): ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--ig-lime)]"></span> Elite category
                                <?php elseif($score >= 75): ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--ig-accent)]"></span> Professional
                                <?php else: ?>
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> Emerging talent
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="md:col-span-8 space-y-3">
                            <?php $metrics = [
                                ['Trust score', number_format(optional($profile->reputationScore)->trust_score ?? 50, 0).'%', optional($profile->reputationScore)->trust_score ?? 50],
                                ['Completion rate', number_format(optional($profile->reputationScore)->completion_rate ?? 100, 0).'%', optional($profile->reputationScore)->completion_rate ?? 100],
                                ['On-time delivery', number_format(optional($profile->reputationScore)->on_time_rate ?? 100, 0).'%', optional($profile->reputationScore)->on_time_rate ?? 100],
                                ['Satisfaction', number_format(optional($profile->reputationScore)->satisfaction_rating ?? 5, 1).'/5', (optional($profile->reputationScore)->satisfaction_rating ?? 5) * 20],
                                ['Communication', number_format(optional($profile->reputationScore)->communication_rating ?? 4.8, 1).'/5', (optional($profile->reputationScore)->communication_rating ?? 4.8) * 20],
                                ['Skill verification', number_format(optional($profile->reputationScore)->skill_verification_rating ?? 0, 0).'%', optional($profile->reputationScore)->skill_verification_rating ?? 0],
                            ]; ?>
                            <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <div class="flex items-center justify-between text-[12.5px] mb-1.5">
                                        <span style="color:#C9C1AE"><?php echo e($m[0]); ?></span>
                                        <span class="text-white font-semibold ig-mono"><?php echo e($m[1]); ?></span>
                                    </div>
                                    <div class="h-[3px] rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full bg-[var(--ig-lime)] rounded-full" style="width:<?php echo e(min(100, $m[2])); ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats column -->
            <div class="lg:col-span-4 grid grid-cols-2 gap-4">
                <?php
                    $stats = [
                        ['Projects', $projectsCompleted, 'ig-card', ''],
                        ['Intern offers', $internshipOffersCount, 'ig-card', ''],
                        ['Job offers', $jobOffersCount, 'ig-card', ''],
                        ['Earnings', '₹'.number_format($totalEarnings, 0), 'ig-card', 'lime-accent'],
                    ];
                ?>
                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="<?php echo e($s[2]); ?> p-5 ig-reveal <?php echo e($loop->iteration === 4 ? 'col-span-2' : ''); ?>">
                        <p class="ig-eyebrow text-[10px]"><?php echo e($s[0]); ?></p>
                        <p class="ig-stat-num text-3xl mt-3"><?php echo e($s[1]); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('wallet.index')); ?>" class="col-span-2 ig-card-dark p-5 flex items-center justify-between ig-reveal">
                    <div>
                        <p class="ig-eyebrow text-[10px]" style="color:#9C9580">Wallet balance</p>
                        <p class="ig-stat-num text-3xl text-white mt-2">₹<?php echo e(number_format($profile->wallet_balance, 0)); ?></p>
                    </div>
                    <span class="text-[var(--ig-lime)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </a>
            </div>
        </div>

        <!-- ─── Public portfolio link ─── -->
        <?php if($profile->portfolio && $profile->portfolio->custom_slug): ?>
            <div class="ig-card p-6 mb-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 ig-reveal">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[var(--ig-ink)] text-[var(--ig-bg)] flex items-center justify-center">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                    <div>
                        <p class="ig-eyebrow text-[10px]">Your public talent profile</p>
                        <p class="ig-mono text-sm mt-1"><?php echo e(url('/talent/' . $profile->portfolio->custom_slug)); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?php echo e(route('talent.profile', $profile->portfolio->custom_slug)); ?>" target="_blank" class="ig-btn ig-btn-primary"><span>View portfolio</span><span class="arrow">→</span></a>
                    <?php if (isset($component)) { $__componentOriginal158033e17ac548ba8d985f1073c1aeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal158033e17ac548ba8d985f1073c1aeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.share-profile-button','data' => ['url' => route('talent.profile', $profile->portfolio->custom_slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('share-profile-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('talent.profile', $profile->portfolio->custom_slug))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal158033e17ac548ba8d985f1073c1aeea)): ?>
<?php $attributes = $__attributesOriginal158033e17ac548ba8d985f1073c1aeea; ?>
<?php unset($__attributesOriginal158033e17ac548ba8d985f1073c1aeea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal158033e17ac548ba8d985f1073c1aeea)): ?>
<?php $component = $__componentOriginal158033e17ac548ba8d985f1073c1aeea; ?>
<?php unset($__componentOriginal158033e17ac548ba8d985f1073c1aeea); ?>
<?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ─── Recommended Tasks ─── -->
        <?php if($recommendedTasks && $recommendedTasks->count() > 0): ?>
            <section class="mb-12 ig-reveal">
                <div class="ig-section-head">
                    <div>
                        <p class="ig-eyebrow mb-2">— Matched for you</p>
                        <h2 class="ig-display text-3xl">Tasks that fit your skills</h2>
                    </div>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="hidden md:inline-flex text-sm font-semibold hover:text-[var(--ig-accent)]">View all →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $recommendedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="ig-card p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="ig-display text-xl flex-1 leading-tight"><?php echo e($task->title); ?></h3>
                                <div class="text-right ml-3">
                                    <p class="ig-stat-num text-2xl text-[var(--ig-accent)]"><?php echo e($task->match_score); ?>%</p>
                                    <p class="ig-mono text-[10px] text-[var(--ig-muted)]">match</p>
                                </div>
                            </div>
                            <p class="text-sm text-[var(--ig-ink-2)] line-clamp-2 mb-4"><?php echo e($task->description); ?></p>
                            <div class="flex items-center justify-between pt-4 border-t border-dashed border-[var(--ig-line)]">
                                <?php if($task->match_score >= 80): ?>
                                    <span class="ig-chip ig-chip-lime">Perfect match</span>
                                <?php elseif($task->match_score >= 60): ?>
                                    <span class="ig-chip">Good match</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- ─── Applications + Ledger ─── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
            <!-- Applications -->
            <section class="lg:col-span-7 ig-card p-8 ig-reveal">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="ig-display text-2xl">My applications</h2>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="text-sm font-semibold hover:text-[var(--ig-accent)]">Browse tasks →</a>
                </div>
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $profile->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-5 border border-[var(--ig-line)] rounded-2xl hover:border-[var(--ig-ink)] transition">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div>
                                    <h3 class="font-semibold text-[15px]"><?php echo e($application->task->title); ?></h3>
                                    <p class="ig-mono text-[11px] text-[var(--ig-muted)] mt-0.5"><?php echo e($application->task->startup->company_name); ?></p>
                                </div>
                                <?php if($application->submission && $application->submission->status === 'accepted'): ?>
                                    <span class="ig-chip ig-chip-success">Completed</span>
                                <?php elseif($application->submission && $application->submission->status === 'rejected'): ?>
                                    <span class="ig-chip ig-chip-danger">Rejected</span>
                                <?php elseif($application->submission && $application->submission->status === 'revision_requested'): ?>
                                    <span class="ig-chip ig-chip-warn">Revision</span>
                                <?php elseif($application->submission && in_array($application->submission->status, ['pending','submitted'])): ?>
                                    <span class="ig-chip">Under review</span>
                                <?php elseif($application->status === 'approved'): ?>
                                    <span class="ig-chip ig-chip-accent">Submit work</span>
                                <?php elseif($application->status === 'rejected'): ?>
                                    <span class="ig-chip ig-chip-danger">Rejected</span>
                                <?php else: ?>
                                    <span class="ig-chip">Pending</span>
                                <?php endif; ?>
                            </div>

                            <?php if($application->submission && $application->submission->feedback && in_array($application->submission->status, ['revision_requested','rejected'])): ?>
                                <p class="text-[12px] text-[var(--ig-muted)] bg-[var(--ig-bg)] rounded-lg p-3 mt-2"><?php echo e($application->submission->feedback); ?></p>
                            <?php endif; ?>

                            <div class="flex items-center gap-3 mt-3">
                                <a href="<?php echo e(route('tasks.show', $application->task_id)); ?>" class="text-[12px] font-semibold hover:text-[var(--ig-accent)]">View task →</a>
                                <?php if($application->status === 'approved' && !$application->submission): ?>
                                    <a href="<?php echo e(route('submissions.create', $application->id)); ?>" class="ig-btn ig-btn-primary" style="padding:8px 14px;font-size:12px;"><span>Submit work</span></a>
                                <?php endif; ?>
                                <?php if($application->submission && $application->submission->status === 'revision_requested'): ?>
                                    <a href="<?php echo e(route('submissions.revise', $application->submission->id)); ?>" class="ig-btn ig-btn-accent" style="padding:8px 14px;font-size:12px;"><span>Revise</span></a>
                                <?php endif; ?>
                                <?php if($application->submission && $application->submission->status === 'accepted' && !in_array($application->task_id, $reviewedTaskIds)): ?>
                                    <button onclick="openReviewModal(<?php echo e($application->task_id); ?>, '<?php echo e(addslashes($application->task->startup->company_name)); ?>')" class="ig-btn ig-btn-lime" style="padding:8px 14px;font-size:12px;"><span>Rate startup</span></button>
                                <?php endif; ?>
                                <?php if(in_array($application->status, ['hired', 'internship_accepted'])): ?>
                                    <a href="<?php echo e(route('messages.create', [$profile->id, $application->task->startup_profile_id, $application->task_id])); ?>" class="ig-btn" style="padding:8px 14px;font-size:12px;background:var(--ig-accent);color:var(--ig-bg);"><span>💬 Message Startup</span></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12">
                            <p class="ig-display text-2xl text-[var(--ig-muted)] mb-1">No applications yet.</p>
                            <a href="<?php echo e(route('tasks.index')); ?>" class="text-sm font-semibold hover:text-[var(--ig-accent)]">Browse available tasks →</a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Experience Ledger -->
            <section class="lg:col-span-5 ig-card p-8 ig-reveal">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="ig-display text-2xl">Experience ledger</h2>
                    <span class="ig-chip ig-chip-lime">Verified</span>
                </div>
                <div class="space-y-5 max-h-[440px] overflow-y-auto pr-1">
                    <?php if($profile->portfolio && $profile->portfolio->items->count() > 0): ?>
                        <?php $__currentLoopData = $profile->portfolio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative pl-5 border-l-2 border-[var(--ig-line)] hover:border-[var(--ig-accent)] transition pb-1">
                                <div class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full bg-[var(--ig-ink)]"></div>
                                <div class="flex items-start justify-between">
                                    <p class="font-semibold text-sm"><?php echo e($item->startup_name); ?></p>
                                    <span class="ig-mono text-[10px] text-[var(--ig-muted)]"><?php echo e($item->created_at->format('M Y')); ?></span>
                                </div>
                                <p class="text-[12px] text-[var(--ig-muted)] mt-0.5"><?php echo e($item->project_title); ?></p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="ig-mono text-[11px]">★ <?php echo e(number_format($item->rating_received, 1)); ?>/5</span>
                                    <?php $badge = $item->badgeLabel(); ?>
                                    <span class="ig-chip ig-chip-success" style="font-size:10px;padding:2px 8px;"><?php echo e($badge['label']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-10">
                            <p class="text-sm text-[var(--ig-muted)]">No verified records yet.</p>
                            <p class="text-[12px] text-[var(--ig-faint)] mt-1">Complete tasks to build your ledger.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- ─── Task Performance & Reputation ─── -->
        <section class="ig-card p-8 mb-12 ig-reveal">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="ig-eyebrow mb-1.5">— Completed Shipments</p>
                    <h2 class="ig-display text-2xl">Task Performance & Reputation Impact</h2>
                </div>
                <span class="ig-mono text-[11px] text-[var(--ig-muted)]"><?php echo e($completedTasks->count()); ?> completed</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $rating = $ratings->get($app->task_id);
                        $domainScore = optional($profile->reputationScore)->domain_scores[$app->task->domain] ?? 50.00;
                    ?>
                    <div class="p-6 border border-[var(--ig-line)] rounded-3xl flex flex-col justify-between bg-[var(--ig-bg-2)]/30 hover:border-[var(--ig-ink)] transition duration-300 relative overflow-hidden">
                        <div>
                            <div class="flex justify-between items-start gap-4 mb-3">
                                <div>
                                    <span class="ig-chip ig-chip-lime text-[9px] uppercase tracking-wider mb-2 font-bold"><?php echo e($app->task->domain); ?></span>
                                    <h4 class="font-bold text-base text-[var(--ig-ink)] mt-1.5 leading-snug"><?php echo e($app->task->title); ?></h4>
                                    <p class="ig-mono text-[11px] text-[var(--ig-muted)] mt-0.5">by <?php echo e($app->task->startup->company_name); ?></p>
                                </div>
                                <span class="ig-mono text-[10px] text-[var(--ig-muted)] whitespace-nowrap">
                                    <?php echo e($app->submission->updated_at->format('M d, Y')); ?>

                                </span>
                            </div>

                            <!-- Performance Stars and Comments -->
                            <div class="mt-4 pt-4 border-t border-dashed border-[var(--ig-line)]">
                                <?php if($rating): ?>
                                    <div class="flex items-center gap-1.5 mb-2">
                                        <div class="flex text-amber-500 text-sm">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <span><?php echo e($i <= $rating->rating ? '★' : '☆'); ?></span>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-xs font-bold text-[var(--ig-ink)] mt-0.5"><?php echo e(number_format($rating->rating, 1)); ?> / 5.0</span>
                                    </div>
                                    <?php if($rating->comment): ?>
                                        <p class="text-[12.5px] italic text-[var(--ig-ink-2)] bg-white/70 border border-[var(--ig-line-2)] rounded-2xl p-3.5 mt-2.5 leading-relaxed">
                                            "<?php echo e($rating->comment); ?>"
                                        </p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-100 text-[11.5px] text-amber-800 font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Awaiting startup rating & feedback
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Domain Reputation Impact -->
                        <div class="mt-5 pt-3 border-t border-[var(--ig-line)] flex items-center justify-between text-xs">
                            <span class="text-[var(--ig-muted)]">Domain reputation contribution:</span>
                            <span class="font-bold text-[var(--ig-accent)] ig-mono"><?php echo e(round($domainScore)); ?> / 100</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="md:col-span-2 text-center py-12">
                        <p class="text-sm text-[var(--ig-muted)]">No completed tasks yet. Ship tasks to establish your reputation.</p>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="inline-block mt-3 text-xs font-semibold text-[var(--ig-accent)] hover:underline">Browse tasks →</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Quick actions -->
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('tasks.index')); ?>" class="ig-btn ig-btn-primary"><span>Browse tasks</span><span class="arrow">→</span></a>
            <a href="<?php echo e(route('messages.index')); ?>" class="ig-btn ig-btn-ghost"><span>Messages</span></a>
        </div>
    </div>

    <!-- Review modal (kept functional) -->
    <div id="review-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl border border-[var(--ig-line)] shadow-2xl p-8 max-w-lg w-full mx-4 relative">
            <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-[var(--ig-muted)] hover:text-[var(--ig-ink)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <p class="ig-eyebrow mb-2">— Rate your experience</p>
            <h3 class="ig-display text-3xl mb-2">Review the startup</h3>
            <p class="text-sm text-[var(--ig-muted)] mb-6">Working with <span class="font-semibold text-[var(--ig-ink)]" id="review-startup-name"></span></p>

            <form id="review-form" method="POST" action="" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold mb-2">Star rating</label>
                    <div class="flex items-center gap-1" id="star-selector">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <button type="button" onclick="setStarRating(<?php echo e($i); ?>)" class="text-3xl text-[var(--ig-line-2)] hover:text-[var(--ig-accent)] transition" id="star-btn-<?php echo e($i); ?>">★</button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="review-rating-val" required value="">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Review</label>
                    <textarea name="review" rows="4" required minlength="5" maxlength="1000" class="ig-input" placeholder="Mentorship, communication, payment reliability, etc."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="ig-btn ig-btn-primary"><span>Submit review</span><span class="arrow">→</span></button>
                    <button type="button" onclick="closeReviewModal()" class="ig-btn ig-btn-ghost">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .lime-accent { background: linear-gradient(180deg, #FFFDF7 0%, var(--ig-bg-2) 100%); }
    </style>
    <script>
        function openReviewModal(taskId, startupName){
            document.getElementById('review-startup-name').textContent = startupName;
            document.getElementById('review-form').action = `/student/tasks/${taskId}/review`;
            document.getElementById('review-rating-val').value = '';
            for (let i=1;i<=5;i++){const b=document.getElementById(`star-btn-${i}`);b.classList.remove('text-[var(--ig-accent)]');b.classList.add('text-[var(--ig-line-2)]');}
            document.getElementById('review-modal').classList.remove('hidden');
        }
        function closeReviewModal(){ document.getElementById('review-modal').classList.add('hidden'); }
        function setStarRating(r){
            document.getElementById('review-rating-val').value = r;
            for (let i=1;i<=5;i++){const b=document.getElementById(`star-btn-${i}`);if(i<=r){b.classList.add('text-[var(--ig-accent)]');b.classList.remove('text-[var(--ig-line-2)]');}else{b.classList.remove('text-[var(--ig-accent)]');b.classList.add('text-[var(--ig-line-2)]');}}
        }
    </script>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/student/dashboard.blade.php ENDPATH**/ ?>