<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Report an Issue</h1>
                    <p class="text-gray-600 mt-1">Help us maintain a safe and professional community</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('report.store') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="reported_id" value="{{ $id }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Report Type <span class="text-red-500">*</span>
                    </label>
                    <select name="report_type" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select type...</option>
                        <option value="user" {{ $type === 'user' ? 'selected' : '' }}>Report a User</option>
                        <option value="task" {{ $type === 'task' ? 'selected' : '' }}>Report a Task</option>
                        <option value="submission" {{ $type === 'submission' ? 'selected' : '' }}>Report a Submission</option>
                        <option value="other">Other Issue</option>
                    </select>
                    @error('report_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="subject" 
                        required 
                        maxlength="255"
                        placeholder="Brief description of the issue"
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('subject') }}"
                    >
                    @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Detailed Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        required 
                        rows="6"
                        minlength="10"
                        placeholder="Please provide as much detail as possible about the issue..."
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    >{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-yellow-800 font-semibold">Important Information</p>
                            <p class="text-xs text-yellow-700 mt-1">
                                • Your report will be sent to our admin team<br>
                                • We review all reports within 24-48 hours<br>
                                • False reports may result in account suspension<br>
                                • Your identity will be kept confidential
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-semibold transition"
                    >
                        Submit Report
                    </button>
                    <a 
                        href="{{ url()->previous() }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Contact Info -->
        <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
            <h3 class="font-semibold text-gray-900 mb-2">Need Immediate Help?</h3>
            <p class="text-sm text-gray-600 mb-3">For urgent issues, you can also contact us directly:</p>
            <a href="mailto:ravalruchit@gmail.com" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                📧 ravalruchit@gmail.com
            </a>
        </div>
    </div>
</x-app-layout>
