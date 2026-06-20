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
    <div class="ig-container py-12 ig-anim-fade-up">
        
        <!-- Hero Header Card -->
        <div class="ig-card p-6 sm:p-8 bg-gradient-to-br from-white via-[var(--ig-bg-2)] to-white relative overflow-hidden mb-8 border border-[var(--ig-line-2)] shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <!-- Avatar initials -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[var(--ig-surface-ink)] flex items-center justify-center text-3xl sm:text-4xl font-black text-white uppercase shadow-lg flex-shrink-0 font-poppins">
                        <?php echo e(substr($profile->user->name, 0, 2)); ?>

                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="ig-display text-3xl sm:text-4xl text-[var(--ig-ink)]"><?php echo e($profile->user->name); ?></h1>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                <?php if($profile->primary_domain): ?>
                                    <?php
                                        $emoji = '💻';
                                        if ($profile->primary_domain == 'UI/UX Design') $emoji = '🎨';
                                        elseif ($profile->primary_domain == 'Digital Marketing') $emoji = '📈';
                                        elseif ($profile->primary_domain == 'Data & AI') $emoji = '🤖';
                                        elseif ($profile->primary_domain == 'Content & Business') $emoji = '💼';
                                    ?>
                                    <span class="ig-chip ig-chip-lime text-[11px] font-bold"><?php echo e($emoji); ?> <?php echo e($profile->primary_domain); ?></span>
                                <?php else: ?>
                                    <span class="ig-chip text-[11px] font-medium">Declared Profile</span>
                                <?php endif; ?>
                                <?php if($profile->preferred_role): ?>
                                    <span class="ig-chip text-[11px] font-semibold bg-[var(--ig-bg-2)] border border-[var(--ig-line)] text-[var(--ig-ink-2)]"><?php echo e($profile->preferred_role); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if($profile->college_name): ?>
                            <p class="text-sm font-semibold text-[var(--ig-muted)] mt-1">🏫 <?php echo e($profile->college_name); ?></p>
                        <?php endif; ?>

                        <?php if($profile->bio): ?>
                            <p class="text-sm text-[var(--ig-ink-2)] mt-3 max-w-xl leading-relaxed font-normal"><?php echo e($profile->bio); ?></p>
                        <?php endif; ?>

                        <p class="text-[10px] text-[var(--ig-faint)] font-mono mt-2">Member since <?php echo e($profile->created_at->format('M Y')); ?></p>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <?php if (isset($component)) { $__componentOriginal158033e17ac548ba8d985f1073c1aeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal158033e17ac548ba8d985f1073c1aeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.share-profile-button','data' => ['url' => route('talent.profile', $portfolio->custom_slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('share-profile-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('talent.profile', $portfolio->custom_slug))]); ?>
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
        </div>

        <!-- Hiring Metrics Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
            <?php
                $stats = [
                    ['label' => 'Total Projects', 'value' => $hiringSummary['total_projects'], 'icon' => '📂', 'chip' => 'ig-chip-ink'],
                    ['label' => 'Avg Rating', 'value' => $hiringSummary['avg_startup_rating'] ? $hiringSummary['avg_startup_rating'] . ' / 5.0' : 'N/A', 'icon' => '⭐', 'chip' => 'ig-chip-warn'],
                    ['label' => 'Verified Skills', 'value' => $hiringSummary['verified_skills'], 'icon' => '⚡', 'chip' => 'ig-chip-lime'],
                    ['label' => 'Offers Received', 'value' => $hiringSummary['offers_received'], 'icon' => '💼', 'chip' => 'ig-chip-accent'],
                    ['label' => 'IPRS Score', 'value' => $hiringSummary['iprs_score'], 'icon' => '🏆', 'chip' => 'ig-chip-success'],
                ];
            ?>
            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="ig-card p-4 text-center hover:translate-y-[-2px] transition duration-200 shadow-sm border border-[var(--ig-line)]">
                    <div class="text-2xl mb-1.5"><?php echo e($stat['icon']); ?></div>
                    <div class="ig-display text-xl sm:text-2xl text-[var(--ig-ink)]"><?php echo e($stat['value']); ?></div>
                    <div class="text-[9px] sm:text-[10px] text-[var(--ig-muted)] font-bold uppercase tracking-wider mt-1"><?php echo e($stat['label']); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Layout details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Panel: IPRS Gauge & Skills -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Reputation Score Widget -->
                <?php
                    $overall = $score ? $score->overall_score : 50.00;
                ?>
                <div class="ig-card-dark p-6 relative overflow-hidden shadow-xl">
                    <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-20" style="background:var(--ig-accent)"></div>
                    <div class="relative text-center border-b border-white/10 pb-5 mb-5">
                        <p class="ig-eyebrow text-[10px] uppercase tracking-widest text-[var(--ig-lime)]">Reputation Index</p>
                        <div class="flex items-baseline justify-center gap-1 mt-3">
                            <span class="ig-display text-5xl font-black text-white"><?php echo e(round($overall)); ?></span>
                            <span class="text-lg font-bold text-white/50">/100</span>
                        </div>
                        <p class="text-[10px]" style="color: #9C9580">Verified Professional Trust Rank</p>
                    </div>

                    <div class="space-y-4 text-xs">
                        <?php
                            $metrics = [
                                ['label' => 'Trust Score', 'value' => $score ? round($score->trust_score) : 50, 'color' => 'bg-indigo-400'],
                                ['label' => 'Completion Rate', 'value' => $score ? round($score->completion_rate) : 100, 'color' => 'bg-[var(--ig-lime)]'],
                                ['label' => 'On-Time Delivery', 'value' => $score ? round($score->on_time_rate) : 100, 'color' => 'bg-amber-400'],
                                ['label' => 'Startup Satisfaction', 'value' => $score ? round($score->satisfaction_rating * 20) : 100, 'color' => 'bg-cyan-400'],
                                ['label' => 'Interview Performance', 'value' => $score ? round($score->interview_performance_score) : 100, 'color' => 'bg-purple-400'],
                            ];
                        ?>

                        <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex justify-between font-semibold mb-1">
                                    <span style="color: #C9C1AE"><?php echo e($metric['label']); ?></span>
                                    <span class="text-white"><?php echo e($metric['value']); ?>%</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-1 overflow-hidden">
                                    <div class="<?php echo e($metric['color']); ?> h-1 rounded-full" style="width: <?php echo e(min($metric['value'], 100)); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($score && $score->interviews_attended > 0): ?>
                            <div class="grid grid-cols-2 gap-2 text-[10px] bg-white/5 border border-white/10 rounded-xl p-3 mt-4 text-white/80">
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">Attended</span>
                                    <span class="font-bold text-white text-xs"><?php echo e($score->interviews_attended); ?></span>
                                </div>
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">Success</span>
                                    <span class="font-bold text-white text-xs"><?php echo e(number_format($score->interview_success_rate, 0)); ?>%</span>
                                </div>
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">Strong Candidates</span>
                                    <span class="font-bold text-white text-xs"><?php echo e($score->strong_candidate_outcomes); ?></span>
                                </div>
                                <div>
                                    <span class="block text-white/40 uppercase tracking-wider font-semibold">No Shows</span>
                                    <span class="font-bold text-rose-455 text-xs"><?php echo e($score->no_shows); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Verified Skills list -->
                <div class="ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4 flex items-center">
                        <span class="mr-1.5">⚡</span> Skills Breakdown
                    </h3>

                    <div class="space-y-3">
                        <?php $__currentLoopData = $profile->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isVerified = in_array($skill->id, $verifiedSkills);
                                $startupCount = $startupVerificationCounts[$skill->id] ?? 0;
                                $skillScore = $skillScores[$skill->id] ?? null;
                            ?>

                            <?php if($isVerified): ?>
                                <div class="flex items-center justify-between bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl px-3.5 py-3 hover:border-[var(--ig-accent)] transition">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[var(--ig-accent)] animate-pulse"></div>
                                        <span class="text-xs font-bold text-[var(--ig-ink)]"><?php echo e($skill->name); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <?php if($startupCount > 0): ?>
                                            <span class="text-[9px] font-bold text-[var(--ig-accent)] bg-[var(--ig-accent-soft)] px-2 py-0.5 rounded-md">
                                                ✓ <?php echo e($startupCount); ?> <?php echo e(Str::plural('Startup', $startupCount)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-[9px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">
                                                ✓ Verified
                                            </span>
                                        <?php endif; ?>
                                        <?php if($skillScore): ?>
                                            <span class="text-[9px] font-bold text-[var(--ig-ink-2)] bg-white border border-[var(--ig-line)] px-1.5 py-0.5 rounded-md font-mono">
                                                <?php echo e($skillScore); ?>/100
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center justify-between border border-[var(--ig-line)] rounded-xl px-3.5 py-3 opacity-60">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[var(--ig-muted)]"></div>
                                        <span class="text-xs font-medium text-[var(--ig-ink-2)]"><?php echo e($skill->name); ?></span>
                                    </div>
                                    <span class="text-[9px] font-medium text-[var(--ig-muted)] bg-[var(--ig-bg-2)] px-2 py-0.5 rounded-md">Declared</span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- B2B Direct Offers -->
                <?php if(auth()->check() && auth()->user()->isStartup()): ?>
                    <div class="ig-card p-6 space-y-4">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Direct Acquisition Pipeline</h3>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Directly pitch to this candidate and bypass the standard hiring steps.</p>
                        
                        <div class="flex flex-col gap-2.5">
                            <button onclick="toggleModal('internship-modal')" class="ig-btn ig-btn-primary justify-center w-full">
                                💼 Offer Internship
                            </button>
                            <button onclick="toggleModal('job-modal')" class="ig-btn ig-btn-ghost justify-center w-full">
                                🚀 Offer Full-Time Job
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Panel: Experience Ledger -->
            <div class="lg:col-span-8 space-y-6">
                
                <div class="flex justify-between items-center border-b border-[var(--ig-line)] pb-4">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] flex items-center">
                        <span class="mr-2">📂</span> Verified Experience Ledger
                    </h2>
                    <span class="ig-chip ig-chip-lime text-xs font-bold">
                        <?php echo e($portfolio->items->count()); ?> Shipped <?php echo e(Str::plural('Project', $portfolio->items->count())); ?>

                    </span>
                </div>

                <?php if($portfolio->items->count() > 0): ?>
                    <div class="space-y-6">
                        <?php $__currentLoopData = $portfolio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $badge = $item->badgeLabel(); ?>
                            <div class="ig-card p-6 hover:translate-y-[-2px] transition duration-350 shadow-sm border border-[var(--ig-line)] group relative overflow-hidden">
                                <!-- Solid Tangerine/Chartreuse side indicator -->
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[var(--ig-ink)] group-hover:bg-[var(--ig-accent)] transition-colors"></div>

                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                                    <div>
                                        <h3 class="ig-display text-xl text-[var(--ig-ink)] group-hover:text-[var(--ig-accent)] transition-colors"><?php echo e($item->startup_name); ?></h3>
                                        <p class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mt-1"><?php echo e($item->project_title); ?></p>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="ig-chip text-[10px] font-bold">
                                            <?php echo e($badge['emoji']); ?> <?php echo e($badge['label']); ?>

                                        </span>
                                        <?php if($item->rating_received): ?>
                                            <span class="ig-chip ig-chip-warn text-[10px] font-bold">
                                                ⭐ <?php echo e(number_format($item->rating_received, 1)); ?> / 5.0
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl p-4 text-xs text-[var(--ig-ink-2)] mb-4">
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Domain</span>
                                        <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->domain ?? 'Software Development'); ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Role</span>
                                        <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->role ?? 'Developer'); ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Date</span>
                                        <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->completed_at ? $item->completed_at->format('M d, Y') : $item->created_at->format('M d, Y')); ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Status</span>
                                        <span class="font-bold text-emerald-700">✓ Accepted</span>
                                    </div>
                                </div>

                                <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-4 font-normal"><?php echo e($item->auto_summary); ?></p>

                                <?php if($item->hasEvidence()): ?>
                                    <div class="bg-white border border-[var(--ig-line)] rounded-xl p-4 mb-4">
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">📎 Code & Project Assets</p>
                                        <div class="flex flex-wrap gap-2">
                                            <?php if($item->github_url): ?>
                                                <a href="<?php echo e($item->github_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] text-xs font-bold text-[var(--ig-ink)] hover:border-[var(--ig-ink)] transition">
                                                    💻 Github Source
                                                </a>
                                            <?php endif; ?>
                                            <?php if($item->demo_url): ?>
                                                <a href="<?php echo e($item->demo_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] text-xs font-bold text-[var(--ig-ink)] hover:border-[var(--ig-ink)] transition">
                                                    🔗 Live Demo URL
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($item->screenshots && count($item->screenshots) > 0): ?>
                                            <div class="flex flex-wrap gap-2 mt-3">
                                                <?php $__currentLoopData = $item->screenshots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $screenshot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(isset($screenshot['path'])): ?>
                                                        <a href="<?php echo e(asset('storage/' . $screenshot['path'])); ?>" target="_blank" class="block w-14 h-14 rounded-lg overflow-hidden border border-[var(--ig-line-2)] hover:border-[var(--ig-ink)] transition">
                                                            <img src="<?php echo e(asset('storage/' . $screenshot['path'])); ?>" alt="Screenshot" class="w-full h-full object-cover">
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[var(--ig-line)] pt-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        <?php $__currentLoopData = $item->skills_demonstrated ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skillName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="ig-tag"><?php echo e($skillName); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="ig-card p-12 text-center text-[var(--ig-muted)] border-dashed">
                        <span class="text-4xl block mb-3">📂</span>
                        <p class="font-bold text-sm text-[var(--ig-ink)]">No ledger entries generated yet.</p>
                        <p class="text-xs mt-1">Once this student completes task submissions, verified entries will populate here.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <!-- Modals for startup Visitors -->
    <?php if(auth()->check() && auth()->user()->isStartup()): ?>
        <!-- Internship Offer Modal -->
        <div id="internship-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="toggleModal('internship-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle ig-card-dark border-none text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white bg-[var(--ig-surface-ink)]">
                    <h2 class="ig-display text-xl mb-4 text-white">💼 Pitch Internship Offer</h2>

                    <form action="<?php echo e(route('startup.offers.store')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="student_profile_id" value="<?php echo e($profile->id); ?>">
                        <input type="hidden" name="offer_type" value="internship">
                        <input type="hidden" name="compensation_period" value="monthly">

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Frontend Development Intern" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Role Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job duties, expectations..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Monthly Stipend (₹)</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 15000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">End Date (Optional)</label>
                            <input type="date" name="end_date" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Perks & Deliverables</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Certificate, Flexible Hours, Work From Home" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('internship-modal')" class="ig-btn ig-btn-ghost text-xs text-white border-white/20 hover:bg-white/10 hover:text-white" style="padding: 10px 18px;">Cancel</button>
                            <button type="submit" class="ig-btn ig-btn-lime text-xs" style="padding: 10px 22px;">Send Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Job Offer Modal -->
        <div id="job-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="toggleModal('job-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-middle ig-card-dark border-none text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white bg-[var(--ig-surface-ink)]">
                    <h2 class="ig-display text-xl mb-4 text-white">🚀 Pitch Full-Time Job Offer</h2>

                    <form action="<?php echo e(route('startup.offers.store')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="student_profile_id" value="<?php echo e($profile->id); ?>">
                        <input type="hidden" name="offer_type" value="job">

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Associate Backend Laravel Developer" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Role Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job responsibilities..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Compensation Amount (₹)</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 600000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Period</label>
                                <select name="compensation_period" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                                    <option value="annual">Annual CTC</option>
                                    <option value="monthly">Monthly Salary</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="start_date" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Contract Terms & Benefits</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Health Insurance, Annual Leave, Bonus Structure" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[var(--ig-lime)] resize-none"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('job-modal')" class="ig-btn ig-btn-ghost text-xs text-white border-white/20 hover:bg-white/10 hover:text-white" style="padding: 10px 18px;">Cancel</button>
                            <button type="submit" class="ig-btn ig-btn-lime text-xs" style="padding: 10px 22px;">Send Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/student/talent-profile.blade.php ENDPATH**/ ?>