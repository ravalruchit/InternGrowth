<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Enter Verification Code</h1>

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

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-blue-900 font-semibold">Check Your Email</p>
                        <p class="text-blue-800 text-sm mt-1">
                            We sent a 6-digit verification code to:<br>
                            <strong>{{ $profile->college_email }}</strong>
                        </p>
                        <p class="text-blue-700 text-xs mt-2">Check your spam folder if you don't see it.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('student.verification.verify') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Enter 6-Digit Code
                        <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="code" 
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required
                        placeholder="123456"
                        class="w-full text-center text-3xl font-bold tracking-widest border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-4"
                        autofocus
                    >
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2 text-center">Enter the 6-digit code from your email</p>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold"
                    >
                        Verify Code
                    </button>
                    <a 
                        href="{{ route('student.verification') }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold"
                    >
                        Back
                    </a>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600">Didn't receive the code?</p>
                <a href="{{ route('student.verification') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    Request a new code
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
