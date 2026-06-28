<x-app-layout>
    <div class="ig-container py-12">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost text-xs">
                ← Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start ig-anim-fade-up">
            <!-- Form Input (Left) -->
            <div class="lg:col-span-8 space-y-8">
                <div class="mb-2">
                    <p class="ig-eyebrow mb-3">— Revision</p>
                    <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                        Revise Your <span class="ig-serif text-[var(--ig-accent)]">Work.</span>
                    </h1>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5 font-semibold">Task: {{ $submission->application->task->title }}</p>
                </div>

                <!-- Revision Feedback from Founder -->
                <div class="p-5 bg-[var(--ig-accent-soft)] border-l-4 border-[var(--ig-accent)] rounded-2xl shadow-sm">
                    <h3 class="font-bold text-sm text-[var(--ig-accent)] mb-2 flex items-center">
                        <span class="text-base mr-2">🔄</span>
                        Revision Feedback from Startup:
                    </h3>
                    <p class="text-xs text-[var(--ig-ink-2)] leading-relaxed font-semibold">{{ $submission->feedback }}</p>
                </div>

                <div class="ig-card p-6 sm:p-8">
                    <form method="POST" action="{{ route('submissions.update', $submission->id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Task Description -->
                        <div class="p-4 bg-[var(--ig-bg-2)]/20 border border-[var(--ig-line-2)] rounded-xl">
                            <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-2">Assignment Details:</h3>
                            <p class="text-xs text-[var(--ig-muted)] leading-relaxed whitespace-pre-wrap">{{ $submission->application->task->description }}</p>
                        </div>

                        <!-- Previous Submission Summary -->
                        <div class="p-4 bg-[var(--ig-bg-2)]/30 border border-[var(--ig-line-2)] rounded-xl space-y-3">
                            <h3 class="font-bold text-xs text-[var(--ig-muted)] uppercase tracking-wider">Your Previous Submission</h3>
                            <p class="text-xs text-[var(--ig-ink-2)] leading-relaxed whitespace-pre-wrap">{{ $submission->content }}</p>
                            
                            @if($submission->files && is_array($submission->files) && count($submission->files) > 0)
                                <div class="pt-3 border-t border-[var(--ig-line)]">
                                    <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Previously Uploaded Files:</p>
                                    <div class="space-y-1.5">
                                        @foreach($submission->files as $file)
                                            @if(is_array($file) && isset($file['name']))
                                                <div class="flex items-center space-x-2 text-xs text-[var(--ig-ink-2)] font-semibold">
                                                    <span>📄</span>
                                                    <span>{{ $file['name'] }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Revised Content Input -->
                        <div>
                            <label for="content" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                Revised Work / Description <span class="text-[var(--ig-rose)]">*</span>
                            </label>
                            <textarea 
                                id="content" 
                                name="content" 
                                rows="8" 
                                required
                                class="ig-input resize-none"
                                placeholder="Update your work based on the feedback..."
                            >{{ old('content', $submission->content) }}</textarea>
                            @error('content')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-[var(--ig-muted)] mt-1.5">Address the startup's feedback point-by-point and outline the improvements you made.</p>
                        </div>

                        <!-- Custom File Upload Dropzone -->
                        <div>
                            <label for="files" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                Add More Files (Optional)
                            </label>
                            <div class="relative border-2 border-dashed border-[var(--ig-line-2)] hover:border-[var(--ig-accent)]/50 rounded-2xl p-6 transition-all duration-200 text-center bg-[var(--ig-bg-2)]/10 cursor-pointer" onclick="document.getElementById('files').click()">
                                <input 
                                    type="file" 
                                    id="files" 
                                    name="files[]" 
                                    multiple
                                    accept=".pdf,.doc,.docx,.txt,.zip,.jpg,.jpeg,.png,.gif"
                                    class="hidden"
                                    onchange="displaySelectedFiles(this)"
                                >
                                <div class="space-y-2">
                                    <div class="text-3xl">📤</div>
                                    <p class="text-sm font-bold text-[var(--ig-ink)]">Click to add files</p>
                                    <p class="text-xs text-[var(--ig-muted)]">Upload new files to add or replace previous documents (max 10MB each)</p>
                                </div>
                            </div>
                            <p class="text-xs text-[var(--ig-muted)] mt-1.5">Your previously uploaded files will be kept unless overwritten.</p>
                            <div id="fileList" class="mt-4 space-y-2"></div>
                            @error('files.*')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost text-xs">Cancel</a>
                            <button 
                                type="submit" 
                                class="ig-btn ig-btn-primary px-8 py-3 rounded-lg"
                            >
                                <span>Submit Revision</span><span class="arrow">→</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column Sidebar -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-4">
                <!-- Premium Notice Box -->
                <div class="relative overflow-hidden bg-white border border-[var(--ig-line-2)] rounded-3xl p-6 shadow-sm">
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-[var(--ig-accent)]/5 rounded-full blur-xl"></div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[var(--ig-accent-soft)] flex items-center justify-center text-lg flex-shrink-0">
                            📢
                        </div>
                        <div class="space-y-4 flex-1">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--ig-accent)] font-poppins">Important Notice</h4>
                                <p class="text-[11.5px] text-[var(--ig-muted)] mt-2 leading-relaxed font-medium">
                                    Work completed outside InternGrowth cannot be verified and will not contribute to your <strong>IPRS score</strong>, experience record, or reviews. Keep your submission and hiring journey on the platform to build your profile!
                                </p>
                            </div>
                            <div class="pt-3 border-t border-[var(--ig-line)] flex items-start gap-2 text-[11px] text-[var(--ig-muted)]">
                                <span class="text-xs leading-none">🔒</span>
                                <span class="leading-relaxed font-semibold"><strong>Safe & Secure:</strong> Right-click and downloading is disabled for startups until your submission is accepted and escrow is released.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    fileDiv.className = 'flex items-center space-x-3 text-xs font-semibold text-[var(--ig-ink-2)] bg-[var(--ig-bg-2)]/30 border border-[var(--ig-line)] p-3 rounded-xl transition duration-150';
                    fileDiv.innerHTML = `
                        <span class="text-base flex-shrink-0">📄</span>
                        <span class="flex-1 truncate">${file.name}</span>
                        <span class="text-[10px] text-[var(--ig-muted)] font-mono">${fileSize} KB</span>
                    `;
                    fileList.appendChild(fileDiv);
                }
            }
        }
    </script>
</x-app-layout>
