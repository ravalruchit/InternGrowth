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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Available Tasks</h1>

        <?php if(isset($isLimited) && $isLimited): ?>
            <div class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-yellow-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-900">Limited Access - Only 5 Tasks Shown</h3>
                        <p class="text-yellow-800 mt-1">Verify your college email to unlock all available tasks and opportunities!</p>
                        <a href="<?php echo e(route('student.verification')); ?>" class="inline-block mt-3 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 font-semibold text-sm">
                            Verify College Email →
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $approvedApp = $task->applications->where('status', 'approved')->first();
                    $completedApp = $task->applications->filter(function($app) {
                        return $app->submission && $app->submission->status === 'accepted';
                    })->first();
                    $isCompleted = $task->status === 'completed' || $completedApp;
                    $isInProgress = !$isCompleted && $approvedApp;
                ?>
                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-6 <?php echo e($isCompleted ? 'border-l-4 border-green-500' : ($isInProgress ? 'border-l-4 border-yellow-500' : 'border-l-4 border-blue-500')); ?>">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-xl font-semibold text-gray-900 flex-1"><?php echo e($task->title); ?></h3>
                        <?php if($isCompleted): ?>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-2">
                                ✓ Completed
                            </span>
                        <?php elseif($isInProgress): ?>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-2">
                                ⏳ In Progress
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 ml-2">
                                📢 Open
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($isCompleted && $completedApp): ?>
                        <p class="text-sm text-green-700 mb-2">
                            <strong>Completed by:</strong> <?php echo e($completedApp->student->user->name); ?>

                        </p>
                    <?php elseif($isInProgress && $approvedApp): ?>
                        <p class="text-sm text-yellow-700 mb-2">
                            <strong>Working on it:</strong> <?php echo e($approvedApp->student->user->name); ?>

                        </p>
                    <?php endif; ?>
                    
                    <p class="text-sm text-gray-500 mb-2">
                        <a href="<?php echo e(route('startups.public-profile', $task->startup->id)); ?>" class="font-bold text-indigo-650 hover:text-indigo-855 hover:underline transition">
                            <?php echo e($task->startup->company_name); ?>

                        </a>
                    </p>
                    <p class="text-gray-600 mb-4"><?php echo e(Str::limit($task->description, 100)); ?></p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php $__currentLoopData = $task->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded"><?php echo e($skill->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-indigo-600"><?php echo e($task->reward_points); ?> pts</span>
                        <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:shadow-lg transition font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500">No tasks available at the moment.</p>
            <?php endif; ?>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/tasks/index.blade.php ENDPATH**/ ?>