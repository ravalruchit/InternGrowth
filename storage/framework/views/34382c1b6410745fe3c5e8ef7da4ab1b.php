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
            <h1 class="text-4xl font-bold text-gray-900">Welcome, <?php echo e($profile->company_name); ?>! 🚀</h1>
            <p class="text-gray-600 mt-2">Manage your tasks and applications</p>
            
            <?php if(!$profile->is_verified): ?>
                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <span class="font-medium">Account Pending Verification</span> - 
                                    <?php if($profile->verification_status === 'pending' && $profile->verification_submitted_at): ?>
                                        Your verification request is under review.
                                    <?php elseif($profile->verification_status === 'rejected'): ?>
                                        Your verification was rejected. Please resubmit with correct documents.
                                    <?php else: ?>
                                        Submit your company documents to get verified and start posting tasks.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <?php if($profile->verification_status !== 'pending' || !$profile->verification_submitted_at): ?>
                            <a href="<?php echo e(route('startup.verification')); ?>" class="ml-4 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-medium whitespace-nowrap">
                                <?php echo e($profile->verification_status === 'rejected' ? 'Resubmit' : 'Get Verified'); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif(session()->has('startup_just_verified_' . auth()->id())): ?>
                <div id="verified-alert" class="mt-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <span class="font-medium">Verified Account</span> - Your startup is verified and can post tasks.
                            </p>
                        </div>
                    </div>
                </div>
                <script>
                    // Auto-dismiss verified alert after 3 seconds and clear session flag
                    const verifiedAlert = document.getElementById('verified-alert');
                    if (verifiedAlert) {
                        setTimeout(() => {
                            verifiedAlert.style.transition = 'opacity 0.5s ease-out';
                            verifiedAlert.style.opacity = '0';
                            setTimeout(() => {
                                verifiedAlert.remove();
                                // Clear the session flag via AJAX
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
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Wallet Balance Card -->
            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Wallet Balance</p>
                        <p class="text-4xl font-bold mt-2">₹<?php echo e(number_format($profile->wallet_balance, 2)); ?></p>
                        <div class="flex gap-3 mt-2">
                            <a href="<?php echo e(route('wallet.index')); ?>" class="text-xs text-white underline">View Transactions</a>
                            <a href="<?php echo e(route('wallet.topup')); ?>" class="text-xs text-white underline">+ Request Top-up</a>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Posted Tasks</p>
                        <p class="text-4xl font-bold mt-2"><?php echo e($tasks->count()); ?></p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Applications</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2"><?php echo e($tasks->sum(fn($t) => $t->applications->count())); ?></p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Reward Points Given</p>
                        <p class="text-4xl font-bold mt-2"><?php echo e($totalPointsGiven); ?></p>
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
                        <p class="text-gray-600 text-sm font-medium">Credibility Score</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2"><?php echo e(number_format($profile->credibility_score * 100, 0)); ?>%</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Score & Student Reviews Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Trust Score Breakdown -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 lg:col-span-1">
                <h3 class="text-lg font-bold text-gray-900 mb-4 font-poppins flex items-center justify-between">
                    <span>🛡️ Trust Score Breakdown</span>
                    <?php
                        $ts = $profile->trustScore;
                        $overall = $ts ? $ts->overall_score : ($profile->credibility_score * 100);
                        if ($overall <= 0) $overall = 100;
                    ?>
                    <span class="text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full font-bold">
                        <?php echo e(number_format($overall, 0)); ?>/100
                    </span>
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                            <span>Verification Status (25%)</span>
                            <span><?php echo e($ts ? number_format($ts->verification_score, 0) : ($profile->is_verified ? '100' : '0')); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: <?php echo e($ts ? $ts->verification_score : ($profile->is_verified ? '100' : '0')); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                            <span>Payment Reliability (25%)</span>
                            <span><?php echo e($ts ? number_format($ts->payment_score, 0) : ($profile->wallet_balance < 0 ? '50' : '100')); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: <?php echo e($ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? '50' : '100')); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                            <span>Student Rating Score (20%)</span>
                            <span><?php echo e($ts ? number_format($ts->student_rating_score, 0) : '100'); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: <?php echo e($ts ? $ts->student_rating_score : '100'); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                            <span>Hiring Success & Offers (15%)</span>
                            <span><?php echo e($ts ? number_format($ts->hiring_score, 0) : '100'); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: <?php echo e($ts ? $ts->hiring_score : '100'); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Reviews Listing -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 lg:col-span-2">
                <h3 class="text-lg font-bold text-gray-900 mb-4 font-poppins">⭐ Student Reviews (<?php echo e($profile->reviews->count()); ?>)</h3>
                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                    <?php $__empty_1 = true; $__currentLoopData = $profile->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-b border-gray-150 pb-4 last:border-none last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-850 text-sm font-poppins"><?php echo e($review->student->user->name); ?></h4>
                                    <p class="text-[10px] text-gray-400 font-semibold">Project: <?php echo e($review->task->title); ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-yellow-500 text-sm">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $review->rating): ?> ★ <?php else: ?> ☆ <?php endif; ?>
                                        <?php endfor; ?>
                                    </span>
                                    <p class="text-[10px] text-gray-400 font-medium"><?php echo e($review->created_at->format('M d, Y')); ?></p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-650 italic leading-relaxed">"<?php echo e($review->review); ?>"</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12 text-gray-400 text-sm my-auto">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>No student reviews received yet.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Hiring Pipeline / Sent Offers -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Hiring Pipeline / Sent Offers</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Candidate</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Offer Type</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Compensation</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $hiringOffers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-9 w-9 bg-indigo-100 text-indigo-850 rounded-lg flex items-center justify-center font-bold">
                                            <?php echo e(substr($offer->student->user->name, 0, 2)); ?>

                                        </div>
                                        <div class="ml-3">
                                            <a href="<?php echo e(route('students.public-profile', $offer->student_profile_id)); ?>" target="_blank" class="text-sm font-semibold text-gray-900 hover:underline">
                                                <?php echo e($offer->student->user->name); ?>

                                            </a>
                                            <p class="text-xs text-gray-500"><?php echo e($offer->student->user->email); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($offer->offer_type === 'internship' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'); ?>">
                                        <?php echo e(ucfirst($offer->offer_type)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    ₹<?php echo e(number_format($offer->compensation, 2)); ?>/<?php echo e($offer->compensation_period === 'annual' ? 'yr' : 'mo'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($offer->start_date->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?php echo e($offer->status === 'accepted' ? 'bg-green-100 text-green-800' : ($offer->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($offer->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))); ?>">
                                        <?php echo e(ucfirst($offer->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if($offer->status === 'pending'): ?>
                                        <form method="POST" action="<?php echo e(route('startup.offers.withdraw', $offer->id)); ?>" onsubmit="return confirm('Are you sure you want to withdraw this offer?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-200 transition">
                                                Withdraw Offer
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 font-medium">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    No hiring offers extended yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My Tasks -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">My Tasks</h2>
                <?php if($profile->is_verified): ?>
                    <a href="<?php echo e(route('tasks.create')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover:shadow-lg transition">
                        + Post New Task
                    </a>
                <?php else: ?>
                    <button disabled class="bg-gray-300 text-gray-500 px-6 py-2 rounded-lg font-medium cursor-not-allowed" title="Account must be verified to post tasks">
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
                    <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="font-semibold text-lg text-gray-900"><?php echo e($task->title); ?></h3>
                                    <?php if($task->status === 'completed' || $completedApp): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            ✓ Completed
                                        </span>
                                    <?php elseif($approvedApp): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ In Progress
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            📢 Open
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($completedApp): ?>
                                    <p class="text-sm text-green-700 mb-2">
                                        <strong>Completed by:</strong> <?php echo e($completedApp->student->user->name); ?>

                                    </p>
                                <?php elseif($approvedApp): ?>
                                    <p class="text-sm text-yellow-700 mb-2">
                                        <strong>Working on it:</strong> <?php echo e($approvedApp->student->user->name); ?>

                                    </p>
                                <?php endif; ?>
                                
                                <p class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium"><?php echo e($task->applications->count()); ?></span> applications • 
                                    <span class="font-medium"><?php echo e($task->reward_points); ?></span> points
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="inline-flex items-center space-x-1 text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>View Details</span>
                                    </a>
                                    <?php if($task->applications()->where('status', 'approved')->count() === 0): ?>
                                        <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="inline-flex items-center space-x-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span>Edit</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="inline-flex items-center space-x-1 bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-sm cursor-not-allowed" title="Cannot edit after approving an application">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span>Locked</span>
                                        </span>
                                    <?php endif; ?>
                                    <?php if($task->applications->count() === 0): ?>
                                        <form method="POST" action="<?php echo e(route('tasks.destroy', $task->id)); ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="inline-flex items-center space-x-1 bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="inline-flex items-center space-x-1 bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-sm cursor-not-allowed" title="Cannot delete task with applications">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            <span>Can't Delete</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 mt-2">No tasks posted yet.</p>
                        <?php if($profile->is_verified): ?>
                            <a href="<?php echo e(route('tasks.create')); ?>" class="text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">Post your first task →</a>
                        <?php else: ?>
                            <p class="text-gray-400 mt-2">Account verification required to post tasks</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="<?php echo e(route('messages.index')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition">
                Messages
            </a>
        </div>

        <!-- Completed Tasks History -->
        <?php if($completedTasks->count() > 0): ?>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center space-x-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Completed Tasks History</span>
            </h2>
            <div class="space-y-4">
                <?php $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $completedApp = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->first();
                    ?>
                    <?php if($completedApp): ?>
                    <div class="border border-green-200 bg-green-50 rounded-lg p-5 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="font-semibold text-lg text-gray-900"><?php echo e($task->title); ?></h3>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        ✓ Completed
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">
                                            <strong class="text-gray-700">Completed by:</strong> <?php echo e($completedApp->student->user->name); ?>

                                        </p>
                                        <p class="text-sm text-gray-600 mb-1">
                                            <strong class="text-gray-700">Reliability Score:</strong> 
                                            <span class="text-green-600 font-semibold"><?php echo e(number_format($completedApp->student->reliability_score * 100, 0)); ?>%</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <strong class="text-gray-700">Points Awarded:</strong> 
                                            <span class="text-indigo-600 font-semibold"><?php echo e($task->reward_points); ?> pts</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">
                                            <strong class="text-gray-700">Completed on:</strong> <?php echo e($completedApp->submission->updated_at->format('M d, Y')); ?>

                                        </p>
                                        <?php if($task->ratings && $task->ratings->where('student_profile_id', $completedApp->student_profile_id)->first()): ?>
                                            <?php
                                                $rating = $task->ratings->where('student_profile_id', $completedApp->student_profile_id)->first();
                                            ?>
                                            <p class="text-sm text-gray-600 mb-1">
                                                <strong class="text-gray-700">Your Rating:</strong>
                                                <span class="text-yellow-500">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <?php if($i <= $rating->rating): ?>
                                                            ⭐
                                                        <?php else: ?>
                                                            ☆
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </span>
                                            </p>
                                            <?php if($rating->review): ?>
                                                <p class="text-sm text-gray-600 italic">"<?php echo e(Str::limit($rating->review, 80)); ?>"</p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if($completedApp->submission->submission_url): ?>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <strong class="text-gray-700">Submission:</strong> 
                                        <a href="<?php echo e($completedApp->submission->submission_url); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 underline">
                                            View Work →
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <div class="flex items-center space-x-3 mt-3">
                                    <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        View Full Details →
                                    </a>
                                    <a href="<?php echo e(route('messages.index')); ?>" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/startup/dashboard.blade.php ENDPATH**/ ?>