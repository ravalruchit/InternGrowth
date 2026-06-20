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
                <p class="ig-eyebrow mb-3">— Ledger & Vault</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    My <span class="ig-serif text-[var(--ig-accent)]">Wallet.</span><br>
                    Track your earnings & deposits.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <?php if(auth()->user()->isStartup()): ?>
                    <a href="<?php echo e(route('wallet.topup')); ?>" class="ig-btn ig-btn-primary">
                        <span>+ Request Top-up</span><span class="arrow">→</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
            <!-- Balance Card -->
            <div class="lg:col-span-4 ig-card-dark p-8 relative overflow-hidden ig-reveal">
                <div class="absolute -top-24 -right-24 w-48 h-48 rounded-full blur-3xl opacity-30" style="background:var(--ig-accent)"></div>
                <div class="relative">
                    <p class="ig-eyebrow mb-3" style="color:#9C9580">Available Balance</p>
                    <p class="ig-display text-5xl text-white">₹<?php echo e(number_format($profile->wallet_balance, 2)); ?></p>
                    
                    <?php if(auth()->user()->isStartup()): ?>
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <p class="text-sm leading-relaxed" style="color:#C9C1AE">
                                Need to add funds to hire more talent?
                            </p>
                            <a href="<?php echo e(route('wallet.topup')); ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--ig-lime)] hover:underline mt-2">
                                Request a top-up
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <p class="text-[12.5px] leading-relaxed" style="color:#C9C1AE">
                                Withdraw your earnings directly to your bank account anytime.
                            </p>
                            <span class="ig-chip ig-chip-lime mt-3">All transactions verified</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Transaction Ledger -->
            <div class="lg:col-span-8 ig-card p-6 md:p-8 ig-reveal" data-reveal-delay="100">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-[var(--ig-line)]">
                    <h2 class="ig-display text-2xl">Transaction History</h2>
                    <span class="ig-mono text-xs text-[var(--ig-muted)]"><?php echo e(count($transactions)); ?> records</span>
                </div>

                <div class="divide-y divide-[var(--ig-line)]">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isCredit = in_array($transaction->type, ['credit', 'escrow_release']);
                            $sign = $isCredit ? '+' : '-';
                        ?>
                        <div class="py-5 flex justify-between items-center hover:bg-[var(--ig-bg)] transition-colors px-3 rounded-xl">
                            <div>
                                <p class="font-semibold text-[15px] text-[var(--ig-ink)]"><?php echo e($transaction->description); ?></p>
                                <p class="ig-mono text-[11px] text-[var(--ig-muted)] mt-1"><?php echo e($transaction->created_at->format('M d, Y · h:i A')); ?></p>
                                <?php if($transaction->reference_id): ?>
                                    <p class="ig-mono text-[10px] text-[var(--ig-faint)] mt-0.5">Ref: <?php echo e($transaction->reference_id); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-right">
                                <span class="ig-display text-xl <?php echo e($isCredit ? 'text-[var(--ig-ink)]' : 'text-[var(--ig-rose)]'); ?>">
                                    <?php echo e($sign); ?>₹<?php echo e(number_format($transaction->amount, 2)); ?>

                                </span>
                                <p class="ig-mono text-[10px] text-[var(--ig-muted)] capitalize mt-1"><?php echo e(str_replace('_', ' ', $transaction->type)); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="py-12 text-center text-[var(--ig-muted)]">
                            <p class="ig-display text-xl mb-1">No transaction history yet.</p>
                            <p class="text-xs">Your completed tasks and payments will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($transactions->hasPages()): ?>
                    <div class="pt-6 border-t border-[var(--ig-line)] mt-6">
                        <?php echo e($transactions->links()); ?>

                    </div>
                <?php endif; ?>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/wallet/index.blade.php ENDPATH**/ ?>