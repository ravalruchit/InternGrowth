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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Startup Verification Requests</h1>

        <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                <p class="text-green-800"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <!-- Pending Verifications -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Pending Verifications (<?php echo e($pendingVerifications->count()); ?>)</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $pendingVerifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $startup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($startup->company_name); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo e($startup->user->email); ?></p>
                                <?php if($startup->verification_submitted_at): ?>
                                    <p class="text-xs text-gray-500 mt-1">Submitted: <?php echo e($startup->verification_submitted_at->format('M d, Y H:i')); ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                Pending Review
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs font-medium text-gray-500">Company Registration Number</p>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e($startup->company_registration_number); ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500">GST Number</p>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e($startup->gst_number ?? 'Not provided'); ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500">Contact Phone</p>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e($startup->contact_phone); ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500">Website</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <?php if($startup->website): ?>
                                        <a href="<?php echo e($startup->website); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800"><?php echo e($startup->website); ?></a>
                                    <?php else: ?>
                                        Not provided
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs font-medium text-gray-500">Company Address</p>
                                <p class="text-sm text-gray-900"><?php echo e($startup->company_address); ?></p>
                            </div>
                        </div>

                        <?php if($startup->verification_documents && count($startup->verification_documents) > 0): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Uploaded Documents:</p>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $startup->verification_documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between bg-white border border-gray-200 p-3 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900"><?php echo e($doc['name']); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo e(number_format($doc['size'] / 1024, 2)); ?> KB</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="<?php echo e(asset('storage/' . $doc['path'])); ?>" target="_blank" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 text-xs font-medium">
                                                    View
                                                </a>
                                                <a href="<?php echo e(asset('storage/' . $doc['path'])); ?>" download="<?php echo e($doc['name']); ?>" class="bg-gray-600 text-white px-3 py-1.5 rounded-lg hover:bg-gray-700 text-xs font-medium">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="flex gap-3">
                            <button onclick="document.getElementById('approve-form-<?php echo e($startup->id); ?>').classList.toggle('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                                ✓ Approve
                            </button>
                            <button onclick="document.getElementById('reject-form-<?php echo e($startup->id); ?>').classList.toggle('hidden')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium">
                                ✗ Reject
                            </button>
                        </div>

                        <!-- Approve Form -->
                        <div id="approve-form-<?php echo e($startup->id); ?>" class="hidden mt-4 p-4 bg-green-50 rounded-lg">
                            <form method="POST" action="<?php echo e(route('admin.verifications.approve', $startup->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <p class="text-sm text-green-800 mb-3">Are you sure you want to approve this startup? They will be able to post tasks.</p>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                                        Confirm Approval
                                    </button>
                                    <button type="button" onclick="document.getElementById('approve-form-<?php echo e($startup->id); ?>').classList.add('hidden')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-sm font-medium">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Reject Form -->
                        <div id="reject-form-<?php echo e($startup->id); ?>" class="hidden mt-4 p-4 bg-red-50 rounded-lg">
                            <form method="POST" action="<?php echo e(route('admin.verifications.reject', $startup->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (will be shown to startup)</label>
                                <textarea name="notes" rows="3" required class="w-full border-gray-300 rounded-lg mb-3" placeholder="e.g., Invalid registration number, documents not clear, etc."></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium">
                                        Confirm Rejection
                                    </button>
                                    <button type="button" onclick="document.getElementById('reject-form-<?php echo e($startup->id); ?>').classList.add('hidden')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-sm font-medium">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 mt-2">No pending verification requests</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recently Reviewed -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Recently Reviewed</h2>
            </div>
            <div class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $recentlyReviewed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $startup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($startup->company_name); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo e($startup->user->email); ?></p>
                                <?php if($startup->verification_reviewed_at): ?>
                                    <p class="text-xs text-gray-500 mt-1">Reviewed: <?php echo e($startup->verification_reviewed_at->format('M d, Y H:i')); ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo e($startup->verification_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e(ucfirst($startup->verification_status)); ?>

                            </span>
                        </div>
                        <?php if($startup->verification_notes): ?>
                            <div class="mt-3 p-3 bg-gray-50 rounded">
                                <p class="text-xs font-medium text-gray-500">Notes:</p>
                                <p class="text-sm text-gray-700"><?php echo e($startup->verification_notes); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-12 text-center">
                        <p class="text-gray-500">No recently reviewed verifications</p>
                    </div>
                <?php endif; ?>
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
<?php /**PATH D:\InternGrowth\InternGrowth\resources\views/admin/verifications.blade.php ENDPATH**/ ?>