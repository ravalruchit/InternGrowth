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
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
        
        <!-- Top Profile Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-800 rounded-3xl p-8 mb-8 shadow-2xl text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-white/[0.02] bg-[size:30px_30px]" pointer-events-none></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl" pointer-events-none></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl" pointer-events-none></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-3xl font-bold uppercase shadow-lg shadow-indigo-500/30">
                        <?php echo e(substr($profile->company_name, 0, 2)); ?>

                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-3xl font-bold tracking-tight font-poppins"><?php echo e($profile->company_name); ?></h1>
                            <?php if($profile->is_verified): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                    ✓ Verified Startup
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-400">
                                    Pending Verification
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-slate-400 text-sm mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span><?php echo e($profile->company_address ?? 'Address not updated'); ?></span>
                        </p>
                    </div>
                </div>
                
                <?php if($profile->website): ?>
                    <div class="flex-shrink-0">
                        <a href="<?php echo e($profile->website); ?>" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-slate-700 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-medium shadow-lg hover:shadow-xl transition duration-300 gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            <span>Visit Website</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Summary Reputation Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Overall Trust Shield -->
            <?php
                $ts = $profile->trustScore;
                $overall = $ts ? $ts->overall_score : ($profile->credibility_score * 100);
                if ($overall <= 0) $overall = 70.00; // base default
            ?>
            <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 rounded-2xl shadow-xl p-6 text-white border border-indigo-500/20 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-indigo-150 text-xs font-semibold uppercase tracking-wider">Overall Trust Score</p>
                        <p class="text-4xl font-extrabold tracking-tight mt-2"><?php echo e(number_format($overall, 0)); ?>/100</p>
                        <p class="text-[10px] text-indigo-200 mt-2">🛡️ Composite credibility rating</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-3">
                        <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Students Hired -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-150 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Students Hired</p>
                        <p class="text-4xl font-extrabold text-slate-900 tracking-tight mt-2"><?php echo e($studentsHired); ?></p>
                        <p class="text-[10px] text-indigo-600 font-medium mt-2">Direct & task-based placements</p>
                    </div>
                    <div class="bg-indigo-50 rounded-2xl p-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Average Rating -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-150 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Average Rating</p>
                        <p class="text-4xl font-extrabold text-slate-900 tracking-tight mt-2">
                            <?php echo e($averageRating > 0 ? number_format($averageRating, 1) : 'N/A'); ?>

                        </p>
                        <p class="text-[10px] text-yellow-500 font-bold mt-2">
                            <?php if($averageRating > 0): ?>
                                ⭐ <?php echo e(number_format($averageRating, 1)); ?> / 5.0 Rating
                            <?php else: ?>
                                No student reviews yet
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="bg-amber-50 rounded-2xl p-3">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Payment Reliability -->
            <?php
                $paymentScoreVal = $ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? 50.00 : 100.00);
            ?>
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-150 transform hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Payment Reliability</p>
                        <p class="text-4xl font-extrabold text-slate-900 tracking-tight mt-2"><?php echo e(number_format($paymentScoreVal, 0)); ?>%</p>
                        <p class="text-[10px] text-green-600 font-medium mt-2">
                            <?php echo e($paymentScoreVal >= 100 ? '✅ 100% Reliable' : '⚠️ Balance Deficit warning'); ?>

                        </p>
                    </div>
                    <div class="bg-green-50 rounded-2xl p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Profile Details, Active Tasks & Student Reviews -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- About Section -->
                <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-150">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 font-poppins flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>About Startup</span>
                    </h2>
                    <p class="text-gray-650 leading-relaxed text-sm whitespace-pre-line"><?php echo e($profile->description ?? 'No company description provided.'); ?></p>
                </div>
                
                <!-- Active Tasks -->
                <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-150">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 font-poppins flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 00-2 2z"></path>
                            </svg>
                            <span>Open Tasks</span>
                        </span>
                        <span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-bold">
                            <?php echo e($activeTasks->count()); ?> Available
                        </span>
                    </h2>
                    
                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $activeTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border border-gray-150 hover:border-indigo-250 hover:shadow-xl rounded-2xl p-5 transition duration-300 group">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="font-bold text-gray-950 font-poppins group-hover:text-indigo-600 transition">
                                            <?php echo e($task->title); ?>

                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Reward Points: <span class="font-bold text-indigo-600"><?php echo e($task->reward_points); ?> pts</span></p>
                                        <div class="flex flex-wrap gap-1.5 mt-3">
                                            <?php $__currentLoopData = $task->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="text-[10px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium"><?php echo e($skill->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="flex sm:flex-col items-start sm:items-end justify-between sm:justify-center gap-2">
                                        <?php if($task->stipend): ?>
                                            <span class="text-lg font-extrabold text-green-600 font-poppins">₹<?php echo e(number_format($task->stipend, 0)); ?></span>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('tasks.show', $task->id)); ?>" 
                                           class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-xl text-xs hover:bg-indigo-700 transition">
                                            Apply Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8 text-gray-400 text-sm">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-3.586-3.586a2 2 0 00-2.828 0L12 14M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>This startup has no open tasks at the moment.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Reviews -->
                <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-150">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 font-poppins flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>Student Reviews</span>
                        </span>
                        <span class="text-xs bg-amber-50 text-amber-700 px-3 py-1 rounded-full font-bold">
                            <?php echo e($profile->reviews->count()); ?> Reviews
                        </span>
                    </h2>
                    
                    <div class="space-y-6">
                        <?php $__empty_1 = true; $__currentLoopData = $profile->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border-b border-gray-150 pb-6 last:border-none last:pb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm font-poppins"><?php echo e($review->student->user->name); ?></h4>
                                        <p class="text-[10px] text-gray-500 font-semibold">Project: <?php echo e($review->task->title); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-yellow-500 text-sm">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $review->rating): ?> ★ <?php else: ?> ☆ <?php endif; ?>
                                            <?php endfor; ?>
                                        </span>
                                        <p class="text-[10px] text-gray-400 font-semibold"><?php echo e($review->created_at->format('M d, Y')); ?></p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-650 italic leading-relaxed">"<?php echo e($review->review); ?>"</p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12 text-gray-400 text-sm">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>No reviews has been posted yet.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Detailed Reputation Trust Score Breakdown -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-150">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 font-poppins flex items-center justify-between border-b border-gray-150 pb-4">
                        <span>🛡️ Score breakdown</span>
                        <span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-bold">
                            <?php echo e(number_format($overall, 0)); ?>/100
                        </span>
                    </h3>
                    
                    <?php
                        $verifyScore = $ts ? $ts->verification_score : ($profile->is_verified ? 100.00 : 0.00);
                        $paymentScore = $ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? 50.00 : 100.00);
                        $ratingScore = $ts ? $ts->student_rating_score : 70.00;
                        
                        // Hiring Score
                        if ($ts) {
                            $hiringScore = $ts->hiring_score;
                        } else {
                            $totalRespondedOffers = $profile->hiringOffers()->whereIn('status', ['accepted', 'rejected'])->count();
                            $hiringScore = $totalRespondedOffers === 0 ? 100.00 : (($profile->hiringOffers()->where('status', 'accepted')->count() / $totalRespondedOffers) * 100);
                        }
                        
                        // Task completion score
                        $totalTasks = $profile->tasks()->count();
                        if ($totalTasks === 0) {
                            $taskScoreVal = 100.00;
                        } else {
                            $completedTasksCount = $profile->tasks()->where(function($q) {
                                $q->where('status', 'completed')
                                  ->orWhereHas('applications.submission', function($subQ) {
                                      $subQ->where('status', 'accepted');
                                  });
                            })->count();
                            $taskScoreVal = ($completedTasksCount / $totalTasks) * 100;
                        }
                    ?>
                    
                    <div class="space-y-5">
                        <!-- Verification -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                                <span>Verification Status (25%)</span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format($verifyScore, 0)); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width: <?php echo e($verifyScore); ?>%"></div>
                            </div>
                        </div>
                        
                        <!-- Payment -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                                <span>Payment Reliability (25%)</span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format($paymentScore, 0)); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: <?php echo e($paymentScore); ?>%"></div>
                            </div>
                        </div>
                        
                        <!-- Student Rating -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                                <span>Student Rating Score (20%)</span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format($ratingScore, 0)); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full transition-all duration-500" style="width: <?php echo e($ratingScore); ?>%"></div>
                            </div>
                        </div>
                        
                        <!-- Hiring Success -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                                <span>Hiring Success (15%)</span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format($hiringScore, 0)); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: <?php echo e($hiringScore); ?>%"></div>
                            </div>
                        </div>
                        
                        <!-- Task Success -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                                <span>Task Completion Success (15%)</span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format($taskScoreVal, 0)); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-pink-500 h-2 rounded-full transition-all duration-500" style="width: <?php echo e($taskScoreVal); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/startup/public-profile.blade.php ENDPATH**/ ?>