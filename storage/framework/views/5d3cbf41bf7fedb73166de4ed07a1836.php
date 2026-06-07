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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Startup Verification</h1>
            <p class="text-gray-600 mb-6">Submit your company documents for verification to start posting tasks</p>

            <?php if($profile->verification_status === 'pending' && $profile->verification_submitted_at): ?>
                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <p class="text-yellow-800">
                        <strong>Verification Pending</strong> - Your verification request is under review. 
                        Submitted on <?php echo e($profile->verification_submitted_at->format('M d, Y H:i')); ?>

                    </p>
                </div>
            <?php elseif($profile->verification_status === 'rejected'): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                    <p class="text-red-800 mb-2">
                        <strong>Verification Rejected</strong>
                    </p>
                    <?php if($profile->verification_notes): ?>
                        <p class="text-red-700 text-sm">Reason: <?php echo e($profile->verification_notes); ?></p>
                    <?php endif; ?>
                    <p class="text-red-700 text-sm mt-2">Please update your information and resubmit.</p>
                </div>
            <?php elseif($profile->is_verified): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                    <p class="text-green-800">
                        <strong>✓ Verified</strong> - Your startup is verified! You can now post tasks.
                    </p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('startup.verification.submit')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Company Registration Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="company_registration_number" 
                           value="<?php echo e(old('company_registration_number', $profile->company_registration_number)); ?>" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                           placeholder="e.g., U12345AB2020PTC123456">
                    <?php $__errorArgs = ['company_registration_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        GST Number (Optional)
                    </label>
                    <input type="text" name="gst_number" 
                           value="<?php echo e(old('gst_number', $profile->gst_number)); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                           placeholder="e.g., 22AAAAA0000A1Z5">
                    <?php $__errorArgs = ['gst_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Company Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="company_address" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                              placeholder="Full registered address"><?php echo e(old('company_address', $profile->company_address)); ?></textarea>
                    <?php $__errorArgs = ['company_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="contact_phone" 
                           value="<?php echo e(old('contact_phone', $profile->contact_phone)); ?>" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                           placeholder="+91 1234567890">
                    <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Verification Documents <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Upload company registration certificate, GST certificate, or other relevant documents (PDF, JPG, PNG - max 5MB each)</p>
                    <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <?php $__errorArgs = ['documents'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php if($profile->verification_documents && count($profile->verification_documents) > 0): ?>
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-900 mb-2">Previously Uploaded Documents:</h3>
                        <div class="space-y-2">
                            <?php $__currentLoopData = $profile->verification_documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700"><?php echo e($doc['name']); ?></span>
                                    <a href="<?php echo e(asset('storage/' . $doc['path'])); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800">View</a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Uploading new documents will replace the old ones.</p>
                    </div>
                <?php endif; ?>

                <div class="flex gap-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                        Submit for Verification
                    </button>
                    <a href="<?php echo e(route('startup.dashboard')); ?>" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium">
                        Cancel
                    </a>
                </div>
            </form>
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
<?php /**PATH D:\InternGrowth\InternGrowth\resources\views/startup/verification.blade.php ENDPATH**/ ?>