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
        
        <!-- Pending Hiring Offers -->
        <?php if($hiringOffers && $hiringOffers->count() > 0): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <span class="animate-pulse inline-block w-3.5 h-3.5 bg-indigo-600 rounded-full"></span>
                    <span>Pending Hiring Offers (<?php echo e($hiringOffers->count()); ?>)</span>
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <?php $__currentLoopData = $hiringOffers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-xl shadow-xl border border-indigo-700 overflow-hidden">
                            <div class="p-6">
                                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                                    <div>
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500 bg-opacity-30 text-indigo-200 uppercase tracking-wider mb-2">
                                            <?php echo e(ucfirst($offer->offer_type)); ?> Offer
                                        </span>
                                        <h3 class="text-2xl font-bold text-white"><?php echo e($offer->title); ?></h3>
                                        <p class="text-indigo-300 text-sm mt-1">from <span class="font-semibold text-white"><?php echo e($offer->startup->company_name); ?></span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-indigo-300">Compensation</p>
                                        <p class="text-3xl font-extrabold text-indigo-400">₹<?php echo e(number_format($offer->compensation, 2)); ?><span class="text-sm font-normal text-indigo-200">/<?php echo e($offer->compensation_period === 'annual' ? 'yr' : 'mo'); ?></span></p>
                                    </div>
                                </div>
                                
                                <div class="border-t border-indigo-950 my-4 pt-4">
                                    <p class="text-indigo-200 text-sm leading-relaxed"><?php echo e($offer->description); ?></p>
                                </div>

                                <?php if($offer->contract_terms): ?>
                                    <div class="bg-black bg-opacity-30 rounded-lg p-4 mb-4 text-xs font-mono max-h-32 overflow-y-auto text-indigo-200 border border-indigo-950">
                                        <p class="font-semibold mb-1 text-white">Contract Terms:</p>
                                        <?php echo e($offer->contract_terms); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="flex flex-wrap items-center justify-between gap-4 mt-6 text-sm text-indigo-300">
                                    <div>
                                        <p><strong>Start Date:</strong> <?php echo e($offer->start_date->format('M d, Y')); ?></p>
                                        <?php if($offer->end_date): ?>
                                            <p><strong>End Date:</strong> <?php echo e($offer->end_date->format('M d, Y')); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <form method="POST" action="<?php echo e(route('student.offers.reject', $offer->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="bg-transparent hover:bg-white hover:bg-opacity-5 text-white font-semibold py-2 px-4 border border-white border-opacity-20 rounded-lg transition text-sm">
                                                Decline
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('student.offers.accept', $offer->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-lg shadow-md transition text-sm">
                                                Accept Offer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Dashboard Redesign Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Left Side: Scorecard & Stats -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Premium Motivation Reputation Card -->
                <?php if (isset($component)) { $__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.reputation-card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('reputation-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd)): ?>
<?php $attributes = $__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd; ?>
<?php unset($__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd)): ?>
<?php $component = $__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd; ?>
<?php unset($__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd); ?>
<?php endif; ?>

                <!-- IPRS Scorecard -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center justify-between">
                        <span>Professional Reputation Scorecard (IPRS)</span>
                        <span class="text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full font-semibold">IPRS Verified</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <!-- Left Sub-card: Overall Score -->
                        <div class="md:col-span-4 bg-gradient-to-br from-indigo-900 to-slate-900 rounded-xl p-5 text-white text-center shadow-md">
                            <p class="text-indigo-200 text-xs font-semibold uppercase tracking-wider">Reputation Score</p>
                            <p class="text-5xl font-black mt-3 text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-indigo-200 to-indigo-100"><?php echo e(number_format(optional($profile->reputationScore)->overall_score ?? 50.00, 0)); ?></p>
                            <p class="text-indigo-200 text-xs mt-1">out of 100</p>
                            <div class="mt-4 inline-block bg-white bg-opacity-10 text-white text-xs px-3 py-1 rounded-full">
                                <?php if((optional($profile->reputationScore)->overall_score ?? 50.00) >= 90): ?>
                                    🔥 Elite Category
                                <?php elseif((optional($profile->reputationScore)->overall_score ?? 50.00) >= 75): ?>
                                    ⭐ Professional
                                <?php else: ?>
                                    📈 Emerging Talent
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Right Sub-card: Component Metrics with dot alignment -->
                        <div class="md:col-span-8 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Trust Score</span>
                                <span class="border-b border-dotted border-gray-300 flex-1 mx-2 h-3"></span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format(optional($profile->reputationScore)->trust_score ?? 50.00, 0)); ?>%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Task Completion Rate</span>
                                <span class="border-b border-dotted border-gray-300 flex-1 mx-2 h-3"></span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format(optional($profile->reputationScore)->completion_rate ?? 100.00, 0)); ?>%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">On-Time Delivery</span>
                                <span class="border-b border-dotted border-gray-300 flex-1 mx-2 h-3"></span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format(optional($profile->reputationScore)->on_time_rate ?? 100.00, 0)); ?>%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Startup Satisfaction</span>
                                <span class="border-b border-dotted border-gray-300 flex-1 mx-2 h-3"></span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format(optional($profile->reputationScore)->satisfaction_rating ?? 5.00, 1)); ?>/5</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Communication Rating</span>
                                <span class="border-b border-dotted border-gray-300 flex-1 mx-2 h-3"></span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format(optional($profile->reputationScore)->communication_rating ?? 4.80, 1)); ?>/5</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Skill Verification rating</span>
                                <span class="border-b border-dotted border-gray-300 flex-1 mx-2 h-3"></span>
                                <span class="font-bold text-gray-900"><?php echo e(number_format(optional($profile->reputationScore)->skill_verification_rating ?? 0.00, 0)); ?>%</span>
                            </div>
                            
                            <!-- Interview Performance Metrics -->
                            <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-150 pt-2 mt-2">
                                <span class="text-indigo-600 font-bold uppercase tracking-wide text-xs">Interview Metrics</span>
                                <span class="border-b border-dashed border-gray-200 flex-1 mx-2 h-3"></span>
                                <span class="font-extrabold text-indigo-650 text-xs bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded">IPS: <?php echo e(number_format(optional($profile->reputationScore)->interview_performance_score ?? 100.00, 0)); ?>%</span>
                            </div>
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 mt-1">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Interviews Attended</span>
                                    <span class="font-bold text-gray-800"><?php echo e(optional($profile->reputationScore)->interviews_attended ?? 0); ?></span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Success Rate</span>
                                    <span class="font-bold text-gray-800"><?php echo e(number_format(optional($profile->reputationScore)->interview_success_rate ?? 100.00, 0)); ?>%</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Strong Outcomes</span>
                                    <span class="font-bold text-gray-800"><?php echo e(optional($profile->reputationScore)->strong_candidate_outcomes ?? 0); ?></span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">No Shows</span>
                                    <span class="font-bold text-red-650"><?php echo e(optional($profile->reputationScore)->no_shows ?? 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Public Portfolio Link -->
                <?php if($profile->portfolio && $profile->portfolio->custom_slug): ?>
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-bold text-indigo-900 flex items-center gap-2">
                                📂 Your Public Talent Profile
                            </h3>
                            <p class="text-xs text-indigo-600 mt-1 font-mono"><?php echo e(url('/talent/' . $profile->portfolio->custom_slug)); ?></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('talent.profile', $profile->portfolio->custom_slug)); ?>" target="_blank" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition">
                                View Portfolio →
                            </a>
                            <?php if (isset($component)) { $__componentOriginal158033e17ac548ba8d985f1073c1aeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal158033e17ac548ba8d985f1073c1aeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.share-profile-button','data' => ['url' => route('talent.profile', $profile->portfolio->custom_slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('share-profile-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('talent.profile', $profile->portfolio->custom_slug))]); ?>
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
                <?php endif; ?>

                <!-- Hiring Success Stats Grid & Wallet -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Projects Completed</span>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?php echo e($projectsCompleted); ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Intern Offers</span>
                        <p class="text-3xl font-black text-indigo-600 mt-2"><?php echo e($internshipOffersCount); ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Job Offers</span>
                        <p class="text-3xl font-black text-purple-600 mt-2"><?php echo e($jobOffersCount); ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 flex flex-col justify-between bg-gradient-to-br from-emerald-50 to-white">
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Total Earnings</span>
                        <p class="text-2xl font-black text-emerald-600 mt-2">₹<?php echo e(number_format($totalEarnings, 0)); ?></p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-md p-4 text-white flex flex-col justify-between col-span-2 md:col-span-1">
                        <div>
                            <span class="text-xs font-semibold text-green-100 uppercase tracking-wider">Wallet Balance</span>
                            <p class="text-xl font-bold mt-1">₹<?php echo e(number_format($profile->wallet_balance, 0)); ?></p>
                        </div>
                        <a href="<?php echo e(route('wallet.index')); ?>" class="text-[10px] text-white underline mt-2 block">View Transactions</a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Experience Ledger -->
            <div>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 h-full flex flex-col">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center justify-between">
                        <span>Experience Ledger</span>
                        <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded">Verified Records</span>
                    </h2>
                    
                    <div class="space-y-4 overflow-y-auto flex-1 max-h-[360px] pr-2">
                        <?php if($profile->portfolio && $profile->portfolio->items->count() > 0): ?>
                            <?php $__currentLoopData = $profile->portfolio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative pl-6 border-l-2 border-indigo-100 hover:border-indigo-500 transition py-1">
                                    <!-- Timeline Node Circle -->
                                    <div class="absolute -left-[6px] top-1.5 w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>
                                    
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-bold text-gray-900 text-sm"><?php echo e($item->startup_name); ?></h4>
                                        <span class="text-xs font-medium text-gray-500"><?php echo e($item->created_at->format('M Y')); ?></span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-0.5">
                                        <strong>Role:</strong> 
                                        <?php if(!empty($item->skills_demonstrated) && is_array($item->skills_demonstrated) && count($item->skills_demonstrated) > 0): ?>
                                            <?php echo e($item->skills_demonstrated[0]); ?> Developer
                                        <?php else: ?>
                                            PHP Developer
                                        <?php endif; ?>
                                    </p>
                                    <p class="text-xs text-gray-600 mt-0.5"><strong>Project:</strong> <?php echo e($item->project_title); ?></p>
                                    
                                    <div class="flex items-center justify-between mt-2 text-xs">
                                        <div class="flex items-center text-yellow-500 font-semibold">
                                            ⭐ <span class="ml-1 text-gray-700"><?php echo e(number_format($item->rating_received, 1)); ?>/5</span>
                                        </div>
                                        <?php $badge = $item->badgeLabel(); ?>
                                        <span class="text-[10px] font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-full flex items-center">
                                            <?php echo e($badge['emoji']); ?> <?php echo e($badge['label']); ?>

                                        </span>
                                    </div>

                                    <!-- Project Evidence Editing -->
                                    <div class="mt-3 pt-2 border-t border-gray-100" x-data="{ editing: false }">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <?php if($item->github_url): ?>
                                                <a href="<?php echo e($item->github_url); ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-md hover:bg-slate-200 transition">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                                    GitHub
                                                </a>
                                            <?php endif; ?>
                                            <?php if($item->demo_url): ?>
                                                <a href="<?php echo e($item->demo_url); ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-md hover:bg-slate-200 transition">
                                                    🌐 Demo
                                                </a>
                                            <?php endif; ?>
                                            <button @click="editing = !editing" class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md hover:bg-indigo-100 transition cursor-pointer">
                                                ✏️ <?php echo e(($item->github_url || $item->demo_url) ? 'Edit' : 'Add'); ?> Links
                                            </button>
                                        </div>

                                        <!-- Inline Edit Form -->
                                        <form x-show="editing" x-transition method="POST" action="<?php echo e(route('student.portfolio.evidence', $item->id)); ?>" class="mt-2 space-y-2">
                                            <?php echo csrf_field(); ?>
                                            <input type="url" name="github_url" value="<?php echo e($item->github_url); ?>" placeholder="GitHub URL" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:border-indigo-500 focus:outline-none">
                                            <input type="url" name="demo_url" value="<?php echo e($item->demo_url); ?>" placeholder="Live Demo URL" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:border-indigo-500 focus:outline-none">
                                            <button type="submit" class="text-xs font-bold bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition">Save</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-12 text-gray-500 my-auto">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">No verified experience records yet.</p>
                                <p class="text-xs text-gray-400 mt-1">Complete tasks and get rated to build your experience ledger.</p>
                            </div>
                        <?php endif; ?>
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
                                    <?php if($application->submission && $application->submission->status === 'accepted' && !in_array($application->task_id, $reviewedTaskIds)): ?>
                                        <button onclick="openReviewModal(<?php echo e($application->task_id); ?>, '<?php echo e(addslashes($application->task->startup->company_name)); ?>')" class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-md transition">
                                            ⭐ Rate Startup
                                        </button>
                                    <?php endif; ?>
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

    <!-- Startup Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
        <div class="bg-white/95 backdrop-blur-lg border border-purple-100 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative">
            <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-650 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h3 class="text-2xl font-bold text-gray-900 mb-2 font-poppins">Rate Startup 🌟</h3>
            <p class="text-sm text-gray-600 mb-6">Review your experience working with <span id="review-startup-name" class="font-bold text-indigo-600"></span>.</p>
            
            <form id="review-form" method="POST" action="" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Rating</label>
                    <div class="flex items-center space-x-1" id="star-selector">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <button type="button" onclick="setStarRating(<?php echo e($i); ?>)" class="text-3xl text-gray-300 hover:text-amber-400 transition" id="star-btn-<?php echo e($i); ?>">
                                ★
                            </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="review-rating-val" required value="">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Review Comment</label>
                    <textarea name="review" rows="4" required minlength="5" maxlength="1000"
                              class="w-full px-4 py-3 border border-purple-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm transition"
                              placeholder="Describe your mentorship experience, payment reliability, communication quality, etc..."></textarea>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold px-6 py-3 rounded-xl hover:shadow-lg transition">
                        Submit Review
                    </button>
                    <button type="button" onclick="closeReviewModal()" class="bg-gray-100 text-gray-705 font-bold px-6 py-3 rounded-xl transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReviewModal(taskId, startupName) {
            document.getElementById('review-startup-name').textContent = startupName;
            document.getElementById('review-form').action = `/student/tasks/${taskId}/review`;
            document.getElementById('review-rating-val').value = '';
            
            // reset stars
            for (let i = 1; i <= 5; i++) {
                document.getElementById(`star-btn-${i}`).classList.remove('text-amber-400');
                document.getElementById(`star-btn-${i}`).classList.add('text-gray-300');
            }
            
            const modal = document.getElementById('review-modal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 50);
        }

        function closeReviewModal() {
            const modal = document.getElementById('review-modal');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function setStarRating(rating) {
            document.getElementById('review-rating-val').value = rating;
            for (let i = 1; i <= 5; i++) {
                const btn = document.getElementById(`star-btn-${i}`);
                if (i <= rating) {
                    btn.classList.add('text-amber-400');
                    btn.classList.remove('text-gray-300');
                } else {
                    btn.classList.remove('text-amber-400');
                    btn.classList.add('text-gray-300');
                }
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/student/dashboard.blade.php ENDPATH**/ ?>