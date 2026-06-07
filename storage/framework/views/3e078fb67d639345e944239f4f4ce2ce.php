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
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Welcome back, <?php echo e(auth()->user()->name); ?>! 👋</h1>
            <p class="text-gray-600 mt-2">Here's your dashboard overview</p>
        </div>

        <!-- Verification Banner -->
        <?php if(!$profile->is_verified): ?>
            <div class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-yellow-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-900">Limited Access - Verify Your College Email</h3>
                            <p class="text-yellow-800 mt-1">You can currently see only 5 tasks. Verify your college email to unlock all tasks and opportunities!</p>
                            <a href="<?php echo e(route('student.verification')); ?>" class="inline-block mt-3 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 font-semibold text-sm">
                                Verify Now →
                            </a>
                        </div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-600 hover:text-yellow-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <div>
                        <p class="text-green-900 font-semibold">✓ Verified Student</p>
                        <p class="text-green-700 text-sm"><?php echo e($profile->college_name); ?> • Full access to all tasks</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Wallet Balance Card -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Wallet Balance</p>
                        <p class="text-4xl font-bold mt-2">₹<?php echo e(number_format($profile->wallet_balance, 2)); ?></p>
                        <a href="<?php echo e(route('wallet.index')); ?>" class="text-xs text-white underline mt-2 inline-block">View Wallet</a>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Points Balance</p>
                        <p class="text-4xl font-bold mt-2"><?php echo e($profile->wallet->balance ?? 0); ?></p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Applications</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2"><?php echo e($profile->applications->count()); ?></p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Reliability Score</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2"><?php echo e(number_format($profile->reliability_score * 100, 0)); ?>%</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Recommended Tasks -->
        <?php if($recommendedTasks && $recommendedTasks->count() > 0): ?>
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-lg p-6 mb-6 border-2 border-purple-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">🤖 AI Recommended for You</h2>
                    <p class="text-gray-600 text-sm mt-1">Tasks matched to your skills and experience</p>
                </div>
                <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-xs font-bold">AI POWERED</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__currentLoopData = $recommendedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white border-2 border-purple-200 rounded-lg p-4 hover:shadow-xl transition transform hover:scale-105">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-semibold text-lg text-gray-900 flex-1"><?php echo e($task->title); ?></h3>
                            <div class="ml-2">
                                <div class="flex items-center space-x-1">
                                    <span class="text-2xl font-bold text-purple-600"><?php echo e($task->match_score); ?>%</span>
                                </div>
                                <p class="text-xs text-gray-500 text-right">Match</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?php echo e(Str::limit($task->description, 100)); ?></p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-medium">
                                    <?php echo e($task->reward_points); ?> pts
                                </span>
                                <?php if($task->match_score >= 80): ?>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                        🔥 Perfect Match
                                    </span>
                                <?php elseif($task->match_score >= 60): ?>
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">
                                        ⭐ Good Match
                                    </span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                View →
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mt-4 text-center">
                <a href="<?php echo e(route('tasks.index')); ?>" class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                    View all tasks →
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- My Applications -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Applications</h2>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $profile->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg text-gray-900"><?php echo e($application->task->title); ?></h3>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($application->task->startup->company_name); ?></p>
                                <div class="flex items-center space-x-4 mt-3">
                                    <?php if($application->submission && $application->submission->status === 'accepted'): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            ✓ Completed
                                        </span>
                                    <?php elseif($application->submission && $application->submission->status === 'rejected'): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                            ✗ Rejected
                                        </span>
                                    <?php elseif($application->submission && $application->submission->status === 'revision_requested'): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                            🔄 Revision Requested
                                        </span>
                                    <?php elseif($application->submission && in_array($application->submission->status, ['pending', 'submitted'])): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            ⏳ Under Review
                                        </span>
                                    <?php elseif($application->status === 'approved'): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            Approved - Submit Work
                                        </span>
                                    <?php elseif($application->status === 'rejected'): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                            Application Rejected
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            Pending Review
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($application->submission && $application->submission->status === 'revision_requested' && $application->submission->feedback): ?>
                                    <div class="mt-3 p-3 bg-orange-50 border-l-4 border-orange-400 rounded">
                                        <p class="text-xs font-semibold text-orange-800 mb-1">Revision Feedback:</p>
                                        <p class="text-xs text-orange-700"><?php echo e($application->submission->feedback); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($application->submission && $application->submission->status === 'rejected' && $application->submission->feedback): ?>
                                    <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-400 rounded">
                                        <p class="text-xs font-semibold text-red-800 mb-1">Rejection Reason:</p>
                                        <p class="text-xs text-red-700"><?php echo e($application->submission->feedback); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex items-center space-x-3 mt-4">
                                    <a href="<?php echo e(route('tasks.show', $application->task_id)); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        View Task →
                                    </a>
                                    <?php if($application->status === 'approved' && !$application->submission): ?>
                                        <a href="<?php echo e(route('submissions.create', $application->id)); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition">
                                            Submit Work
                                        </a>
                                    <?php endif; ?>
                                    <?php if($application->submission && $application->submission->status === 'revision_requested'): ?>
                                        <a href="<?php echo e(route('submissions.revise', $application->submission->id)); ?>" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition">
                                            Revise Submission
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 mt-2">No applications yet.</p>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">Browse available tasks →</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- My Certificates -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Certificates 🏆</h2>
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $profile->certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg text-gray-900"><?php echo e($certificate->task->title); ?></h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Certificate #<?php echo e($certificate->certificate_number); ?>

                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Issued: <?php echo e($certificate->issued_at->format('M d, Y')); ?>

                                </p>
                            </div>
                            <a href="<?php echo e(route('student.certificates.download', $certificate->id)); ?>" 
                               class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition">
                                📄 Download
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 mt-2">No certificates yet.</p>
                        <p class="text-gray-400 text-sm mt-1">Complete tasks to earn certificates!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-4">
            <a href="<?php echo e(route('tasks.index')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition">
                Browse Tasks
            </a>
            <a href="<?php echo e(route('messages.index')); ?>" class="bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg font-medium hover:shadow-md transition">
                Messages
            </a>
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
<?php /**PATH D:\InternGrowth\InternGrowth\resources\views/student/dashboard.blade.php ENDPATH**/ ?>