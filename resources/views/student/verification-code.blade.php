<x-app-layout>
    <div class="ig-container py-12 max-w-2xl">
        
        <div class="mb-8 text-center ig-anim-fade-up">
            <p class="ig-eyebrow mb-3">— Academic Proof</p>
            <h1 class="ig-display text-4xl text-[var(--ig-ink)]">
                Enter Code.
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

        <div class="ig-card p-6 sm:p-8 space-y-6">
            <div class="ig-banner ig-banner-warn text-xs">
                <div class="flex items-start gap-3">
                    <span class="text-lg">📬</span>
                    <div>
                        <p class="font-semibold text-amber-955">Check Your Inbox</p>
                        <p class="text-amber-900 mt-1">We shared a 6-digit OTP code to: <strong>{{ $profile->college_email }}</strong></p>
                        <p class="text-amber-800 text-[10px] mt-1 font-medium">Please review spam/promotions directories if it hasn't landed.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('student.verification.verify') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-3 text-center">
                        6-Digit Verification Code
                    </label>
                    <input 
                        type="text" 
                        name="code" 
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required
                        placeholder="123456"
                        class="ig-input ig-mono text-center text-3xl font-black tracking-[0.4em] py-4"
                        autofocus
                    >
                    @error('code')
                        <p class="text-[var(--ig-rose)] text-xs mt-2 text-center font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="ig-btn ig-btn-primary flex-1 justify-center">
                        Verify Code
                    </button>
                    <a href="{{ route('student.verification') }}" class="ig-btn ig-btn-ghost">
                        Back
                    </a>
                </div>
            </form>

            <div class="border-t border-[var(--ig-line)] pt-4 text-center">
                <p class="text-xs text-[var(--ig-muted)]">Didn't receive the code?</p>
                <a href="{{ route('student.verification') }}" class="text-[var(--ig-accent)] font-semibold text-xs hover:underline mt-1 inline-block">
                    Request a new passcode
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
