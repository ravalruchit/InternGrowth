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
        <h1 class="text-3xl font-bold mb-6">Wallet Top-up Requests</h1>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-xl font-semibold">Pending Requests</h2>
                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">
                    <?php echo e($pending->count()); ?> pending
                </span>
            </div>

            <?php if($pending->isEmpty()): ?>
                <div class="p-8 text-center text-gray-500">No pending requests.</div>
            <?php else: ?>
                <div class="divide-y">
                    <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 text-lg"><?php echo e($req->startup->company_name); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($req->startup->user->email); ?></p>
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500">Amount:</span>
                                            <span class="font-bold text-green-600 ml-1">₹<?php echo e(number_format($req->amount, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Method:</span>
                                            <span class="ml-1 capitalize"><?php echo e(str_replace('_', ' ', $req->payment_method)); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Ref:</span>
                                            <span class="ml-1"><?php echo e($req->transaction_reference ?? '—'); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Current Balance:</span>
                                            <span class="ml-1 font-medium">₹<?php echo e(number_format($req->startup->wallet_balance, 2)); ?></span>
                                        </div>
                                    </div>
                                    <?php if($req->notes): ?>
                                        <p class="text-sm text-gray-600 mt-2 italic">"<?php echo e($req->notes); ?>"</p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-400 mt-2">Requested <?php echo e($req->created_at->diffForHumans()); ?></p>
                                </div>

                                <div class="flex gap-2 ml-4">
                                    <!-- Approve -->
                                    <form method="POST" action="<?php echo e(route('admin.topup.approve', $req->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="admin_notes" value="Payment verified and credited.">
                                        <button type="submit"
                                            onclick="return confirm('Approve ₹<?php echo e(number_format($req->amount, 2)); ?> for <?php echo e($req->startup->company_name); ?>?')"
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                            ✓ Approve
                                        </button>
                                    </form>

                                    <!-- Reject -->
                                    <button onclick="openRejectModal(<?php echo e($req->id); ?>, '<?php echo e($req->startup->company_name); ?>')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                        ✗ Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Reviewed Requests -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Reviewed Requests</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reviewed</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $reviewed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900"><?php echo e($req->startup->company_name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($req->startup->user->email); ?></p>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">₹<?php echo e(number_format($req->amount, 2)); ?></td>
                                <td class="px-6 py-4 capitalize text-sm"><?php echo e(str_replace('_', ' ', $req->payment_method)); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($req->transaction_reference ?? '—'); ?></td>
                                <td class="px-6 py-4">
                                    <?php if($req->status === 'approved'): ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">✓ Approved</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">✗ Rejected</span>
                                    <?php endif; ?>
                                    <?php if($req->admin_notes): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($req->admin_notes); ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo e($req->reviewed_at ? $req->reviewed_at->format('M d, Y') : '—'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">No reviewed requests yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($reviewed->hasPages()): ?>
                <div class="p-4 border-t"><?php echo e($reviewed->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Reject Top-up Request</h3>
            <p class="text-sm text-gray-600 mb-4">Rejecting request for: <span id="reject_company" class="font-semibold"></span></p>
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection <span class="text-red-500">*</span></label>
                    <textarea name="admin_notes" rows="3" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                        placeholder="e.g., Payment not received, invalid reference number..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg text-sm">Cancel</button>
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, company) {
            document.getElementById('reject_company').textContent = company;
            document.getElementById('rejectForm').action = '/admin/topup/' + id + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
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
<?php /**PATH D:\InternGrowth\InternGrowth\resources\views/admin/topup-requests.blade.php ENDPATH**/ ?>