<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-[var(--ig-accent)] hover:text-[#E03E0B] text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Submit Your Work</h1>
            <p class="text-gray-600 mb-6">Task: {{ $application->task->title }}</p>

            <!-- Premium Motivation Reputation Card -->
            <div class="mb-8">
                <x-reputation-card />
            </div>

            <form method="POST" action="{{ route('submissions.store', $application->id) }}" enctype="multipart/form-data">
                @csrf

                <!-- Task Description -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-2">Task Description:</h3>
                    <p class="text-gray-700">{{ $application->task->description }}</p>
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent"
                        placeholder="Describe your work, provide links, or paste your content here..."
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent"
                        onchange="displaySelectedFiles(this)"
                    >
                    <p class="text-gray-500 text-sm mt-1">You can upload multiple files (PDF, DOC, images, ZIP - max 10MB each)</p>
                    <div id="fileList" class="mt-2 space-y-1"></div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button 
                        type="submit" 
                        class="bg-[var(--ig-ink)] hover:bg-[var(--ig-accent)] text-white px-8 py-3 rounded-lg font-medium hover:shadow-lg transition"
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
                        <svg class="w-4 h-4 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
</x-app-layout>
