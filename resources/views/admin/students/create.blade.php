<x-app-layout>
    <div class="ig-container max-w-4xl py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Database</p>
                <h1 class="ig-display text-5xl md:text-6xl leading-[0.95]">
                    Create <span class="ig-serif text-[var(--ig-accent)]">Student.</span><br>
                    Register new talent profile.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.students') }}" class="ig-btn ig-btn-ghost">
                    <span>← Cancel</span>
                </a>
            </div>
        </div>

        <div class="ig-card p-8 bg-white ig-reveal is-in">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="ig-input" required placeholder="Full Name">
                        @error('name')
                            <p class="text-red-600 text-xs mt-1.5 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="ig-input" required placeholder="student@university.edu">
                        @error('email')
                            <p class="text-red-600 text-xs mt-1.5 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Password *</label>
                            <input type="password" name="password" 
                                   class="ig-input" required placeholder="••••••••">
                            @error('password')
                                <p class="text-red-600 text-xs mt-1.5 font-mono">{{ $message }}</p>
                            @enderror
                            <p class="text-[var(--ig-muted)] text-[10px] mt-1.5 font-mono font-semibold">Minimum 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Confirm Password *</label>
                            <input type="password" name="password_confirmation" 
                                   class="ig-input" required placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Bio</label>
                        <textarea name="bio" rows="4" 
                                  class="ig-input" placeholder="A short bio about the student, their aspirations, or skills...">{{ old('bio') }}</textarea>
                    </div>

                    <!-- Education -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Education</label>
                        <textarea name="education" rows="3" 
                                  class="ig-input" placeholder="e.g. BS in Computer Science, Stanford University (Expected 2027)">{{ old('education') }}</textarea>
                    </div>

                    <!-- Experience -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Experience</label>
                        <textarea name="experience" rows="3" 
                                  class="ig-input" placeholder="e.g. Software Engineering Intern at Google (Summer 2025)">{{ old('experience') }}</textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between mt-10 pt-6 border-t border-[var(--ig-line)]">
                    <a href="{{ route('admin.students') }}" class="ig-btn ig-btn-ghost">
                        <span>Cancel</span>
                    </a>
                    <button type="submit" class="ig-btn ig-btn-primary">
                        <span>Create Student</span>
                        <span class="arrow">→</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
