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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-8">
            <!-- Startup Info Badge -->
            <div class="mb-4 flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Posted by</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="<?php echo e(route('startups.public-profile', $task->startup->id)); ?>" class="text-lg font-bold text-indigo-650 hover:text-indigo-855 hover:underline transition">
                            <?php echo e($task->startup->company_name); ?>

                        </a>
                        <?php if($task->startup->is_verified): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                ✓ Verified
                            </span>
                        <?php endif; ?>
                        <?php
                            $trustScoreVal = $task->startup->trustScore ? $task->startup->trustScore->overall_score : ($task->startup->credibility_score * 100);
                            if ($trustScoreVal <= 0) $trustScoreVal = 100;
                        ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-150">
                            🛡️ <?php echo e(number_format($trustScoreVal, 0)); ?>/100 Trust Score
                        </span>
                    </div>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo e($task->title); ?></h1>
            
            <?php if(auth()->check() && auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id): ?>
                <?php
                    $hasApprovedApp = $task->applications()->where('status', 'approved')->count() > 0;
                ?>
                <div class="mb-4 flex gap-2">
                    <?php if(!$hasApprovedApp): ?>
                        <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit Task</a>
                    <?php else: ?>
                        <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed" title="Cannot edit task after approving an application">
                            Edit Task (Locked)
                        </button>
                    <?php endif; ?>
                    <?php if($task->applications->count() === 0): ?>
                        <form method="POST" action="<?php echo e(route('tasks.destroy', $task->id)); ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Delete Task</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                <p class="text-gray-600"><?php echo e($task->description); ?></p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Required Skills</h3>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $task->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded"><?php echo e($skill->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Reward Points</h3>
                    <p class="text-2xl font-bold text-indigo-600"><?php echo e($task->reward_points); ?></p>
                </div>
                <?php if($task->stipend): ?>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Stipend</h3>
                        <p class="text-2xl font-bold text-green-600">₹<?php echo e($task->stipend); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isStudent()): ?>
                    <?php
                        $existingApplication = $task->applications->where('student_profile_id', auth()->user()->studentProfile->id)->first();
                        $hasApprovedApplication = $task->applications->where('status', 'approved')->count() > 0;
                        $approvedApplication = $task->applications->where('status', 'approved')->first();
                        $hasAcceptedSubmission = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->count() > 0;
                        $acceptedApplication = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->first();
                    ?>
                    
                    <?php if($hasAcceptedSubmission || $task->status === 'completed'): ?>
                        <!-- Task completed -->
                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-900">This task has been completed</h3>
                                    <p class="text-green-700 mt-1">
                                        This task has been successfully completed by 
                                        <strong><?php echo e($acceptedApplication ? $acceptedApplication->student->user->name : 'a student'); ?></strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php elseif($hasApprovedApplication && !$existingApplication): ?>
                        <!-- Task already accepted by someone else -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-900">This task has been accepted</h3>
                                    <p class="text-yellow-700 mt-1">
                                        <strong><?php echo e($approvedApplication ? $approvedApplication->student->user->name : 'A student'); ?></strong> has already been approved for this task. Applications are no longer being accepted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php elseif($existingApplication): ?>
                        <!-- Show application status -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Your Application Status</h3>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-4 py-2 text-sm font-medium rounded-full <?php echo e($existingApplication->status === 'approved' ? 'bg-green-100 text-green-800' : ($existingApplication->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                            <?php echo e(ucfirst($existingApplication->status)); ?>

                                        </span>
                                        <?php if($existingApplication->status === 'approved'): ?>
                                            <span class="text-green-600 font-medium">✓ You've been accepted for this task!</span>
                                        <?php elseif($existingApplication->status === 'rejected'): ?>
                                            <span class="text-red-600 font-medium">✗ Your application was not accepted</span>
                                        <?php else: ?>
                                            <span class="text-yellow-600 font-medium">⏳ Waiting for startup review</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($existingApplication->cover_letter): ?>
                                        <p class="text-sm text-gray-600 mt-3">
                                            <strong>Your cover letter:</strong> <?php echo e(Str::limit($existingApplication->cover_letter, 100)); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('messages.create', [auth()->user()->studentProfile->id, $task->startup_profile_id, $task->id])); ?>" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:shadow-md transition font-medium">
                                    💬 Message
                                </a>
                            </div>
                            
                            <?php if($existingApplication->status === 'approved'): ?>
                                <?php
                                    $submission = $existingApplication->submission;
                                ?>
                                
                                <!-- Submit Work / Revision Section -->
                                <div class="mt-6 pt-6 border-t border-indigo-200">
                                    <?php if(!$submission): ?>
                                        <!-- No submission yet - show submit button -->
                                        <div class="bg-white rounded-lg p-4 border-2 border-green-400">
                                            <h4 class="font-semibold text-gray-900 mb-2">📝 Ready to Submit Your Work?</h4>
                                            <p class="text-sm text-gray-600 mb-4">You've been approved! Now submit your completed work for review.</p>
                                            <a href="<?php echo e(route('submissions.create', $existingApplication->id)); ?>" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
                                                Submit Work →
                                            </a>
                                        </div>
                                    <?php elseif($submission->status === 'pending'): ?>
                                        <!-- Submission pending review -->
                                        <div class="bg-yellow-50 rounded-lg p-4 border-2 border-yellow-400">
                                            <h4 class="font-semibold text-gray-900 mb-2">⏳ Submission Under Review</h4>
                                            <p class="text-sm text-gray-600">Your work has been submitted and is waiting for startup review.</p>
                                            <p class="text-xs text-gray-500 mt-2">Submitted: <?php echo e($submission->created_at->format('M d, Y H:i')); ?></p>
                                        </div>
                                    <?php elseif($submission->status === 'revision_requested'): ?>
                                        <!-- Revision requested - show revise button -->
                                        <div class="bg-orange-50 rounded-lg p-4 border-2 border-orange-400">
                                            <h4 class="font-semibold text-gray-900 mb-2">🔄 Revision Requested</h4>
                                            <p class="text-sm text-gray-600 mb-2">The startup has requested changes to your submission.</p>
                                            <?php if($submission->feedback): ?>
                                                <div class="bg-white p-3 rounded border border-orange-200 mb-3">
                                                    <p class="text-xs font-medium text-gray-500 mb-1">Feedback:</p>
                                                    <p class="text-sm text-gray-800"><?php echo e($submission->feedback); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('submissions.revise', $submission->id)); ?>" class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 font-semibold">
                                                Revise Submission →
                                            </a>
                                        </div>
                                    <?php elseif($submission->status === 'accepted'): ?>
                                        <!-- Work accepted -->
                                        <div class="bg-green-50 rounded-lg p-4 border-2 border-green-400">
                                            <h4 class="font-semibold text-green-900 mb-2">✅ Work Accepted!</h4>
                                            <p class="text-sm text-green-700">Congratulations! Your submission has been accepted.</p>
                                        </div>
                                    <?php elseif($submission->status === 'rejected'): ?>
                                        <!-- Work rejected -->
                                        <div class="bg-red-50 rounded-lg p-4 border-2 border-red-400">
                                            <h4 class="font-semibold text-red-900 mb-2">❌ Submission Rejected</h4>
                                            <?php if($submission->feedback): ?>
                                                <div class="bg-white p-3 rounded border border-red-200 mb-2">
                                                    <p class="text-xs font-medium text-gray-500 mb-1">Feedback:</p>
                                                    <p class="text-sm text-gray-800"><?php echo e($submission->feedback); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <p class="text-sm text-red-700">Unfortunately, your submission was not accepted.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- Show application form -->
                        <form method="POST" action="<?php echo e(route('applications.store', $task->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter (Optional)</label>
                                <textarea name="cover_letter" rows="4" class="w-full border-gray-300 rounded-lg" placeholder="Tell the startup why you're a great fit for this task..."></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition font-medium">Apply Now</button>
                                <a href="<?php echo e(route('messages.create', [auth()->user()->studentProfile->id, $task->startup_profile_id, $task->id])); ?>" class="bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg hover:shadow-md transition font-medium">
                                    💬 Message Startup
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id): ?>
                    <!-- AI Recommended Students -->
                    <?php if($recommendedStudents && $recommendedStudents->count() > 0): ?>
                    <div class="mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border-2 border-blue-200">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">🤖 AI Recommended Students</h2>
                                <p class="text-gray-600 text-sm mt-1">Top students matched to this task's requirements</p>
                            </div>
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">AI POWERED</span>
                        </div>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $recommendedStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white border-2 border-blue-200 rounded-lg p-4 hover:shadow-xl transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="font-semibold text-lg text-gray-900"><?php echo e($student->user->name); ?></h3>
                                                <span class="text-2xl font-bold text-blue-600"><?php echo e($student->match_score); ?>%</span>
                                                <?php if($student->match_score >= 80): ?>
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                                        🔥 Perfect Match
                                                    </span>
                                                <?php elseif($student->match_score >= 60): ?>
                                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">
                                                        ⭐ Good Match
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($student->bio ?? 'No bio available', 100)); ?></p>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-medium">
                                                    Reliability: <?php echo e(number_format($student->reliability_score * 100, 0)); ?>%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="<?php echo e(route('students.public-profile', $student->id)); ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                View Profile →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-4 text-center">
                            💡 Tip: Reach out to these students directly to invite them to apply!
                        </p>
                    </div>
                    <?php endif; ?>
                
                    <div class="mt-8 border-t pt-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Applications & Submissions (<?php echo e($task->applications->count()); ?>)</h2>
                        
                        <?php if($task->applications->count() === 0): ?>
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 mt-4">No applications yet. Students will see your task and can apply.</p>
                            </div>
                        <?php else: ?>
                            <?php $__currentLoopData = $task->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-gray-200 rounded-lg p-5 mb-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-lg"><?php echo e($application->student->user->name); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo e($application->student->user->email); ?></p>

                                        
                                        <?php
                                            $points = $application->student->wallet?->balance ?? 0;
                                            $reliability = $application->student->reliability_score ?? 0;
                                        ?>
                                        <div class="flex flex-wrap items-center gap-3 mt-2">
                                            <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.952 2.878c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.064 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.285-3.957z"/>
                                                </svg>
                                                <?php echo e(number_format($points)); ?> pts
                                            </span>
                                            <span class="inline-flex items-center gap-1 <?php echo e($reliability >= 0.7 ? 'bg-green-50 text-green-700' : ($reliability >= 0.4 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-50 text-gray-600')); ?> text-xs font-semibold px-2.5 py-1 rounded-full">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                <?php echo e(number_format($reliability * 100, 0)); ?>% reliability
                                            </span>
                                            <?php if($application->student->is_verified): ?>
                                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Verified
                                                </span>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('students.public-profile', $application->student->id)); ?>" target="_blank"
                                               class="text-xs text-indigo-600 hover:text-indigo-800 underline">
                                                View Profile →
                                            </a>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        <?php if($application->status === 'approved' || $application->status === 'internship_accepted' || $application->status === 'hired'): ?> 
                                            bg-green-100 text-green-800
                                        <?php elseif($application->status === 'rejected'): ?> 
                                            bg-red-100 text-red-800 
                                        <?php elseif($application->status === 'shortlisted' || $application->status === 'interview'): ?> 
                                            bg-blue-100 text-blue-800 
                                        <?php elseif($application->status === 'internship_offered'): ?>
                                            bg-purple-100 text-purple-800
                                        <?php else: ?> 
                                            bg-yellow-100 text-yellow-800 
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $application->status))); ?>

                                    </span>
                                </div>
                                
                                <?php if($application->cover_letter): ?>
                                    <div class="mb-3 p-3 bg-gray-50 rounded">
                                        <p class="text-sm text-gray-700"><strong>Cover Letter:</strong> <?php echo e($application->cover_letter); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Talent Pipeline Status Actions -->
                                <div class="bg-slate-50 border border-gray-200 rounded-xl p-4 mb-4 mt-3">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Hiring Funnel Stage</h4>
                                    
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
                                            if ($application->submission && $application->submission->status === 'accepted') {
                                                $currentStatus = 'task_completed';
                                            }
                                            $stagesKeys = array_keys($stages);
                                            $currentIndex = array_search($currentStatus, $stagesKeys);
                                            if ($currentIndex === false) {
                                                if (in_array($currentStatus, ['internship_accepted'])) {
                                                    $currentIndex = 5;
                                                } else {
                                                    $currentIndex = 0;
                                                }
                                            }
                                        ?>
                                        <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $keyIndex = array_search($key, $stagesKeys);
                                                $isActive = $key === $currentStatus;
                                                $isPassed = $keyIndex < $currentIndex;
                                            ?>
                                            <div class="flex items-center flex-1 last:flex-none">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold <?php echo e($isActive ? 'bg-indigo-600 text-white' : ($isPassed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600')); ?>">
                                                        <?php if($isPassed): ?> ✓ <?php else: ?> <?php echo e($keyIndex + 1); ?> <?php endif; ?>
                                                    </div>
                                                    <span class="text-[10px] font-semibold mt-1 whitespace-nowrap <?php echo e($isActive ? 'text-indigo-650 font-bold' : 'text-gray-500'); ?>"><?php echo e($label); ?></span>
                                                </div>
                                                <?php if(!$loop->last): ?>
                                                    <div class="h-0.5 flex-1 mx-2 <?php echo e($keyIndex < $currentIndex ? 'bg-green-500' : 'bg-gray-200'); ?>"></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <!-- Actions Dropdown -->
                                    <form method="POST" action="<?php echo e(route('startup.applications.update-status', $application->id)); ?>" class="flex items-center space-x-3">
                                        <?php echo csrf_field(); ?>
                                        <label class="text-xs font-bold text-gray-700">Move Candidate To:</label>
                                        <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg text-xs py-1.5 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500">
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
                                
                                <!-- Submission Section -->
                                <?php if($application->submission): ?>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-semibold text-gray-900">Work Submission</h4>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo e($application->submission->status === 'accepted' ? 'bg-green-100 text-green-800' : ($application->submission->status === 'rejected' ? 'bg-red-100 text-red-800' : ($application->submission->status === 'revision_requested' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'))); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $application->submission->status))); ?>

                                            </span>
                                        </div>
                                        
                                        <div class="p-4 bg-gray-50 rounded-lg mb-3">
                                            <div class="mb-3">
                                                <h5 class="font-semibold text-gray-900 mb-2">Submission Description:</h5>
                                                <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($application->submission->content); ?></p>
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
                                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                                        <h5 class="font-semibold text-gray-900 mb-2">Attached Files:</h5>
                                                        <div class="space-y-2">
                                                            <?php $__currentLoopData = $application->submission->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])): ?>
                                                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200">
                                                                        <div class="flex items-center space-x-3 flex-1">
                                                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                            </svg>
                                                                            <div class="flex-1">
                                                                                <div class="flex items-center space-x-2">
                                                                                    <p class="text-sm font-medium text-gray-900"><?php echo e($file['name']); ?></p>
                                                                                    <?php if(isset($file['version'])): ?>
                                                                                        <?php if($file['version'] === 'original'): ?>
                                                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Original</span>
                                                                                        <?php elseif($file['version'] === 'revision'): ?>
                                                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Revised</span>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <p class="text-xs text-gray-500">
                                                                                    <?php echo e(isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : ''); ?>

                                                                                    <?php if(isset($file['uploaded_at'])): ?>
                                                                                        • Uploaded: <?php echo e(\Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y H:i')); ?>

                                                                                    <?php endif; ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <a href="<?php echo e(route('startup.submissions.review', $application->submission->id)); ?>" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium ml-3 px-3 py-1.5 bg-indigo-50 rounded-lg border border-indigo-200">
                                                                            �️ View Preview
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
                                            <div class="p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded mb-3">
                                                <p class="text-sm text-yellow-800"><strong>Your Feedback:</strong> <?php echo e($application->submission->feedback); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Submission Actions -->
                                        <?php if(in_array($application->submission->status, ['pending', 'submitted', 'revision_requested'])): ?>
                                            <div class="flex gap-2">
                                                <form method="POST" action="<?php echo e(route('startup.submissions.accept', $application->submission->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">✓ Accept Work</button>
                                                </form>
                                                
                                                <button onclick="document.getElementById('revision-form-<?php echo e($application->submission->id); ?>').classList.toggle('hidden')" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-medium">
                                                    ↻ Request Revision
                                                </button>
                                                <button onclick="document.getElementById('reject-form-<?php echo e($application->submission->id); ?>').classList.toggle('hidden')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium">
                                                    ✗ Reject Work
                                                </button>
                                            </div>
                                            
                                            <!-- Revision Request Form -->
                                            <div id="revision-form-<?php echo e($application->submission->id); ?>" class="hidden mt-3">
                                                <form method="POST" action="<?php echo e(route('startup.submissions.revision', $application->submission->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <textarea name="feedback" rows="3" required class="w-full border-gray-300 rounded-lg mb-2" placeholder="Explain what needs to be revised..."></textarea>
                                                    <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-medium">Send Revision Request</button>
                                                </form>
                                            </div>
                                            
                                            <!-- Reject Form -->
                                            <div id="reject-form-<?php echo e($application->submission->id); ?>" class="hidden mt-3">
                                                <form method="POST" action="<?php echo e(route('startup.submissions.reject', $application->submission->id)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <textarea name="feedback" rows="3" required class="w-full border-gray-300 rounded-lg mb-2" placeholder="Explain why the work is rejected..."></textarea>
                                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium">Confirm Rejection</button>
                                                </form>
                                            </div>
                                        <?php elseif($application->submission->status === 'accepted'): ?>
                                            <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded">
                                                <div class="flex items-center">
                                                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-semibold text-green-800">✓ Task Completed Successfully</p>
                                                        <p class="text-xs text-green-700 mt-1">Student has been awarded <?php echo e($task->reward_points); ?> points.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif($application->status === 'approved'): ?>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-sm text-gray-500 italic">Waiting for student to submit their work...</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/tasks/show.blade.php ENDPATH**/ ?>