<x-app-layout>
    <div class="ig-container py-12">
        
        <div class="mb-8 ig-anim-fade-up">
            <p class="ig-eyebrow mb-3">— Academic Proof</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Email <span class="ig-serif text-[var(--ig-accent)]">Verification.</span>
            </h1>
        </div>

        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-6 text-sm">
                <p class="font-bold text-emerald-950">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="ig-banner mb-6 p-4 bg-red-50 border-red-200 text-sm text-red-900 font-bold">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($profile->is_verified)
            <div class="ig-banner ig-banner-success p-8 text-center flex flex-col items-center justify-center space-y-4 ig-anim-scale-in">
                <div class="w-16 h-16 rounded-full bg-[var(--ig-lime)] flex items-center justify-center text-[var(--ig-ink)] text-2xl font-bold shadow-md">
                    ✓
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[var(--ig-ink)]">Email Verified!</h2>
                    <p class="text-sm font-bold text-emerald-800 mt-2">{{ $profile->college_email }}</p>
                    <p class="text-xs text-emerald-700 mt-1">{{ $profile->college_name }}</p>
                    <p class="text-[10px] text-[var(--ig-muted)] mt-2">Verified on: {{ $profile->email_verified_at->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('student.dashboard') }}" class="ig-btn ig-btn-primary">
                    <span>Go to Dashboard</span><span class="arrow">→</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Form block --}}
                <div class="lg:col-span-8 ig-card p-6 sm:p-8">
                    <form method="POST" action="{{ route('student.verification.send') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                College Email Address
                            </label>
                            <input 
                                type="email" 
                                name="college_email" 
                                value="{{ old('college_email', $profile->college_email) }}"
                                required
                                placeholder="your.name@college.edu"
                                class="ig-input"
                            >
                            <p class="text-[11px] text-[var(--ig-muted)] mt-1.5">Must end with a valid academic domain (.edu, .ac.in, etc.)</p>
                            @error('college_email')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                College/University Name
                            </label>
                            <input 
                                type="text" 
                                name="college_name" 
                                value="{{ old('college_name', $profile->college_name) }}"
                                required
                                placeholder="e.g., Stanford University, MIT"
                                class="ig-input"
                            >
                            @error('college_name')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="ig-banner ig-banner-warn text-xs">
                            <div>
                                <p class="font-semibold text-amber-955 mb-1">📬 OTP Verification</p>
                                <p class="text-amber-900 leading-relaxed">A one-time-passcode link will be shared to the address. Make sure to check your spam directory if it doesn't arrive within 5 minutes.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-2">
                            <button type="submit" class="ig-btn ig-btn-primary flex-1 justify-center">
                                Send Verification Email
                            </button>
                            <a href="{{ route('student.dashboard') }}" class="ig-btn ig-btn-ghost">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Side info panel --}}
                <div class="lg:col-span-4 space-y-4">
                    <div class="ig-card p-6">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Verification Perks</h3>
                        <ul class="space-y-3">
                            <li class="text-xs text-[var(--ig-ink-2)] flex items-start gap-2">
                                <span class="text-[var(--ig-accent)]">•</span>
                                <span>Gain access to all active marketplace listings without limits.</span>
                            </li>
                            <li class="text-xs text-[var(--ig-ink-2)] flex items-start gap-2">
                                <span class="text-[var(--ig-accent)]">•</span>
                                <span>Verify academic credibility to earn founder trust.</span>
                            </li>
                            <li class="text-xs text-[var(--ig-ink-2)] flex items-start gap-2">
                                <span class="text-[var(--ig-accent)]">•</span>
                                <span>Increases ranking in AI candidate recommendation pools.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Alternate upload route -->
                    <div class="ig-card p-6 bg-[var(--ig-bg-2)] border-[var(--ig-line)] text-center">
                        <p class="text-xs text-[var(--ig-muted)] mb-3">No college email address?</p>
                        <a href="{{ route('student.verify-id') }}" class="ig-btn ig-btn-ghost w-full justify-center text-xs">
                            📷 Upload ID & AI Verify
                        </a>
                    </div>
                </div>

            </div>
        @endif
    </div>
</x-app-layout>
