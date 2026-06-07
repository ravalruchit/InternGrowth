<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white/80 backdrop-blur-lg border border-purple-100 shadow-2xl rounded-3xl p-8 md:p-12 relative overflow-hidden transition-all duration-300">
            <!-- Decorative Glow Accents -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-purple-300/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-300/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Header -->
                <div class="mb-10 text-center md:text-left">
                    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 mb-3 font-poppins">
                        Startup Verification ⭐
                    </h1>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Verify your company credentials to unlock task posting, candidate discovery, and direct internship/job offers.
                    </p>
                </div>

                <!-- Status Banners -->
                @if($profile->verification_status === 'pending' && $profile->verification_submitted_at)
                    <div class="mb-8 p-6 bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 rounded-2xl shadow-md flex items-start space-x-4 animate-pulse-slow">
                        <div class="flex-shrink-0 bg-yellow-500 text-white rounded-full p-2.5 shadow-md">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-yellow-800">Verification Request Under Review</h3>
                            <p class="text-yellow-700 text-sm mt-1">
                                We've received your company documents and are checking them. Submitted on <strong>{{ $profile->verification_submitted_at->format('M d, Y H:i') }}</strong>.
                            </p>
                        </div>
                    </div>
                @elseif($profile->verification_status === 'rejected')
                    <div class="mb-8 p-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-2xl shadow-md flex items-start space-x-4">
                        <div class="flex-shrink-0 bg-red-500 text-white rounded-full p-2.5 shadow-md">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-red-800">Verification Rejected</h3>
                            @if($profile->verification_notes)
                                <div class="mt-2 p-3 bg-red-100/50 rounded-xl border border-red-200">
                                    <p class="text-red-700 text-sm font-semibold">Feedback reason:</p>
                                    <p class="text-red-800 text-sm italic mt-1">"{{ $profile->verification_notes }}"</p>
                                </div>
                            @endif
                            <p class="text-red-700 text-sm mt-3 font-medium">
                                Please correct the details or upload higher-quality documents, then resubmit the form below.
                            </p>
                        </div>
                    </div>
                @elseif($profile->is_verified)
                    <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-2xl shadow-md flex items-start space-x-4">
                        <div class="flex-shrink-0 bg-green-500 text-white rounded-full p-2.5 shadow-md">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-green-800">Verified Account</h3>
                            <p class="text-green-700 text-sm mt-1">
                                Your startup is verified! You have full access to create tasks, find students, and send hiring offers.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('startup.verification.submit') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Registration Number -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Company Registration / Incorporation Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="company_registration_number" 
                                   value="{{ old('company_registration_number', $profile->company_registration_number) }}" 
                                   required
                                   class="w-full px-4 py-3 border border-purple-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                                   placeholder="e.g. U12345AB2020PTC123456">
                            @error('company_registration_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GST Number -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                GSTIN (Optional)
                            </label>
                            <input type="text" name="gst_number" 
                                   value="{{ old('gst_number', $profile->gst_number) }}"
                                   class="w-full px-4 py-3 border border-purple-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                                   placeholder="e.g. 22AAAAA0000A1Z5">
                            @error('gst_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Official Contact Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="contact_phone" 
                               value="{{ old('contact_phone', $profile->contact_phone) }}" 
                               required
                               class="w-full px-4 py-3 border border-purple-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                               placeholder="e.g. +91 98765 43210">
                        @error('contact_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Address -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Registered Office Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="company_address" rows="3" required
                                  class="w-full px-4 py-3 border border-purple-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                                  placeholder="Enter the full address as registered in official documents">{{ old('company_address', $profile->company_address) }}</textarea>
                        @error('company_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Upload Zone -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Upload Verification Documents <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative border-2 border-dashed border-purple-200 rounded-2xl p-8 text-center hover:border-indigo-400 transition-all duration-300 bg-purple-50/20 group">
                            <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" id="documents"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="bg-indigo-50 text-indigo-600 p-4 rounded-full group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-gray-800">
                                    Drag and drop files here, or <span class="text-indigo-600 hover:text-indigo-850 underline">browse</span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Accepted formats: PDF, JPG, PNG (Max 5MB per file)
                                </p>
                            </div>
                        </div>
                        @error('documents')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Selected Files Preview Container (JS dynamically shows file names here) -->
                        <div id="file-list-preview" class="mt-3 hidden space-y-2">
                            <p class="text-xs font-semibold text-gray-500">Selected for upload:</p>
                            <div id="selected-files" class="space-y-1"></div>
                        </div>
                    </div>

                    <!-- Previously Uploaded -->
                    @if($profile->verification_documents && count($profile->verification_documents) > 0)
                        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 shadow-inner">
                            <h3 class="font-bold text-gray-800 text-sm mb-3">Previously Uploaded Documents:</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($profile->verification_documents as $doc)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 shadow-sm text-sm">
                                        <div class="flex items-center space-x-2 truncate">
                                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-gray-700 truncate font-medium" title="{{ $doc['name'] }}">{{ $doc['name'] }}</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="text-indigo-650 hover:text-indigo-850 font-semibold text-xs flex items-center space-x-0.5">
                                            <span>View</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-3 italic">⚠️ Note: Submitting new documents will replace the existing set.</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-lg hover:shadow-indigo-150 text-white px-8 py-3.5 rounded-xl hover:from-indigo-700 hover:to-purple-700 font-bold transition duration-300 text-center">
                            Submit Verification Request
                        </button>
                        <a href="{{ route('startup.dashboard') }}" class="bg-gray-100 hover:bg-gray-250 text-gray-700 px-8 py-3.5 rounded-xl font-bold transition duration-300 text-center">
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script to dynamically update file inputs preview -->
    <script>
        document.getElementById('documents').addEventListener('change', function(e) {
            const list = document.getElementById('selected-files');
            const preview = document.getElementById('file-list-preview');
            list.innerHTML = '';
            
            if (this.files.length > 0) {
                preview.classList.remove('hidden');
                Array.from(this.files).forEach(file => {
                    const sizeKB = (file.size / 1024).toFixed(1);
                    const div = document.createElement('div');
                    div.className = 'flex items-center space-x-2 text-xs text-gray-700 bg-indigo-50/50 p-2 rounded-lg border border-indigo-100/30';
                    div.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="font-medium truncate">${file.name}</span>
                        <span class="text-gray-400 font-mono">(${sizeKB} KB)</span>
                    `;
                    list.appendChild(div);
                });
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
