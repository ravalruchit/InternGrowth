<x-guest-layout>
    <p class="ig-eyebrow mb-3">— Welcome back</p>
    <h1 class="ig-display text-4xl md:text-5xl mb-3">Sign in.</h1>
    <p class="text-[var(--ig-muted)] mb-8 text-sm">Pick up where you left off — your tasks, scorecard, and offers are waiting.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(!empty(config('services.google.client_id')))
        <div class="mb-6 space-y-4">
            <a href="{{ route('auth.google') }}" class="ig-btn ig-btn-accent w-full justify-center flex items-center gap-2" style="height: 48px;">
                <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Continue with Google</span>
            </a>
            <div class="relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[var(--ig-line-2)]"></div></div>
                <div class="relative flex justify-center"><span class="px-3 ig-mono text-[10px] text-[var(--ig-muted)] bg-[var(--ig-bg)] uppercase tracking-widest">or continue with email</span></div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold mb-2">Email</label>
            <input id="email" name="email" type="email" required autofocus autocomplete="username"
                   value="{{ old('email') }}" placeholder="you@college.edu"
                   class="ig-input" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-semibold">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[12px] text-[var(--ig-muted)] hover:text-[var(--ig-accent)] font-medium">Forgot?</a>
                @endif
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   placeholder="••••••••" class="ig-input" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-[var(--ig-line-2)] text-[var(--ig-ink)] w-4 h-4">
            <span class="text-sm text-[var(--ig-ink-2)]">Keep me signed in</span>
        </label>

        <button type="submit" class="ig-btn ig-btn-primary w-full justify-center mt-2">
            <span>Sign in</span>
            <span class="arrow">→</span>
        </button>

        <p class="text-center text-sm text-[var(--ig-muted)] pt-2">
            New to InternGrowth? <a href="{{ route('register') }}" class="font-semibold text-[var(--ig-ink)] hover:text-[var(--ig-accent)]">Create an account →</a>
        </p>
    </form>
</x-guest-layout>
