<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-black font-poppins bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Welcome! 🎉
        </h2>
        <p class="text-gray-600 text-sm font-medium">Choose your account type to continue</p>
    </div>

    <form method="POST" action="{{ route('auth.google.complete') }}" class="space-y-4">
        @csrf
        
        <div class="space-y-3">
            <label class="block">
                <input type="radio" name="role" value="student" class="sr-only peer" required>
                <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🎓</div>
                        <div>
                            <div class="font-bold text-gray-900">Student</div>
                            <div class="text-sm text-gray-600">Looking for opportunities</div>
                        </div>
                    </div>
                </div>
            </label>
            
            <label class="block">
                <input type="radio" name="role" value="startup" class="sr-only peer" required>
                <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🚀</div>
                        <div>
                            <div class="font-bold text-gray-900">Startup</div>
                            <div class="text-sm text-gray-600">Looking for talent</div>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="submit" class="group relative w-full py-3 rounded-xl text-white font-bold overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl mt-6">
            <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 animate-gradient bg-[length:200%_200%]"></span>
            <span class="relative">Continue</span>
        </button>
    </form>
</x-guest-layout>
