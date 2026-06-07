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
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Flash Messages -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Success</p>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Error</p>
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Student Bio & IPRS Rating -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Base Card -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl text-white">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-2xl font-bold uppercase shadow-lg shadow-indigo-500/30">
                            <?php echo e(substr($profile->user->name, 0, 2)); ?>

                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight"><?php echo e($profile->user->name); ?></h1>
                            <?php if($profile->is_verified): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mt-1">
                                    🎓 Verified Academic Profile
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400 mt-1">
                                    Declared Profile
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed mb-6"><?php echo e($profile->bio ?? 'No bio provided.'); ?></p>

                    <div class="border-t border-slate-800 pt-6">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Academic Status</h3>
                        <p class="text-sm text-slate-300 font-semibold"><?php echo e($profile->college_name ?? 'N/A'); ?></p>
                        <p class="text-xs text-slate-500 mt-1"><?php echo e($profile->college_email ?? 'N/A'); ?></p>
                    </div>
                </div>

                <!-- IPRS Score Card -->
                <?php
                    $score = $profile->reputationScore;
                    $overall = $score ? $score->overall_score : 50.00;
                ?>
                <div class="bg-gradient-to-br from-indigo-950 to-slate-900 border border-indigo-500/20 rounded-2xl p-6 shadow-xl text-white">
                    <div class="text-center mb-6">
                        <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider">Reputation Score (IPRS)</h3>
                        <div class="relative flex items-center justify-center mt-4">
                            <div class="text-5xl font-black text-white tracking-tight"><?php echo e(round($overall)); ?><span class="text-indigo-400 text-2xl font-bold">/100</span></div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Verified Professional Trust Rank</p>
                    </div>

                    <div class="space-y-4 border-t border-slate-800/60 pt-6">
                        <!-- Trust Score -->
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-slate-400">Trust Score</span>
                                <span class="text-white font-semibold"><?php echo e($score ? round($score->trust_score) : 50); ?>%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: <?php echo e($score ? $score->trust_score : 50); ?>%"></div>
                            </div>
                        </div>

                        <!-- Completion Rate -->
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-slate-400">Task Completion Rate</span>
                                <span class="text-white font-semibold"><?php echo e($score ? round($score->completion_rate) : 100); ?>%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?php echo e($score ? $score->completion_rate : 100); ?>%"></div>
                            </div>
                        </div>

                        <!-- On-Time Rate -->
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-slate-400">On-Time Delivery</span>
                                <span class="text-white font-semibold"><?php echo e($score ? round($score->on_time_rate) : 100); ?>%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: <?php echo e($score ? $score->on_time_rate : 100); ?>%"></div>
                            </div>
                        </div>

                        <!-- Satisfaction Rating -->
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-slate-400">Startup Satisfaction</span>
                                <span class="text-white font-semibold"><?php echo e($score ? number_format($score->satisfaction_rating, 1) : '5.0'); ?>/5.0</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-400 h-1.5 rounded-full" style="width: <?php echo e($score ? ($score->satisfaction_rating * 20) : 100); ?>%"></div>
                            </div>
                        </div>

                        <!-- Interview Performance Score (IPS) -->
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-slate-400">Interview Performance (IPS)</span>
                                <span class="text-indigo-400 font-semibold"><?php echo e($score ? number_format($score->interview_performance_score, 0) : '100'); ?>%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: <?php echo e($score ? $score->interview_performance_score : 100); ?>%"></div>
                            </div>
                        </div>

                        <!-- Mini Interview stats grid -->
                        <div class="grid grid-cols-2 gap-2 text-[11px] bg-slate-900 border border-slate-800/80 rounded-xl p-3 text-slate-400">
                            <div>
                                <span class="block text-slate-500 font-medium">Attended</span>
                                <span class="font-bold text-white"><?php echo e($score ? $score->interviews_attended : 0); ?></span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium">Success Rate</span>
                                <span class="font-bold text-white"><?php echo e($score ? number_format($score->interview_success_rate, 0) : '100'); ?>%</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium">Strong Outcomes</span>
                                <span class="font-bold text-white"><?php echo e($score ? $score->strong_candidate_outcomes : 0); ?></span>
                            </div>
                            <div>
                                <span class="block text-slate-500 font-medium">No Shows</span>
                                <span class="font-bold text-rose-450"><?php echo e($score ? $score->no_shows : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- B2B Direct Action CTAs for Startup Visitors -->
                <?php if(auth()->check() && auth()->user()->isStartup()): ?>
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl text-white space-y-4">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Acquisition pipeline</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Directly engage this student using verified work history and bypass standard interviews.</p>
                        
                        <button onclick="toggleModal('internship-modal')" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-500/20 text-sm transition duration-150">
                            💼 Offer Internship
                        </button>
                        <button onclick="toggleModal('job-modal')" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 px-4 rounded-xl border border-slate-700 text-sm transition duration-150">
                            🚀 Offer Job
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Skills Graph & Verified Work Ledger -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Skills Card -->
                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                        <span class="mr-2">⚡</span> Verified Skill Badges
                    </h2>
                    
                    <?php
                        $verifiedSkills = $profile->skillVerifications->pluck('skill_id')->toArray();
                        $skillScores = $profile->skillVerifications->pluck('score', 'skill_id')->toArray();
                    ?>

                    <div class="flex flex-wrap gap-3">
                        <?php $__currentLoopData = $profile->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(in_array($skill->id, $verifiedSkills)): ?>
                                <?php $skillScore = $skillScores[$skill->id] ?? null; ?>
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-50 border border-indigo-200 text-indigo-700 shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
                                    <?php echo e($skill->name); ?> 
                                    <?php if($skillScore): ?>
                                        <span class="ml-1.5 text-indigo-900 bg-indigo-200/50 px-1.5 py-0.5 rounded-md text-[10px]"><?php echo e($skillScore); ?>/100</span>
                                    <?php else: ?>
                                        <span class="ml-1.5 text-indigo-900 bg-indigo-200/50 px-1.5 py-0.5 rounded-md text-[10px]">Verified</span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200/60">
                                    <?php echo e($skill->name); ?>

                                </span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                             <!-- Experience Ledger -->
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center">
                            <span class="mr-2">📂</span> Experience Ledger
                        </h2>
                        <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full">
                            <?php echo e($profile->portfolio && $profile->portfolio->items ? $profile->portfolio->items->count() : 0); ?> Verified Experience Records
                        </span>
                    </div>

                    <?php if($profile->portfolio && $profile->portfolio->items && $profile->portfolio->items->count() > 0): ?>
                        <div class="grid grid-cols-1 gap-6">
                            <?php $__currentLoopData = $profile->portfolio->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-150 relative overflow-hidden">
                                    <!-- Decorative Edge -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600"></div>

                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-black text-slate-900 tracking-tight"><?php echo e($item->startup_name); ?></h3>
                                            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Verified Experience Record</p>
                                        </div>
                                        
                                        <!-- Rating -->
                                        <?php if($item->rating_received): ?>
                                            <div class="flex items-center space-x-1 bg-amber-50 border border-amber-200/60 text-amber-700 px-2.5 py-1 rounded-xl text-xs font-bold">
                                                <span>⭐</span>
                                                <span><?php echo e(number_format($item->rating_received, 1)); ?>/5</span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-xl font-bold uppercase tracking-wider">Verified Task</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Ledger Details Grid -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 text-sm">
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-slate-500 font-medium sm:w-24">Role:</span>
                                            <span class="font-semibold text-slate-800">
                                                <?php if(!empty($item->skills_demonstrated) && is_array($item->skills_demonstrated) && count($item->skills_demonstrated) > 0): ?>
                                                    <?php echo e($item->skills_demonstrated[0]); ?> Developer
                                                <?php else: ?>
                                                    PHP Developer
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-slate-500 font-medium sm:w-24">Verified By:</span>
                                            <span class="font-semibold text-indigo-600 flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6.267 3.585a.75.75 0 011.05 0l6 6a.75.75 0 010 1.06l-6 6a.75.75 0 11-1.06-1.06L11.69 10 6.267 4.645a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                                                </svg>
                                                Startup Founder
                                            </span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-slate-500 font-medium sm:w-24">Project:</span>
                                            <span class="font-semibold text-slate-800"><?php echo e($item->project_title); ?></span>
                                        </div>
                                        <div class="flex justify-between sm:justify-start items-center">
                                            <span class="text-slate-500 font-medium sm:w-24">Date:</span>
                                            <span class="font-semibold text-slate-800"><?php echo e($item->created_at->format('M Y')); ?></span>
                                        </div>
                                    </div>

                                    <p class="text-slate-650 text-sm leading-relaxed mb-4 font-normal"><?php echo e($item->auto_summary); ?></p>

                                    <!-- Tags & Cert Link -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-slate-100 pt-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            <?php $__currentLoopData = $item->skills_demonstrated ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skillName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2 py-0.5 rounded-md border border-slate-200/40"><?php echo e($skillName); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>

                                        <?php if($item->certificate_number): ?>
                                            <a href="<?php echo e(route('certificates.verify', $item->certificate_number)); ?>" target="_blank" class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-500 font-bold transition">
                                                📜 View Verified Certificate →
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-12 text-center text-slate-500">
                            <p class="text-sm font-semibold">This student hasn't completed any microtasks yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Once tasks are accepted, they will automatically appear here as verified experience records.</p>
                        </div>
                    <?php endif; ?>
                </div>               </div>
            </div>
        </div>
    </div>

    <!-- 💼 Internship Offer Modal -->
    <?php if(auth()->check() && auth()->user()->isStartup()): ?>
        <div id="internship-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 transition-opacity" aria-hidden="true" onclick="toggleModal('internship-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-middle bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 text-white">💼 Extend Internship Offer</h2>
                    
                    <form action="<?php echo e(route('startup.offers.store')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="student_profile_id" value="<?php echo e($profile->id); ?>">
                        <input type="hidden" name="offer_type" value="internship">
                        <input type="hidden" name="compensation_period" value="monthly">

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Frontend Development Intern" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Role Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job duties and goals..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Monthly Stipend (₹)</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 8000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">End Date (Optional)</label>
                                <input type="date" name="end_date" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contract Terms & Perks</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Certificate, Flexible Hours, Work From Home" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('internship-modal')" class="bg-slate-800 hover:bg-slate-750 border border-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition duration-150">Send Offer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 🚀 Job Offer Modal -->
        <div id="job-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-950/60 transition-opacity" aria-hidden="true" onclick="toggleModal('job-modal')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-middle bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 text-white">
                    <h2 class="text-lg font-bold mb-4 text-white">🚀 Extend Full-Time Job Offer</h2>
                    
                    <form action="<?php echo e(route('startup.offers.store')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="student_profile_id" value="<?php echo e($profile->id); ?>">
                        <input type="hidden" name="offer_type" value="job">

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Offer Title</label>
                            <input type="text" name="title" required placeholder="e.g. Junior Backend Laravel Developer" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Job Description</label>
                            <textarea name="description" required rows="3" placeholder="Outline job responsibilities..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Compensation Amount</label>
                                <input type="number" name="compensation" required min="0" placeholder="e.g. 600000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Period</label>
                                <select name="compensation_period" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                                    <option value="annual">Annual CTC</option>
                                    <option value="monthly">Monthly Salary</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Start Date</label>
                                <input type="date" name="start_date" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contract Terms & Benefits</label>
                            <textarea name="contract_terms" rows="2" placeholder="e.g. Health Insurance, Annual Leave, Bonus Structure" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="toggleModal('job-modal')" class="bg-slate-800 hover:bg-slate-755 border border-slate-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition duration-150">Send Offer</button>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/student/public-profile.blade.php ENDPATH**/ ?>