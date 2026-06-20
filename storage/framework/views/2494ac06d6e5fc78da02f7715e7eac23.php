<div class="space-y-6">
    <!-- Header with AI Ranking Toggle -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-150">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 font-poppins">Applications & Submissions (<?php echo e($applications->count()); ?>)</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and rank candidates in your hiring funnel</p>
        </div>
        <?php if($applications->count() > 0): ?>
            <div class="flex items-center space-x-3 bg-indigo-50 border border-indigo-100 px-4 py-2.5 rounded-xl self-start sm:self-auto shadow-sm">
                <span class="text-xs font-bold text-indigo-700 tracking-wider uppercase font-poppins flex items-center gap-1.5">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                    AI Candidate Ranking
                </span>
                <button type="button" 
                        id="ai-ranking-toggle" 
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" 
                        role="switch" 
                        aria-checked="false" 
                        onclick="toggleAIRanking()">
                    <span id="ai-ranking-toggle-bg" class="pointer-events-none absolute inset-0 rounded-full bg-gray-300 transition-colors duration-200 ease-in-out"></span>
                    <span id="ai-ranking-toggle-handle" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0"></span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php if($applications->count() === 0): ?>
        <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-500 mt-4 font-semibold">No applications yet. Students will see your task and can apply.</p>
        </div>
    <?php else: ?>
        <!-- Applications Container -->
        <div id="applications-container" class="space-y-6">
            <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $student = $application->student;
                    $iprs = $student->reputationScore?->overall_score ?? 50.00;
                    $reliability = $student->reliability_score ?? 0;
                    $matchScore = $application->match_score ?? 0;
                    $rankingDetails = $application->ranking_details ?? [];
                ?>
                <div class="application-card border border-gray-200 rounded-xl p-6 transition-all duration-350 shadow-sm bg-white hover:shadow-md" 
                     data-match-score="<?php echo e($matchScore); ?>" 
                     data-id="<?php echo e($application->id); ?>">
                    
                    <!-- Top Candidate Header (AI-only) -->
                    <div class="top-candidate-header hidden mb-4 bg-gradient-to-r from-amber-500 to-yellow-600 text-white px-4 py-2 rounded-lg text-xs font-bold tracking-wider flex items-center justify-between shadow-sm">
                        <span class="flex items-center gap-1.5">🥇 AI RANK #1 MATCH (TOP RECOMMENDED CANDIDATE)</span>
                        <span class="bg-white text-amber-700 px-2.5 py-0.5 rounded-full font-black text-[10px]"><?php echo e($matchScore); ?>% Match</span>
                    </div>

                    <!-- Main Candidate Row -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h3 class="font-bold text-lg text-gray-850 font-poppins"><?php echo e($student->user->name); ?></h3>
                                
                                <!-- Match Score Badge (AI-only) -->
                                <span class="ai-info hidden bg-indigo-100 text-indigo-800 border border-indigo-200 px-2.5 py-0.5 rounded-full text-xs font-extrabold font-poppins shadow-sm">
                                    Match Score: <?php echo e($matchScore); ?>%
                                </span>
                                
                                <!-- Recommended Candidate Badge (AI-only) -->
                                <?php if($matchScore >= 85): ?>
                                    <span class="ai-info hidden bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-0.5 rounded-full text-xs font-extrabold font-poppins flex items-center gap-1 shadow-sm">
                                        🥇 Recommended Candidate
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs font-medium text-gray-450 mt-0.5"><?php echo e($student->user->email); ?></p>

                            <!-- Reputation/Indicators Row -->
                            <div class="flex flex-wrap items-center gap-3 mt-3">
                                <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.952 2.878c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.064 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.285-3.957z"/>
                                    </svg>
                                    <?php echo e(number_format($iprs, 1)); ?> IPRS
                                </span>
                                <span class="inline-flex items-center gap-1 <?php echo e($reliability >= 0.7 ? 'bg-green-50 text-green-700 border-green-200' : ($reliability >= 0.4 ? 'bg-yellow-50 text-yellow-700 border-yellow-250' : 'bg-gray-50 text-gray-600 border-gray-200')); ?> text-xs font-bold px-3 py-1 rounded-full border">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <?php echo e(number_format($reliability * 100, 0)); ?>% reliability
                                </span>
                                <?php if($student->is_verified): ?>
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-150">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified Talent
                                    </span>
                                <?php endif; ?>
                                <a href="<?php echo e(route('students.public-profile', $student->id)); ?>" target="_blank"
                                   class="text-xs text-indigo-600 hover:text-indigo-800 underline font-semibold flex items-center gap-0.5">
                                    View Full Profile
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Status Label -->
                        <span class="px-3.5 py-1 text-xs font-bold rounded-full self-start md:self-auto font-poppins shadow-sm
                            <?php if($application->status === 'approved' || $application->status === 'internship_accepted' || $application->status === 'hired'): ?> 
                                bg-green-100 text-green-800 border border-green-200
                            <?php elseif($application->status === 'rejected'): ?> 
                                bg-red-100 text-red-800 border border-red-200
                            <?php elseif($application->status === 'shortlisted' || $application->status === 'interview'): ?> 
                                bg-blue-100 text-blue-800 border border-blue-250
                            <?php elseif($application->status === 'internship_offered'): ?>
                                bg-purple-100 text-purple-800 border border-purple-200
                            <?php else: ?> 
                                bg-yellow-100 text-yellow-800 border border-yellow-250
                            <?php endif; ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $application->status))); ?>

                        </span>
                    </div>

                    <!-- AI Explanation / "Why Recommended" (AI-only) -->
                    <?php if(isset($rankingDetails['explanations']) && count($rankingDetails['explanations']) > 0): ?>
                        <div class="ai-info hidden mt-4 p-4 bg-indigo-50/40 border border-indigo-100 rounded-xl">
                            <h4 class="text-xs font-bold text-indigo-800 tracking-wider uppercase mb-2 font-poppins flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-650" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Why Recommended
                            </h4>
                            <ul class="space-y-1.5 text-xs text-indigo-900 font-medium">
                                <?php $__currentLoopData = $rankingDetails['explanations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-start gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span>
                                        <span><?php echo e($expl); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- AI "Best Evidence" Section (AI-only) -->
                    <?php if(isset($rankingDetails['best_evidence']) && $rankingDetails['best_evidence'] !== null): ?>
                        <div class="ai-info hidden mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5 font-poppins flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Best Evidence Project
                            </h4>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-sm font-bold text-slate-800 font-poppins"><?php echo e($rankingDetails['best_evidence']['project_title']); ?></h5>
                                    <?php if(!empty($rankingDetails['best_evidence']['skills_demonstrated'])): ?>
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            <?php $__currentLoopData = $rankingDetails['best_evidence']['skills_demonstrated']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold font-poppins"><?php echo e($sk); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if($rankingDetails['best_evidence']['rating_received']): ?>
                                    <div class="text-right whitespace-nowrap bg-amber-50 border border-amber-100 px-2.5 py-1.5 rounded-lg shadow-sm">
                                        <span class="text-amber-600 font-black text-sm font-poppins">⭐ <?php echo e($rankingDetails['best_evidence']['rating_received']); ?>/5</span>
                                        <p class="text-[8px] text-amber-550 font-bold uppercase tracking-wider mt-0.5">Startup Rated</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- AI Insights Drawer (AI-only) -->
                    <?php if(isset($rankingDetails['insights'])): ?>
                        <div class="ai-info hidden mt-4 border border-indigo-100 rounded-xl overflow-hidden shadow-sm">
                            <button type="button" 
                                    class="w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 transition flex items-center justify-between text-xs font-bold text-indigo-900 uppercase tracking-wider font-poppins" 
                                    onclick="toggleInsightsDrawer('insights-drawer-<?php echo e($application->id); ?>')">
                                <span class="flex items-center gap-1.5">
                                    🔍 Candidate Insights (Strengths, Risks & Interview Questions)
                                </span>
                                <span class="arrow transition-transform duration-200 select-none">▼</span>
                            </button>
                            <div id="insights-drawer-<?php echo e($application->id); ?>" class="hidden p-5 bg-white border-t border-indigo-100 space-y-4">
                                
                                <!-- Strengths -->
                                <div>
                                    <h5 class="text-xs font-bold text-green-700 tracking-wide uppercase mb-1.5 font-poppins flex items-center gap-1">💪 Strengths</h5>
                                    <ul class="list-disc pl-5 text-xs text-gray-700 space-y-1 font-medium">
                                        <?php $__currentLoopData = $rankingDetails['insights']['strengths']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($st); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>

                                <!-- Risks -->
                                <?php if(count($rankingDetails['insights']['risks']) > 0): ?>
                                    <div>
                                        <h5 class="text-xs font-bold text-red-700 tracking-wide uppercase mb-1.5 font-poppins flex items-center gap-1">⚠️ Potential Risks</h5>
                                        <ul class="list-disc pl-5 text-xs text-gray-750 space-y-1 font-medium">
                                            <?php $__currentLoopData = $rankingDetails['insights']['risks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($rk); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <!-- Interview Questions -->
                                <?php if(count($rankingDetails['insights']['interview_questions']) > 0): ?>
                                    <div class="pt-3 border-t border-gray-150">
                                        <h5 class="text-xs font-bold text-indigo-700 tracking-wide uppercase mb-1.5 font-poppins flex items-center gap-1">💬 Suggested Interview Questions</h5>
                                        <ul class="list-decimal pl-5 text-xs text-gray-750 space-y-1.5">
                                            <?php $__currentLoopData = $rankingDetails['insights']['interview_questions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="italic font-medium text-gray-800">"<?php echo e($qs); ?>"</li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Cover Letter (Standard Section) -->
                    <?php if($application->cover_letter): ?>
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-150">
                            <p class="text-sm text-gray-700 font-medium font-poppins mb-1 text-xs text-gray-400 uppercase tracking-wider">Cover Letter</p>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">"<?php echo e($application->cover_letter); ?>"</p>
                        </div>
                    <?php endif; ?>

                    <!-- Talent Pipeline Status Actions (Standard Section) -->
                    <div class="bg-slate-50 border border-gray-200 rounded-xl p-4 mt-4 shadow-sm">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 font-poppins">Hiring Funnel Stage</h4>
                        
                        <!-- Visual Stepper -->
                        <div class="flex items-center justify-between mb-4 overflow-x-auto py-2">
                            <?php
                                $stages = [
                                    'applied' => 'Applied',
                                    'shortlisted' => 'Shortlisted',
                                    'approved' => 'Task Started',
                                    'task_completed' => 'Task Done',
                                    'interview' => 'Interview',
                                    'internship_offered' => 'Offered',
                                    'hired' => 'Hired'
                                ];
                                $currentStatus = $application->status;
                                if ($currentStatus === 'approved' && $application->submission && $application->submission->status === 'accepted') {
                                    $currentStatus = 'task_completed';
                                }
                                if ($currentStatus === 'internship_accepted') {
                                    $currentStatus = 'internship_offered';
                                }
                                $stagesKeys = array_keys($stages);
                                $currentIndex = array_search($currentStatus, $stagesKeys);
                                if ($currentIndex === false) {
                                    $currentIndex = 0;
                                }
                            ?>
                            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $keyIndex = array_search($key, $stagesKeys);
                                    $isActive = $key === $currentStatus;
                                    $isPassed = $keyIndex < $currentIndex;
                                    
                                    // Determine the status to submit
                                    $targetStatus = $key;
                                    if ($key === 'task_completed') {
                                        $targetStatus = 'approved';
                                    }
                                ?>
                                <div class="flex items-center flex-1 last:flex-none">
                                    <button type="button" 
                                            onclick="submitStatusChange('<?php echo e($application->id); ?>', '<?php echo e($targetStatus); ?>')"
                                            class="flex flex-col items-center focus:outline-none cursor-pointer group"
                                            title="Move to <?php echo e($label); ?>">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-200 group-hover:scale-110 group-hover:ring-2 group-hover:ring-indigo-450 group-hover:ring-offset-1 <?php echo e($isActive ? 'bg-indigo-600 text-white' : ($isPassed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600')); ?>">
                                            <?php if($isPassed): ?> ✓ <?php else: ?> <?php echo e($keyIndex + 1); ?> <?php endif; ?>
                                        </div>
                                        <span class="text-[10px] font-semibold mt-1 whitespace-nowrap transition-colors duration-200 group-hover:text-indigo-650 <?php echo e($isActive ? 'text-indigo-650 font-bold' : 'text-gray-550'); ?>"><?php echo e($label); ?></span>
                                    </button>
                                    <?php if(!$loop->last): ?>
                                        <div class="h-0.5 flex-1 mx-2 <?php echo e($keyIndex < $currentIndex ? 'bg-green-500' : 'bg-gray-200'); ?>"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Actions Dropdown -->
                        <form method="POST" action="<?php echo e(route('startup.applications.update-status', $application->id)); ?>" class="flex items-center space-x-3">
                            <?php echo csrf_field(); ?>
                            <label class="text-xs font-bold text-gray-700 font-poppins">Move Candidate To:</label>
                            <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg text-xs py-1.5 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 font-medium">
                                <option value="applied" <?php echo e($application->status === 'applied' ? 'selected' : ''); ?>>1. Task Applicant (Applied)</option>
                                <option value="shortlisted" <?php echo e($application->status === 'shortlisted' ? 'selected' : ''); ?>>2. Shortlisted</option>
                                <option value="approved" <?php echo e($application->status === 'approved' ? 'selected' : ''); ?>>3. Task Approved (Assign Task)</option>
                                <option value="interview" <?php echo e($application->status === 'interview' ? 'selected' : ''); ?>>4. Interviewing</option>
                                <option value="internship_offered" <?php echo e($application->status === 'internship_offered' ? 'selected' : ''); ?>>5. Internship Offered</option>
                                <option value="internship_accepted" <?php echo e($application->status === 'internship_accepted' ? 'selected' : ''); ?>>6. Internship Accepted</option>
                                <option value="hired" <?php echo e($application->status === 'hired' ? 'selected' : ''); ?>>7. Employee Hired</option>
                                <option value="rejected" <?php echo e($application->status === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                            </select>
                        </form>
                    </div>
                    
                    <!-- Submission Section (Standard Section) -->
                    <?php if($application->submission): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900 font-poppins">Work Submission</h4>
                                <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($application->submission->status === 'accepted' ? 'bg-green-100 text-green-800' : ($application->submission->status === 'rejected' ? 'bg-red-100 text-red-800' : ($application->submission->status === 'revision_requested' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'))); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $application->submission->status))); ?>

                                </span>
                            </div>
                            
                            <div class="p-4 bg-gray-50 border border-gray-150 rounded-xl mb-3">
                                <div class="mb-3">
                                    <h5 class="font-bold text-gray-900 text-xs uppercase tracking-wider mb-2 font-poppins">Submission Description</h5>
                                    <p class="text-gray-700 whitespace-pre-wrap text-sm leading-relaxed"><?php echo e($application->submission->content); ?></p>
                                </div>
                                
                                <?php if($application->submission->files && is_array($application->submission->files) && count($application->submission->files) > 0): ?>
                                    <?php
                                        $hasValidFiles = false;
                                        foreach ($application->submission->files as $file) {
                                            if (is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])) {
                                                $hasValidFiles = true;
                                                break;
                                            }
                                        }
                                    ?>
                                    
                                    <?php if($hasValidFiles): ?>
                                        <div class="mt-4 pt-4 border-t border-gray-250">
                                            <h5 class="font-bold text-gray-900 text-xs uppercase tracking-wider mb-2 font-poppins">Attached Files</h5>
                                            <div class="space-y-2">
                                                <?php $__currentLoopData = $application->submission->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])): ?>
                                                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                                            <div class="flex items-center space-x-3 flex-1">
                                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                </svg>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center space-x-2">
                                                                        <p class="text-sm font-semibold text-gray-900"><?php echo e($file['name']); ?></p>
                                                                        <?php if(isset($file['version'])): ?>
                                                                            <?php if($file['version'] === 'original'): ?>
                                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Original</span>
                                                                            <?php elseif($file['version'] === 'revision'): ?>
                                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-850">Revised</span>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                                        <?php echo e(isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : ''); ?>

                                                                        <?php if(isset($file['uploaded_at'])): ?>
                                                                            • Uploaded: <?php echo e(\Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y H:i')); ?>

                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <a href="<?php echo e(route('startup.submissions.review', $application->submission->id)); ?>" class="text-indigo-650 hover:text-indigo-850 text-xs font-semibold ml-3 px-3 py-1.5 bg-indigo-50 rounded-lg border border-indigo-200 shadow-sm transition">
                                                                👁️ View Preview
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($application->submission->feedback): ?>
                                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl mb-3 shadow-sm">
                                    <p class="text-sm text-yellow-800"><strong>Your Feedback:</strong> <?php echo e($application->submission->feedback); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Submission Actions -->
                            <?php if(in_array($application->submission->status, ['pending', 'submitted', 'revision_requested'])): ?>
                                <div class="flex gap-2">
                                    <form method="POST" action="<?php echo e(route('startup.submissions.accept', $application->submission->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-semibold shadow-sm transition">✓ Accept Work</button>
                                    </form>
                                    
                                    <button onclick="document.getElementById('revision-form-<?php echo e($application->submission->id); ?>').classList.toggle('hidden')" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-semibold shadow-sm transition">
                                        ↻ Request Revision
                                    </button>
                                    <button onclick="document.getElementById('reject-form-<?php echo e($application->submission->id); ?>').classList.toggle('hidden')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-semibold shadow-sm transition">
                                        ✗ Reject Work
                                    </button>
                                </div>
                                
                                <!-- Revision Request Form -->
                                <div id="revision-form-<?php echo e($application->submission->id); ?>" class="hidden mt-3 p-4 bg-yellow-50/50 border border-yellow-200 rounded-xl">
                                    <form method="POST" action="<?php echo e(route('startup.submissions.revision', $application->submission->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <textarea name="feedback" rows="3" required class="w-full border-gray-300 rounded-lg mb-3 shadow-sm" placeholder="Explain what needs to be revised..."></textarea>
                                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-semibold shadow-sm transition">Send Revision Request</button>
                                    </form>
                                </div>
                                
                                <!-- Reject Form -->
                                <div id="reject-form-<?php echo e($application->submission->id); ?>" class="hidden mt-3 p-4 bg-red-50/50 border border-red-200 rounded-xl">
                                    <form method="POST" action="<?php echo e(route('startup.submissions.reject', $application->submission->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <textarea name="feedback" rows="3" required class="w-full border-gray-300 rounded-lg mb-3 shadow-sm" placeholder="Explain why the work is rejected..."></textarea>
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-semibold shadow-sm transition">Confirm Rejection</button>
                                    </form>
                                </div>
                            <?php elseif($application->submission->status === 'accepted'): ?>
                                <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-xl shadow-sm">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-3 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-bold text-green-800 font-poppins">✓ Task Completed Successfully</p>
                                            <p class="text-xs text-green-700 mt-1">Student's IPRS reputation score has been updated.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif($application->status === 'approved'): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-400 italic">Waiting for student to submit their work...</p>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <script>
            function toggleAIRanking() {
                const toggle = document.getElementById('ai-ranking-toggle');
                const bg = document.getElementById('ai-ranking-toggle-bg');
                const handle = document.getElementById('ai-ranking-toggle-handle');
                const container = document.getElementById('applications-container');
                const cards = Array.from(container.getElementsByClassName('application-card'));
                
                const isActive = toggle.getAttribute('aria-checked') === 'true';
                const newState = !isActive;
                toggle.setAttribute('aria-checked', newState ? 'true' : 'false');
                
                if (newState) {
                    // AI Ranking Enabled
                    bg.classList.remove('bg-gray-300');
                    bg.classList.add('bg-indigo-600');
                    handle.classList.remove('translate-x-0');
                    handle.classList.add('translate-x-5');
                    
                    document.querySelectorAll('.ai-info').forEach(el => {
                        el.classList.remove('hidden');
                        el.style.opacity = 0;
                        setTimeout(() => {
                            el.style.transition = 'opacity 0.35s ease-in-out';
                            el.style.opacity = 1;
                        }, 50);
                    });
                    
                    // Sort cards by match score descending
                    cards.sort((a, b) => {
                        return parseInt(b.getAttribute('data-match-score')) - parseInt(a.getAttribute('data-match-score'));
                    });
                    
                    cards.forEach((card, index) => {
                        container.appendChild(card);
                        if (index === 0) {
                            card.classList.add('border-amber-400', 'bg-gradient-to-br', 'from-amber-50/40', 'to-white');
                            card.querySelector('.top-candidate-header').classList.remove('hidden');
                        } else {
                            card.classList.remove('border-amber-400', 'bg-gradient-to-br', 'from-amber-50/40', 'to-white');
                            card.querySelector('.top-candidate-header').classList.add('hidden');
                        }
                    });
                } else {
                    // Normal View
                    bg.classList.remove('bg-indigo-600');
                    bg.classList.add('bg-gray-300');
                    handle.classList.remove('translate-x-5');
                    handle.classList.add('translate-x-0');
                    
                    document.querySelectorAll('.ai-info').forEach(el => {
                        el.classList.add('hidden');
                        el.style.opacity = 0;
                    });
                    
                    // Sort cards by original ID ascending
                    cards.sort((a, b) => {
                        return parseInt(a.getAttribute('data-id')) - parseInt(b.getAttribute('data-id'));
                    });
                    
                    cards.forEach((card) => {
                        container.appendChild(card);
                        card.classList.remove('border-amber-400', 'bg-gradient-to-br', 'from-amber-50/40', 'to-white');
                        card.querySelector('.top-candidate-header').classList.add('hidden');
                    });
                }
            }

            function toggleInsightsDrawer(id) {
                const drawer = document.getElementById(id);
                const button = drawer.previousElementSibling;
                const arrow = button.querySelector('.arrow');
                
                drawer.classList.toggle('hidden');
                
                if (drawer.classList.contains('hidden')) {
                    arrow.style.transform = 'rotate(0deg)';
                    button.classList.remove('bg-indigo-100');
                } else {
                    arrow.style.transform = 'rotate(180deg)';
                    button.classList.add('bg-indigo-100');
                }
            }

            function submitStatusChange(applicationId, newStatus) {
                const card = document.querySelector(`.application-card[data-id="${applicationId}"]`);
                if (card) {
                    const select = card.querySelector('select[name="status"]');
                    if (select) {
                        select.value = newStatus;
                        select.closest('form').submit();
                    }
                }
            }
        </script>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/applications/index.blade.php ENDPATH**/ ?>