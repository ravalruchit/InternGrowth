<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Revise Your Submission</h1>
            <p class="text-gray-600 mb-6">Task: {{ $submission->application->task->title }}</p>

            <!-- Revision Feedback -->
            <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-400 rounded-lg">
                <h3 class="font-semibold text-orange-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Revision Feedback from Startup:
                </h3>
                <p class="text-orange-800">{{ $submission->feedback }}</p>
            </div>

            <form method="POST" action="{{ route('submissions.update', $submission->id) }}" enctype="multipart/form-data">
                @csrf

                <!-- Task Description -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-2">Task Description:</h3>
                    <p class="text-gray-700">{{ $submission->application->task->description }}</p>
                </div>

                <!-- Previous Submission -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-blue-900 mb-2">Your Previous Submission:</h3>
                    <p class="text-blue-800 whitespace-pre-wrap">{{ $submission->content }}</p>
                    
                    @if($submission->files && is_array($submission->files) && count($submission->files) > 0)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-blue-900 mb-2">Previously Uploaded Files:</p>
                            <div class="space-y-1">
                                @foreach($submission->files as $file)
                                    @if(is_array($file) && isset($file['name']))
                                        <div class="flex items-center space-x-2 text-sm text-blue-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span>{{ $file['name'] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Revised Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Revised Work / Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="8" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Update your work based on the feedback..."
                    >{{ old('content', $submission->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Address the feedback points and improve your submission</p>
                </div>

                <!-- Additional Files -->
                <div class="mb-6">
                    <label for="files" class="block text-sm font-medium text-gray-700 mb-2">
                        Add More Files (Optional)
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
                    <p class="text-gray-500 text-sm mt-1">Your previous files will be kept. You can add more files here.</p>
                    <div id="fileList" class="mt-2 space-y-1"></div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-3 rounded-lg font-medium hover:shadow-lg transition"
                    >
                        Submit Revision
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
</x-app-layout>
