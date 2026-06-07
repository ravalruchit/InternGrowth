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
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Verify Your College Email</h1>

        <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                <p class="text-green-800"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                <p class="text-red-800"><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <?php if($profile->is_verified): ?>
            <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-green-900">Email Verified!</h3>
                        <p class="text-green-700"><?php echo e($profile->college_email); ?></p>
                        <p class="text-sm text-green-600 mt-1"><?php echo e($profile->college_name); ?></p>
                        <p class="text-xs text-green-600">Verified on: <?php echo e($profile->email_verified_at->format('M d, Y')); ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="mb-6">
                    <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-blue-900">Why verify your college email?</h3>
                            <ul class="text-sm text-blue-800 mt-2 space-y-1">
                                <li>• Access ALL available tasks (currently limited to 5 tasks)</li>
                                <li>• Build trust with startups</li>
                                <li>• Prove you're a genuine student</li>
                                <li>• Get priority in recommendations</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('student.verification.send')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            College Email Address
                            <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="college_email" 
                            value="<?php echo e(old('college_email', $profile->college_email)); ?>"
                            required
                            placeholder="your.name@college.edu"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <p class="text-xs text-gray-500 mt-1">Must be a valid college email (.edu, .ac.in, .edu.in)</p>
                        <?php $__errorArgs = ['college_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            College/University Name
                            <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="college_name" 
                            value="<?php echo e(old('college_name', $profile->college_name)); ?>"
                            required
                            placeholder="e.g., MIT, Stanford University"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <?php $__errorArgs = ['college_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-sm text-yellow-800">
                                A verification link will be sent to your college email. Please check your inbox and spam folder.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button 
                            type="submit" 
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold"
                        >
                            Send Verification Email
                        </button>
                        <a 
                            href="<?php echo e(route('student.dashboard')); ?>" 
                            class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
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
<?php /**PATH D:\InternGrowth\InternGrowth\resources\views/student/verification.blade.php ENDPATH**/ ?>