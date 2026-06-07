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
    <div class="max-w-3xl mx-auto px-4 py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <?php if($notifications->total() > 0): ?>
                <form method="POST" action="<?php echo e(route('notifications.destroy-all')); ?>"
                      onsubmit="return confirm('Clear all notifications?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="text-sm text-red-500 hover:text-red-700 font-medium">Clear all</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($notifications->isEmpty()): ?>
            <div class="bg-white rounded-xl shadow p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">You're all caught up!</p>
                <p class="text-gray-400 text-sm mt-1">No notifications yet.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $iconBg = match($notification->type) {
                            'success' => 'bg-green-100',
                            'warning' => 'bg-yellow-100',
                            'error'   => 'bg-red-100',
                            default   => 'bg-blue-100',
                        };
                        $iconColor = match($notification->type) {
                            'success' => 'text-green-600',
                            'warning' => 'text-yellow-600',
                            'error'   => 'text-red-600',
                            default   => 'text-blue-600',
                        };
                        $icon = match($notification->type) {
                            'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                            'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                            default   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        };
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border <?php echo e($notification->is_read ? 'border-gray-100 opacity-75' : 'border-indigo-200 ring-1 ring-indigo-100'); ?> p-4 flex items-start gap-4 transition hover:shadow-md">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-full <?php echo e($iconBg); ?> flex items-center justify-center">
                            <svg class="w-5 h-5 <?php echo e($iconColor); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?php echo $icon; ?>

                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-semibold text-gray-900 text-sm <?php echo e($notification->is_read ? '' : 'text-indigo-900'); ?>">
                                    <?php echo e($notification->title); ?>

                                    <?php if(!$notification->is_read): ?>
                                        <span class="ml-2 inline-block w-2 h-2 bg-indigo-500 rounded-full align-middle"></span>
                                    <?php endif; ?>
                                </p>
                                <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                                    <?php echo e($notification->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-0.5"><?php echo e($notification->message); ?></p>
                        </div>

                        <!-- Delete -->
                        <form method="POST" action="<?php echo e(route('notifications.destroy', $notification->id)); ?>" class="flex-shrink-0">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-gray-300 hover:text-red-400 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($notifications->hasPages()): ?>
                <div class="mt-6"><?php echo e($notifications->links()); ?></div>
            <?php endif; ?>
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
<?php /**PATH D:\InternGrowth\InternGrowth\resources\views/notifications/index.blade.php ENDPATH**/ ?>