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
        <div class="mb-6">
            <a href="<?php echo e(route('dashboard')); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Submit Your Work</h1>
            <p class="text-gray-600 mb-6">Task: <?php echo e($application->task->title); ?></p>

            <!-- Premium Motivation Reputation Card -->
            <div class="mb-8">
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
            </div>

            <form method="POST" action="<?php echo e(route('submissions.store', $application->id)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <!-- Task Description -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-2">Task Description:</h3>
                    <p class="text-gray-700"><?php echo e($application->task->description); ?></p>
                </div>

                <!-- Submission Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Work / Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="8" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Describe your work, provide links, or paste your content here..."
                    ><?php echo e(old('content')); ?></textarea>
                    <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-gray-500 text-sm mt-1">Provide a detailed description of your work, include any relevant links (GitHub, Google Drive, etc.)</p>
                </div>

                <!-- File Upload (Optional) -->
                <div class="mb-6">
                    <label for="files" class="block text-sm font-medium text-gray-700 mb-2">
                        Attach Files (Optional)
                    </label>
                    <input 
                        type="file" 
                        id="files" 
                        name="files[]" 
                        multiple
                        accept=".pdf,.doc,.docx,.txt,.zip,.jpg,.jpeg,.png,.gif"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        onchange="displaySelectedFiles(this)"
                    >
                    <p class="text-gray-500 text-sm mt-1">You can upload multiple files (PDF, DOC, images, ZIP - max 10MB each)</p>
                    <div id="fileList" class="mt-2 space-y-1"></div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg font-medium hover:shadow-lg transition"
                    >
                        Submit Work
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function displaySelectedFiles(input) {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';
            
            if (input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const fileSize = (file.size / 1024).toFixed(2);
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 p-2 rounded';
                    fileDiv.innerHTML = `
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="flex-1">${file.name}</span>
                        <span class="text-xs text-gray-500">${fileSize} KB</span>
                    `;
                    fileList.appendChild(fileDiv);
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
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/submissions/create.blade.php ENDPATH**/ ?>