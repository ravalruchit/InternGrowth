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
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— The marketplace</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Real work. <span class="ig-serif text-[var(--ig-accent)]">Real receipts.</span><br>
                    Pick what you want to ship.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <p class="text-sm text-[var(--ig-muted)] max-w-xs md:ml-auto">
                    Every task here is posted by a verified startup. Complete one — earn a stipend, and a permanent entry on your IPRS scorecard.
                </p>
            </div>
        </div>

        <!-- Verification banner -->
        <?php if(isset($isLimited) && $isLimited): ?>
            <div class="ig-banner ig-banner-warn mb-10 ig-anim-fade-up ig-delay-1">
                <svg class="w-6 h-6 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1">
                    <h3 class="ig-display text-xl mb-1">Limited access — only 5 tasks visible</h3>
                    <p class="text-sm text-[var(--ig-ink-2)]">Verify your college email to unlock the full marketplace and the rest of this week's open tasks.</p>
                </div>
                <a href="<?php echo e(route('student.verification')); ?>" class="ig-btn ig-btn-primary"><span>Verify email</span><span class="arrow">→</span></a>
            </div>
        <?php endif; ?>

        <!-- Filter bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-10 pb-5 border-b border-[var(--ig-line)] ig-reveal">
            <div class="flex flex-wrap items-center gap-2">
                <span class="ig-eyebrow mr-2">Filter</span>
                <button class="ig-chip ig-chip-ink filter-btn" data-filter="all">All</button>
                <button class="ig-chip filter-btn" data-filter="is-open">Open</button>
                <button class="ig-chip filter-btn" data-filter="is-progress">In progress</button>
                <button class="ig-chip filter-btn" data-filter="is-done">Completed</button>
            </div>
            <div class="flex items-center gap-2 text-sm text-[var(--ig-muted)]">
                <span id="tasks-count-display"><?php echo e(count($tasks ?? [])); ?> tasks live</span>
                <span class="w-2 h-2 rounded-full bg-[var(--ig-lime-deep)] animate-pulse ml-1"></span>
            </div>
        </div>

        <!-- Tasks grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $approvedApp = $task->applications->where('status', 'approved')->first();
                    $completedApp = $task->applications->filter(fn($app) => $app->submission && $app->submission->status === 'accepted')->first();
                    $isCompleted = $task->status === 'completed' || $completedApp;
                    $isInProgress = !$isCompleted && $approvedApp;
                    $statusClass = $isCompleted ? 'is-done' : ($isInProgress ? 'is-progress' : 'is-open');
                ?>

                <a href="<?php echo e(route('tasks.show', $task->id)); ?>"
                   class="ig-card ig-task-card <?php echo e($statusClass); ?> p-6 flex flex-col justify-between ig-reveal"
                   data-reveal-delay="<?php echo e(($idx % 6) * 60); ?>">
                    <!-- Top -->
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <?php if($isCompleted): ?>
                                <span class="ig-chip ig-chip-success">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Completed
                                </span>
                            <?php elseif($isInProgress): ?>
                                <span class="ig-chip ig-chip-warn">In progress</span>
                            <?php else: ?>
                                <span class="ig-chip ig-chip-accent">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--ig-accent)] animate-pulse"></span>
                                    Open
                                </span>
                            <?php endif; ?>

                            <span class="ig-mono text-[10px] text-[var(--ig-muted)]">#<?php echo e(str_pad($task->id, 4, '0', STR_PAD_LEFT)); ?></span>
                        </div>

                        <h3 class="ig-display text-[22px] leading-[1.1] mb-2 text-[var(--ig-ink)] line-clamp-2">
                            <?php echo e($task->title); ?>

                        </h3>

                        <p class="ig-mono text-[11px] text-[var(--ig-muted)] mb-4">
                            <span class="text-[var(--ig-ink-2)] font-semibold"><?php echo e($task->startup->company_name); ?></span>
                        </p>

                        <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed mb-5 line-clamp-3">
                            <?php echo e(Str::limit($task->description, 130)); ?>

                        </p>

                        <?php if($isCompleted && $completedApp): ?>
                            <p class="text-[11.5px] text-[var(--ig-muted)] mb-4 ig-mono">→ shipped by <?php echo e($completedApp->student->user->name); ?></p>
                        <?php elseif($isInProgress && $approvedApp): ?>
                            <p class="text-[11.5px] text-[var(--ig-muted)] mb-4 ig-mono">→ working: <?php echo e($approvedApp->student->user->name); ?></p>
                        <?php endif; ?>

                        <div class="flex flex-wrap gap-1.5 mb-6">
                            <?php $__currentLoopData = $task->skills->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="ig-tag"><?php echo e($skill->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($task->skills->count() > 4): ?>
                                <span class="ig-tag">+<?php echo e($task->skills->count() - 4); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bottom -->
                    <div class="flex items-end justify-between pt-5 border-t border-dashed border-[var(--ig-line)]">
                        <div>
                            <?php if($task->stipend): ?>
                                <p class="ig-eyebrow text-[10px] mb-1">Stipend</p>
                                <p class="ig-display text-[20px] text-[var(--ig-lime-deep)] font-bold">₹<?php echo e(number_format($task->stipend, 0)); ?></p>
                            <?php else: ?>
                                <p class="ig-eyebrow text-[10px] mb-1">Stipend</p>
                                <p class="text-xs text-[var(--ig-muted)] font-medium">Experience Task</p>
                            <?php endif; ?>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--ig-ink)] group">
                            View
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="transition-transform group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-24">
                    <p class="ig-display text-3xl text-[var(--ig-muted)] mb-2">No open tasks right now.</p>
                    <p class="text-sm text-[var(--ig-muted)]">Check back soon — startups post new work daily.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Below grid: helper -->
        <?php if(count($tasks) > 0 && auth()->check() && auth()->user()->isStudent()): ?>
            <div class="mt-16 ig-card-dark p-8 md:p-12 relative overflow-hidden ig-reveal">
                <div class="absolute -top-20 -right-20 w-60 h-60 rounded-full blur-3xl opacity-30" style="background:var(--ig-accent)"></div>
                <div class="relative grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div>
                        <p class="ig-eyebrow mb-3" style="color:#9C9580">— Pro tip</p>
                        <h3 class="ig-display text-3xl text-white">Apply to 3 tasks that match your skills. Founders shortlist within 48h.</h3>
                    </div>
                    <div class="md:text-right">
                        <a href="<?php echo e(route('student.profile')); ?>" class="ig-btn ig-btn-lime"><span>Tune your profile</span><span class="arrow">→</span></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const taskCards = document.querySelectorAll('.ig-task-card');
            const countDisplay = document.getElementById('tasks-count-display');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active style class from all buttons
                    filterBtns.forEach(b => b.classList.remove('ig-chip-ink'));
                    // Add active style class to clicked button
                    this.classList.add('ig-chip-ink');

                    const filter = this.getAttribute('data-filter');
                    let visibleCount = 0;

                    taskCards.forEach(card => {
                        if (filter === 'all' || card.classList.contains(filter)) {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Update live count display
                    if (filter === 'all') {
                        countDisplay.textContent = `${visibleCount} tasks live`;
                    } else {
                        const filterName = this.textContent.trim().toLowerCase();
                        countDisplay.textContent = `${visibleCount} ${filterName} tasks`;
                    }
                });
            });
        });
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/tasks/index.blade.php ENDPATH**/ ?>