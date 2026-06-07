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
                        <?php echo $__env->make('applications.index', ['applications' => $task->applications, 'task' => $task], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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