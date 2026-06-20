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
                <p class="ig-eyebrow mb-3">— Hall of Fame</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Student <span class="ig-serif text-[var(--ig-accent)]">Leaderboard.</span><br>
                    Top shippers in the ecosystem.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <p class="text-sm text-[var(--ig-muted)] max-w-xs md:ml-auto">
                    Rankings are updated live based on total verified points earned and your IPRS/reliability scores.
                </p>
            </div>
        </div>

        <!-- Leaderboard Table Container -->
        <div class="ig-card p-6 md:p-8 overflow-hidden ig-reveal">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[var(--ig-line)] pb-4 text-[var(--ig-muted)]">
                            <th class="ig-eyebrow pb-4 w-20">Rank</th>
                            <th class="ig-eyebrow pb-4">Student</th>
                            <th class="ig-eyebrow pb-4 text-right pr-6">Points</th>
                            <th class="ig-eyebrow pb-4 text-right">Reliability</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)]">
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $rank = $index + 1;
                                $isTopThree = $rank <= 3;
                                $rankClass = $rank === 1 ? 'bg-[var(--ig-lime)] text-[var(--ig-ink)] font-bold' : ($rank === 2 ? 'bg-[var(--ig-accent)] text-white font-bold' : ($rank === 3 ? 'bg-amber-100 text-amber-900 font-bold' : 'bg-[var(--ig-bg-2)] text-[var(--ig-ink-2)]'));
                            ?>
                            <tr class="hover:bg-[var(--ig-bg)] transition-colors group">
                                <td class="py-5 font-semibold text-sm">
                                    <span class="inline-flex w-8 h-8 rounded-xl items-center justify-center text-xs <?php echo e($rankClass); ?>">
                                        <?php echo e($rank); ?>

                                    </span>
                                </td>
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[var(--ig-ink)] flex items-center justify-center text-[var(--ig-bg)] ig-display text-sm font-semibold">
                                            <?php echo e(strtoupper(substr($student->user->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('students.public-profile', $student->id)); ?>" class="ig-display text-lg hover:text-[var(--ig-accent)] transition font-semibold">
                                                <?php echo e($student->user->name); ?>

                                            </a>
                                            <p class="ig-mono text-[10px] text-[var(--ig-muted)]">Verified Talent</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 text-right font-semibold pr-6">
                                    <span class="ig-display text-xl text-[var(--ig-ink)]">
                                        <?php echo e(number_format($student->wallet->balance ?? 0)); ?>

                                        <span class="text-[11px] text-[var(--ig-muted)] font-normal ml-0.5">pts</span>
                                    </span>
                                </td>
                                <td class="py-5 text-right">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        <span class="ig-mono text-sm font-semibold text-[var(--ig-ink-2)]">
                                            <?php echo e(number_format($student->reliability_score * 100, 0)); ?>%
                                        </span>
                                        <div class="w-16 h-1.5 bg-[var(--ig-line)] rounded-full overflow-hidden hidden sm:block">
                                            <div class="h-full bg-[var(--ig-accent)] rounded-full" style="width: <?php echo e($student->reliability_score * 100); ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/leaderboard/index.blade.php ENDPATH**/ ?>