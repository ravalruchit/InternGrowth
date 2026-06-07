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
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Review Submission</h1>
            
            <?php if(session('success')): ?>
                <div id="success-message" class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded" style="transition: opacity 0.5s ease-out;">
                    <p class="text-green-800 font-semibold"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div id="error-message" class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded" style="transition: opacity 0.5s ease-out;">
                    <p class="text-red-800 font-semibold"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Task</h3>
                <p class="text-gray-900"><?php echo e($submission->application->task->title); ?></p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Student</h3>
                <p class="text-gray-900"><?php echo e($submission->application->student->user->name); ?></p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Submission Content</h3>
                <?php if($submission->status !== 'rejected'): ?>
                    <div class="bg-gray-50 p-4 rounded select-none" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none;" oncontextmenu="return false;" oncopy="return false;" oncut="return false;">
                        <p class="text-gray-900 whitespace-pre-wrap"><?php echo e($submission->content); ?></p>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">⚠️ Content is protected - copying and downloading disabled</p>
                <?php else: ?>
                    <div class="bg-red-50 p-4 rounded border-l-4 border-red-400">
                        <p class="text-red-800">Content hidden - Submission was rejected</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php
                $hasValidFiles = false;
                if ($submission->files && is_array($submission->files)) {
                    foreach ($submission->files as $file) {
                        if (is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])) {
                            $hasValidFiles = true;
                            break;
                        }
                    }
                }
            ?>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Attached Files:</h3>
                <?php if($submission->status === 'rejected'): ?>
                    <div class="bg-red-50 p-4 rounded border-l-4 border-red-400">
                        <p class="text-red-800">Files hidden - Submission was rejected</p>
                    </div>
                <?php elseif($hasValidFiles): ?>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $submission->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])): ?>
                                <?php
                                    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isPdf = $fileExtension === 'pdf';
                                    $isText = in_array($fileExtension, ['txt', 'md', 'json', 'xml', 'csv', 'log', 'html', 'css', 'js', 'php', 'py', 'java', 'c', 'cpp', 'h']);
                                ?>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    
                                    <!-- Show Download Button ONLY when accepted, otherwise Preview -->
                                    <?php if($submission->status === 'accepted'): ?>
                                        <!-- Accepted: Show Download Button -->
                                        <a href="<?php echo e(asset('storage/' . $file['path'])); ?>" download="<?php echo e($file['name']); ?>" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                                            ⬇️ Download <?php echo e($file['name']); ?>

                                        </a>
                                        <p class="text-xs text-green-600 mt-2">✓ Download enabled - Submission accepted</p>
                                    <?php else: ?>
                                        <!-- Pending/Revision: Show Preview Only -->
                                        <?php if($isImage): ?>
                                            <button onclick="document.getElementById('image-preview-<?php echo e($loop->index); ?>').classList.toggle('hidden')" class="mb-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                                                Preview
                                            </button>
                                            <div id="image-preview-<?php echo e($loop->index); ?>" class="hidden select-none" style="user-select: none; -webkit-user-select: none;" oncontextmenu="return false;">
                                                <img src="<?php echo e(asset('storage/' . $file['path'])); ?>" alt="<?php echo e($file['name']); ?>" class="max-w-full h-auto rounded border border-gray-300" style="pointer-events: none;">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">🔒 Preview only - Right-click disabled</p>
                                        <?php elseif($isPdf): ?>
                                            <button onclick="document.getElementById('pdf-preview-<?php echo e($loop->index); ?>').classList.toggle('hidden')" class="mb-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                                               Preview
                                            </button>
                                            <div id="pdf-preview-<?php echo e($loop->index); ?>" class="hidden bg-white rounded border border-gray-300 overflow-auto" style="max-height: 500px;">
                                                <iframe src="<?php echo e(asset('storage/' . $file['path'])); ?>#toolbar=0&navpanes=0&scrollbar=1" class="w-full rounded" style="height: 500px; border: none;" oncontextmenu="return false;"></iframe>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">🔒 Preview only - Scroll to view all pages</p>
                                        <?php elseif($isText): ?>
                                            <button onclick="document.getElementById('text-preview-<?php echo e($loop->index); ?>').classList.toggle('hidden')" class="mb-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                                               Preview
                                            </button>
                                            <div id="text-preview-<?php echo e($loop->index); ?>" class="hidden bg-gray-900 text-green-400 p-4 rounded border border-gray-300 overflow-auto select-none" style="max-height: 500px; user-select: none; -webkit-user-select: none; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.5;" oncontextmenu="return false;" oncopy="return false;">
                                                <?php
                                                    $filePath = storage_path('app/public/' . $file['path']);
                                                    if (file_exists($filePath)) {
                                                        $content = file_get_contents($filePath);
                                                        // Limit to first 10000 characters for preview
                                                        if (strlen($content) > 10000) {
                                                            $content = substr($content, 0, 10000) . "\n\n... (Content truncated for preview)";
                                                        }
                                                        echo htmlspecialchars($content);
                                                    } else {
                                                        echo "File not found";
                                                    }
                                                ?>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">🔒 Preview only - Copy disabled</p>
                                        <?php else: ?>
                                            <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                                                <p class="text-sm text-yellow-800">📄 <?php echo e(strtoupper($fileExtension)); ?> file - Preview not available</p>
                                                <p class="text-xs text-yellow-600 mt-1">File type: <?php echo e($file['type'] ?? 'Unknown'); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($submission->status !== 'accepted'): ?>
                        <div class="mt-4 p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                            <p class="text-sm text-blue-800">⚠️ <strong>Security Notice:</strong> Files are preview-only to protect student work. Download will be enabled after acceptance.</p>
                        </div>
                    <?php else: ?>
                        <div class="mt-4 p-3 bg-green-50 rounded border-l-4 border-green-400">
                            <p class="text-sm text-green-800">✓ <strong>Download Enabled:</strong> Submission accepted - You can now download all files.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-sm italic">No files attached to this submission.</p>
                <?php endif; ?>
            </div>

            <?php if($submission->status === 'pending' || $submission->status === 'revision_requested'): ?>
                <div class="flex gap-4">
                    <form method="POST" action="<?php echo e(route('startup.submissions.accept', $submission->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">✓ Accept</button>
                    </form>

                    <button onclick="document.getElementById('revisionForm').classList.toggle('hidden')" class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700">🔄 Request Revision</button>

                    <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">✗ Reject</button>
                </div>
            <?php elseif($submission->status === 'accepted'): ?>
                <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                    <p class="text-green-800 font-semibold">✓ Submission Accepted</p>
                    <p class="text-green-700 text-sm mt-1">You can now download the files below.</p>
                </div>
            <?php elseif($submission->status === 'rejected'): ?>
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                    <p class="text-red-800 font-semibold">✗ Submission Rejected</p>
                </div>
            <?php endif; ?>

            <?php if($submission->status === 'accepted' && !$rating): ?>
                <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">⭐ Rate Student Performance</h3>
                    <form method="POST" action="<?php echo e(route('startup.submissions.rate', $submission->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating (1-5 stars)</label>
                            <select name="rating" required class="border-gray-300 rounded-lg">
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Below Average</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                            <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-lg" placeholder="Share your feedback about the student's work..."></textarea>
                        </div>
                        <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">Submit Rating</button>
                    </form>
                </div>
            <?php elseif($rating): ?>
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">✓ Rating Submitted</h3>
                    <p class="text-gray-600">Rating: <span class="font-bold text-indigo-600"><?php echo e($rating->rating); ?>/5 ⭐</span></p>
                    <?php if($rating->comment): ?>
                        <p class="text-gray-600 mt-2">Comment: "<?php echo e($rating->comment); ?>"</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div id="revisionForm" class="hidden mt-6">
                <form method="POST" action="<?php echo e(route('startup.submissions.revision', $submission->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Revision Feedback</label>
                    <textarea name="feedback" rows="4" required class="w-full border-gray-300 rounded-lg mb-2"></textarea>
                    <button class="bg-yellow-600 text-white px-4 py-2 rounded-lg">Submit Revision Request</button>
                </form>
            </div>

            <div id="rejectForm" class="hidden mt-6">
                <form method="POST" action="<?php echo e(route('startup.submissions.reject', $submission->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="feedback" rows="4" required class="w-full border-gray-300 rounded-lg mb-2"></textarea>
                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg">Confirm Rejection</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide success and error messages after 4 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            
            if (successMsg) {
                setTimeout(() => {
                    successMsg.style.opacity = '0';
                    setTimeout(() => successMsg.remove(), 500);
                }, 4000);
            }
            
            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.style.opacity = '0';
                    setTimeout(() => errorMsg.remove(), 500);
                }, 4000);
            }
        });
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\resources\views/submissions/review.blade.php ENDPATH**/ ?>