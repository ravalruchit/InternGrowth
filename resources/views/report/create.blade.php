<x-app-layout>
    <div class="ig-container max-w-3xl py-12 space-y-8 ig-anim-fade-up">
        
        <!-- Form Card Container -->
        <div class="ig-card p-6 sm:p-8 bg-white border border-[var(--ig-line)]">
            <div class="flex items-center space-x-4 mb-8">
                <div class="bg-[var(--ig-accent-soft)] p-3 rounded-2xl flex-shrink-0">
                    <svg class="w-6 h-6 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="ig-eyebrow mb-1">— Safety & Trust</p>
                    <h1 class="ig-display text-3xl text-[var(--ig-ink)]">
                        Report an <span class="ig-serif text-[var(--ig-rose)]">Issue.</span>
                    </h1>
                </div>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div class="ig-banner ig-banner-success mb-6">
                    <span class="text-lg">✓</span>
                    <div>
                        <p class="text-sm font-semibold text-[var(--ig-ink)]">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="ig-banner ig-banner-warn mb-6">
                    <span class="text-lg">⚠️</span>
                    <div>
                        <p class="text-sm font-semibold text-[var(--ig-ink)]">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('report.store') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="reported_id" value="{{ $id }}">

                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                        Report Type <span class="text-[var(--ig-rose)]">*</span>
                    </label>
                    <select name="report_type" required class="ig-input">
                        <option value="">Select type...</option>
                        <option value="user" {{ $type === 'user' ? 'selected' : '' }}>Report a User</option>
                        <option value="task" {{ $type === 'task' ? 'selected' : '' }}>Report a Task</option>
                        <option value="submission" {{ $type === 'submission' ? 'selected' : '' }}>Report a Submission</option>
                        <option value="other">Other Issue</option>
                    </select>
                    @error('report_type')
                        <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                        Subject <span class="text-[var(--ig-rose)]">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="subject" 
                        required 
                        maxlength="255"
                        placeholder="Brief summary of the issue..."
                        class="ig-input"
                        value="{{ old('subject') }}"
                    >
                    @error('subject')
                        <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                        Detailed Description <span class="text-[var(--ig-rose)]">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        required 
                        rows="6"
                        minlength="10"
                        placeholder="Please provide clear facts or logs about this issue..."
                        class="ig-input resize-none"
                    >{{ old('description') }}</textarea>
                    <p class="text-[10px] text-[var(--ig-muted)] mt-1.5 font-mono">Minimum 10 characters required</p>
                    @error('description')
                        <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guidance Info Box -->
                <div class="ig-banner ig-banner-warn bg-[var(--ig-bg-2)] border-[var(--ig-line-2)] rounded-xl p-4">
                    <span class="text-lg">💡</span>
                    <div>
                        <p class="text-sm font-bold text-[var(--ig-ink)]">Important Information</p>
                        <ul class="text-xs text-[var(--ig-muted)] mt-2 space-y-1.5 leading-relaxed font-medium">
                            <li>• Your report is directly escalated to the platform administration.</li>
                            <li>• Review processes generally take 24–48 operating hours.</li>
                            <li>• Submitting false reports with intent to harm others may lead to account reviews.</li>
                            <li>• All reporter identities are strictly confidential.</li>
                        </ul>
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit" class="ig-btn ig-btn-accent flex-1 justify-center">
                        Submit Report
                    </button>
                    <a href="{{ url()->previous() }}" class="ig-btn ig-btn-ghost justify-center text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Direct Support Desk -->
        <div class="ig-card p-6 bg-[var(--ig-bg-2)] border-[var(--ig-line)]">
            <h3 class="font-bold text-[var(--ig-ink)] mb-1">Need Urgent Help?</h3>
            <p class="text-xs text-[var(--ig-muted)] mb-3">For sensitive details, payment issues, or account safety questions, contact support directly:</p>
            <a href="mailto:ravalruchit@gmail.com" class="text-[var(--ig-azure)] hover:underline font-mono text-sm font-semibold select-all">
                ravalruchit@gmail.com
            </a>
        </div>
    </div>
</x-app-layout>
