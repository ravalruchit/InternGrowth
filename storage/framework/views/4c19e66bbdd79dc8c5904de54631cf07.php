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
        
        <!-- Flash Messages -->
        <?php if(session('success')): ?>
            <div class="ig-banner ig-banner-success mb-6 text-sm">
                <p class="font-bold text-emerald-950"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="ig-banner mb-6 p-4 bg-red-50 border-red-200 text-sm text-red-900 font-bold">
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Student Bio & IPRS Rating -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Base Card -->
                <div class="ig-card p-6 bg-gradient-to-br from-white to-[var(--ig-bg-2)] border border-[var(--ig-line)]">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-[var(--ig-surface-ink)] flex items-center justify-center text-2xl font-black text-white uppercase shadow-lg font-poppins flex-shrink-0">
                            <?php echo e(substr($profile->user->name, 0, 2)); ?>

                        </div>
                        <div>
                            <h1 class="ig-display text-2xl text-[var(--ig-ink)] leading-tight"><?php echo e($profile->user->name); ?></h1>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                <?php if($profile->primary_domain): ?>
                                    <?php
                                        $emoji = '💻';
                                        if ($profile->primary_domain == 'UI/UX Design') $emoji = '🎨';
                                        elseif ($profile->primary_domain == 'Digital Marketing') $emoji = '📈';
                                        elseif ($profile->primary_domain == 'Data & AI') $emoji = '🤖';
                                        elseif ($profile->primary_domain == 'Content & Business') $emoji = '💼';
                                    ?>
                                    <span class="ig-chip ig-chip-lime text-[10px] font-bold"><?php echo e($emoji); ?> <?php echo e($profile->primary_domain); ?></span>
                                <?php else: ?>
                                    <span class="ig-chip text-[10px] font-medium">Declared Profile</span>
                                <?php endif; ?>
                                <?php if($profile->preferred_role): ?>
                                    <span class="ig-chip text-[10px] font-semibold bg-[var(--ig-bg-2)] border border-[var(--ig-line)] text-[var(--ig-ink-2)]"><?php echo e($profile->preferred_role); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-6 font-normal"><?php echo e($profile->bio ?? 'No bio provided.'); ?></p>

                    <div class="border-t border-[var(--ig-line)] pt-5">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Academic Status</h3>
                        <p class="text-sm font-semibold text-[var(--ig-ink)]">🏫 <?php echo e($profile->college_name ?? 'N/A'); ?></p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1"><?php echo e($profile->college_email ?? 'N/A'); ?></p>
                    </div>
                </div>

                <!-- IPRS Score Card -->
                <?php
                    $score = $profile->reputationScore;
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

                <!-- B2B Direct Offers -->
                <?php if(auth()->check() && auth()->user()->isStartup()): ?>
                    <div class="ig-card p-6 space-y-4">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Direct Acquisition Pipeline</h3>
                        <p class="text-xs text-[var(--ig-muted)] leading-relaxed">Directly engage this student using verified work history and bypass standard interviews.</p>
                        
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

            <!-- Right Column: Skills & Experience Ledger -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Skills card -->
                <div class="ig-card p-6">
                    <h2 class="text-sm font-bold text-[var(--ig-ink)] mb-4 flex items-center">
                        <span class="mr-1.5">⚡</span> Verified Skill Badges
                    </h2>
                    
                    <?php
                        $verifiedSkills = $profile->skillVerifications->pluck('skill_id')->toArray();
                        $skillScores = $profile->skillVerifications->pluck('score', 'skill_id')->toArray();
                    ?>

                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $profile->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(in_array($skill->id, $verifiedSkills)): ?>
                                <?php $skillScore = $skillScores[$skill->id] ?? null; ?>
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[var(--ig-lime)] border border-[var(--ig-lime-deep)] text-[var(--ig-ink)]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--ig-ink)] mr-2 animate-pulse"></span>
                                    <?php echo e($skill->name); ?> 
                                    <?php if($skillScore): ?>
                                        <span class="ml-1.5 text-white bg-[var(--ig-surface-ink)] px-1.5 py-0.5 rounded-md text-[9px] font-mono"><?php echo e($skillScore); ?>/100</span>
                                    <?php else: ?>
                                        <span class="ml-1.5 text-white bg-[var(--ig-surface-ink)] px-1.5 py-0.5 rounded-md text-[9px]">Verified</span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-medium bg-[var(--ig-bg-2)] text-[var(--ig-ink-2)] border border-[var(--ig-line)]">
                                    <?php echo e($skill->name); ?>

                                </span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Experience Ledger timeline -->
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-[var(--ig-line)] pb-4">
                        <h2 class="ig-display text-2xl text-[var(--ig-ink)] flex items-center">
                            <span class="mr-2">📂</span> Experience Ledger
                        </h2>
                        <span class="ig-chip ig-chip-lime text-xs font-bold">
                            <?php echo e($profile->portfolio && $profile->portfolio->items ? $profile->portfolio->items->count() : 0); ?> Verified Placements
                        </span>
                    </div>

                    <?php if($profile->portfolio && $profile->portfolio->items && $profile->portfolio->items->count() > 0): ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $profile->portfolio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="ig-card p-6 hover:translate-y-[-2px] transition duration-350 shadow-sm border border-[var(--ig-line)] group relative overflow-hidden bg-white">
                                    <!-- Decorative Solid Side Bar -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[var(--ig-ink)] group-hover:bg-[var(--ig-accent)] transition-colors"></div>

                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="ig-display text-xl text-[var(--ig-ink)] group-hover:text-[var(--ig-accent)] transition-colors"><?php echo e($item->startup_name); ?></h3>
                                            <p class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mt-1">Verified Experience Record</p>
                                        </div>
                                        
                                        <!-- Rating -->
                                        <?php if($item->rating_received): ?>
                                            <span class="ig-chip ig-chip-warn text-[10px] font-bold">
                                                ⭐ <?php echo e(number_format($item->rating_received, 1)); ?> / 5.0
                                            </span>
                                        <?php else: ?>
                                            <span class="ig-chip ig-chip-lime text-[10px] font-bold">Verified Task</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl p-4 text-xs text-[var(--ig-ink-2)] mb-4">
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Domain:</span>
                                            <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->domain ?? 'Software Development'); ?></span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Role:</span>
                                            <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->role ?? 'Developer'); ?></span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Project:</span>
                                            <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->project_title); ?></span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-[var(--ig-muted)] font-medium sm:w-24">Date:</span>
                                            <span class="font-bold text-[var(--ig-ink)]"><?php echo e($item->created_at->format('M Y')); ?></span>
                                        </div>
                                    </div>

                                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-4 font-normal"><?php echo e($item->auto_summary); ?></p>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-[var(--ig-line)] pt-4">
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
                            <p class="font-bold text-sm text-[var(--ig-ink)]">This student hasn't completed any microtasks yet.</p>
                            <p class="text-xs mt-1">Once tasks are completed, they will automatically appear here as verified experience records.</p>
                        </div>
                    <?php endif; ?>
                </div>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/student/public-profile.blade.php ENDPATH**/ ?>