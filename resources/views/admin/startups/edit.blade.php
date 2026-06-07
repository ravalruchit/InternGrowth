<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Startup</h1>
            <p class="text-gray-600 mt-2">Update startup information</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('admin.startups.update', $startup->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Name</label>
                        <input type="text" name="name" value="{{ old('name', $startup->name) }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition" required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $startup->email) }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition" required>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $startup->startupProfile->company_name ?? '') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">{{ old('description', $startup->startupProfile->description ?? '') }}</textarea>
                    </div>

                    <!-- Industry -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry', $startup->startupProfile->industry ?? '') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                    </div>

                    <!-- Verification Status -->
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                        <input type="checkbox" name="is_verified" id="is_verified" 
                               {{ old('is_verified', $startup->startupProfile->is_verified ?? false) ? 'checked' : '' }}
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_verified" class="text-sm font-semibold text-gray-700">Verified Startup</label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.startups') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-semibold">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition font-semibold">
                        Update Startup
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
