<x-app-layout>
    <div class="ig-container py-12 max-w-4xl">
        <div class="mb-8 ig-anim-fade-up">
            <p class="ig-eyebrow mb-3">— Settings</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Edit Startup <span class="ig-serif text-[var(--ig-accent)]">Profile.</span>
            </h1>
        </div>

        <div class="ig-card p-6 sm:p-8 ig-anim-scale-in">
            <form method="POST" action="{{ route('startup.profile.update') }}" class="space-y-6">
                @csrf
                
                {{-- Company Name --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Company Name</label>
                    <input type="text" name="company_name" value="{{ $profile->company_name }}" required
                           class="ig-input" placeholder="e.g., Acme Technologies Inc.">
                    @error('company_name')
                        <p class="text-[var(--ig-rose)] text-xs mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Official Email Address</label>
                    <input type="email" value="{{ auth()->user()->email }}" 
                           class="ig-input bg-[var(--ig-bg-2)] cursor-not-allowed opacity-80" disabled>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5 flex items-center gap-1">
                        🛡️ Account email cannot be changed.
                    </p>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Company Description</label>
                    <textarea name="description" rows="5" 
                              class="ig-input resize-none"
                              placeholder="Describe your startup mission, product, industry focus, and what makes your workspace unique...">{{ $profile->description }}</textarea>
                    @error('description')
                        <p class="text-[var(--ig-rose)] text-xs mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Website --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Company Website</label>
                    <input type="url" name="website" value="{{ $profile->website }}" 
                           class="ig-input" placeholder="https://yourcompany.com">
                    @error('website')
                        <p class="text-[var(--ig-rose)] text-xs mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" class="ig-btn ig-btn-primary flex-1 justify-center">
                        <span>Update Profile</span><span class="arrow">→</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
