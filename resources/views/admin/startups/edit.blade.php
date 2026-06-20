<x-app-layout>
    <div class="ig-container max-w-4xl py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Database</p>
                <h1 class="ig-display text-5xl md:text-6xl leading-[0.95]">
                    Edit <span class="ig-serif text-[var(--ig-accent)]">Startup.</span><br>
                    Update company info.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.startups') }}" class="ig-btn ig-btn-ghost">
                    <span>← Back</span>
                </a>
            </div>
        </div>

        <div class="ig-card p-8 bg-white ig-reveal is-in">
            <form action="{{ route('admin.startups.update', $startup->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Contact Name -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Contact Name *</label>
                        <input type="text" name="name" value="{{ old('name', $startup->name) }}" 
                               class="ig-input" required>
                        @error('name')
                            <p class="text-red-600 text-xs mt-1.5 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $startup->email) }}" 
                               class="ig-input" required>
                        @error('email')
                            <p class="text-red-600 text-xs mt-1.5 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $startup->startupProfile->company_name ?? '') }}" 
                               class="ig-input">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Description</label>
                        <textarea name="description" rows="4" 
                                  class="ig-input">{{ old('description', $startup->startupProfile->description ?? '') }}</textarea>
                    </div>

                    <!-- Industry -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry', $startup->startupProfile->industry ?? '') }}" 
                               class="ig-input">
                    </div>

                    <!-- Verification Status -->
                    <div class="flex items-center space-x-3 p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl">
                        <input type="checkbox" name="is_verified" id="is_verified" 
                               {{ old('is_verified', $startup->startupProfile->is_verified ?? false) ? 'checked' : '' }}
                               class="w-5 h-5 text-[var(--ig-accent)] border-[var(--ig-line-2)] rounded focus:ring-[var(--ig-accent)]">
                        <label for="is_verified" class="text-sm font-semibold text-[var(--ig-ink)] cursor-pointer select-none">Verified Startup</label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between mt-10 pt-6 border-t border-[var(--ig-line)]">
                    <a href="{{ route('admin.startups') }}" class="ig-btn ig-btn-ghost">
                        <span>Cancel</span>
                    </a>
                    <button type="submit" class="ig-btn ig-btn-primary">
                        <span>Update Startup</span>
                        <span class="arrow">→</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
