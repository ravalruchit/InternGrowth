<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Premium Motivation Reputation Card -->
        <x-reputation-card />

        <div class="bg-gradient-to-br from-white via-purple-50 to-indigo-50 rounded-2xl shadow-2xl p-8 border border-purple-100 animate-scale-in">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Edit Profile</h1>
            </div>
            
            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                @csrf
                
                <div class="relative group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Full Name</span>
                    </label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 bg-white/80 backdrop-blur-sm" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Email Address</span>
                    </label>
                    <input type="email" value="{{ auth()->user()->email }}" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 cursor-not-allowed" 
                           disabled>
                    <p class="text-sm text-gray-500 mt-2 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Email cannot be changed</span>
                    </p>
                </div>
                
                <div class="relative group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        <span>Bio</span>
                    </label>
                    <textarea name="bio" rows="4" 
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 bg-white/80 backdrop-blur-sm resize-none">{{ $profile->bio }}</textarea>
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <span>Skills</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($skills as $skill)
                            <label class="relative flex items-center p-3 bg-white/80 backdrop-blur-sm border-2 border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:shadow-md transition-all duration-300 group">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                    {{ $profile->skills->contains($skill->id) ? 'checked' : '' }} 
                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $skill->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Portfolio Visibility & Talent Profile URL -->
                @if($profile->portfolio)
                    <div class="relative bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl p-5">
                        <h3 class="text-sm font-bold text-indigo-900 mb-3 flex items-center gap-2">
                            📂 Public Talent Profile Settings
                        </h3>

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label for="is_public" class="text-sm font-semibold text-gray-700">Portfolio Visibility</label>
                                <p class="text-xs text-gray-500 mt-0.5">When enabled, your talent profile is publicly accessible via URL.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_public" id="is_public" value="1"
                                    {{ $profile->portfolio->is_public ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white/80 border border-indigo-100 rounded-lg p-3">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Your Talent Profile URL</p>
                                <p class="text-xs text-indigo-600 font-mono mt-1">{{ url('/talent/' . $profile->portfolio->custom_slug) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('talent.profile', $profile->portfolio->custom_slug) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                                    Preview →
                                </a>
                                <x-share-profile-button :url="route('talent.profile', $profile->portfolio->custom_slug)" />
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Update Profile</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-4 border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
