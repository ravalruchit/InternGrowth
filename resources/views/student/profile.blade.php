<x-app-layout>
    <div class="ig-container space-y-8 ig-anim-fade-up">
        <!-- Compact Reputation Banner (replaces massive full card) -->
        <x-reputation-card :compact="true" />

        <div class="mb-2">
            <p class="ig-eyebrow mb-3">— Settings</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Edit Student <span class="ig-serif text-[var(--ig-accent)]">Profile.</span>
            </h1>
        </div>

        {{-- ─── Profile Settings Card ─── --}}
        <div class="ig-card p-6 sm:p-8" style="transform:none!important;">
            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-8">
                @csrf

                {{-- ─── Section: Personal Info ─── --}}
                <div>
                    <h2 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-5 flex items-center gap-2">
                        <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                        Personal Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" class="ig-input" required>
                            @error('name')
                                <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Email Address</label>
                            <input type="email" value="{{ auth()->user()->email }}" class="ig-input bg-[var(--ig-bg-2)] cursor-not-allowed opacity-80" disabled>
                            <p class="text-xs text-[var(--ig-muted)] mt-1.5 flex items-center gap-1">
                                🛡️ Email address cannot be changed.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Professional Headline</label>
                            <input type="text" name="professional_title" value="{{ old('professional_title', $profile->professional_title) }}" placeholder="e.g. Full Stack Developer" class="ig-input">
                            @error('professional_title')
                                <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $profile->phone_number) }}" placeholder="e.g. +91 98765 43210" class="ig-input">
                            @error('phone_number')
                                <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">City</label>
                            <input type="text" name="city" value="{{ old('city', $profile->city) }}" placeholder="e.g. Ahmedabad" class="ig-input">
                            @error('city')
                                <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">State / Province</label>
                            <input type="text" name="state" value="{{ old('state', $profile->state) }}" placeholder="e.g. Gujarat" class="ig-input">
                            @error('state')
                                <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Country</label>
                            <input type="text" name="country" value="{{ old('country', $profile->country) }}" placeholder="e.g. India" class="ig-input">
                            @error('country')
                                <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Education Section --}}
                    <div class="mt-8 border-t border-[var(--ig-line)] pt-6">
                        <h3 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-5 flex items-center gap-2">
                            <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                            Education Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">College / University Name</label>
                                <input type="text" name="college_name" value="{{ old('college_name', $profile->college_name) }}" placeholder="e.g. LJ University" class="ig-input">
                                @error('college_name')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Degree & Course</label>
                                <input type="text" name="degree_name" value="{{ old('degree_name', $profile->degree_name) }}" placeholder="e.g. Integrated B.Sc + M.Sc (IT)" class="ig-input">
                                @error('degree_name')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Expected/Actual Graduation Year</label>
                                <input type="number" name="graduation_year" value="{{ old('graduation_year', $profile->graduation_year) }}" placeholder="e.g. 2029" class="ig-input">
                                @error('graduation_year')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">CGPA / Score (Optional)</label>
                                <input type="number" step="0.01" name="cgpa" value="{{ old('cgpa', $profile->cgpa) }}" placeholder="e.g. 8.7" class="ig-input">
                                @error('cgpa')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Social Profile Links Section --}}
                    <div class="mt-8 border-t border-[var(--ig-line)] pt-6">
                        <h3 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-5 flex items-center gap-2">
                            <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                            Social & Professional Profiles
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">GitHub URL</label>
                                <input type="url" name="github_url" value="{{ old('github_url', $profile->github_url) }}" placeholder="https://github.com/username" class="ig-input font-mono text-sm">
                                @error('github_url')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url) }}" placeholder="https://linkedin.com/in/username" class="ig-input font-mono text-sm">
                                @error('linkedin_url')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Personal Portfolio URL</label>
                                <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url) }}" placeholder="https://username.dev" class="ig-input font-mono text-sm">
                                @error('portfolio_url')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">LeetCode URL</label>
                                <input type="url" name="leetcode_url" value="{{ old('leetcode_url', $profile->leetcode_url) }}" placeholder="https://leetcode.com/username" class="ig-input font-mono text-sm">
                                @error('leetcode_url')
                                    <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Bio</label>
                    <textarea name="bio" rows="4"
                              class="ig-input resize-none"
                              placeholder="Introduce yourself to founders, highlight your interests and background...">{{ $profile->bio }}</textarea>
                </div>

                {{-- Hidden selects for form submission compatibility --}}
                <select id="primary_domain" name="primary_domain" class="hidden">
                    <option value="">Select Domain</option>
                    @foreach(\App\Models\StudentProfile::$domains as $domain => $roles)
                        <option value="{{ $domain }}" {{ (old('primary_domain', $profile->primary_domain) == $domain) ? 'selected' : '' }}>{{ $domain }}</option>
                    @endforeach
                </select>
                <select id="preferred_role" name="preferred_role" class="hidden">
                    <option value="">Select Role</option>
                    @if($profile->primary_domain && isset(\App\Models\StudentProfile::$domains[$profile->primary_domain]))
                        @foreach(\App\Models\StudentProfile::$domains[$profile->primary_domain] as $role)
                            <option value="{{ $role }}" {{ (old('preferred_role', $profile->preferred_role) == $role) ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    @endif
                </select>

                {{-- ─── Section: Domain & Role ─── --}}
                <div class="pt-2">
                    <h2 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-5 flex items-center gap-2">
                        <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                        Domain & Role
                    </h2>

                    {{-- Domain Selection Cards --}}
                    <div>
                        <label class="block text-xs font-semibold text-[var(--ig-ink)] mb-3">Choose your primary domain</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="domain-cards-container">
                            @php
                                $domainDetails = [
                                    'Software Development' => ['icon' => '💻', 'desc' => 'Web apps, mobile apps, backend systems, and clean code.'],
                                    'UI/UX Design' => ['icon' => '🎨', 'desc' => 'Interfaces, wireframes, prototype flows, design systems.'],
                                    'Digital Marketing' => ['icon' => '📈', 'desc' => 'Drive traffic, manage ads, SEO, and copywriting.'],
                                    'Data & AI' => ['icon' => '🤖', 'desc' => 'AI models, data analysis, ML, and key insights.'],
                                    'Content & Business' => ['icon' => '📋', 'desc' => 'Content writing, strategy, market research.']
                                ];
                            @endphp
                            @foreach($domainDetails as $domName => $info)
                                <div data-domain="{{ $domName }}" class="domain-card cursor-pointer p-4 bg-white border-2 border-[var(--ig-line)] rounded-2xl hover:shadow-md hover:border-[var(--ig-accent)]/50 active:scale-[0.97] transition-all duration-200 flex items-start gap-3 group">
                                    <div class="text-2xl flex-shrink-0 mt-0.5 transition-transform duration-200 group-hover:scale-110">{{ $info['icon'] }}</div>
                                    <div>
                                        <h4 class="font-bold text-sm text-[var(--ig-ink)] leading-tight">{{ $domName }}</h4>
                                        <p class="text-[11px] text-[var(--ig-muted)] leading-relaxed mt-0.5">{{ $info['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('primary_domain')
                            <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role Selection Cards --}}
                    <div id="role-section-wrapper" class="mt-5" style="display: none;">
                        <label class="block text-xs font-semibold text-[var(--ig-ink)] mb-3">Choose your preferred role</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2" id="role-cards-container">
                            <!-- Dynamic role cards populated in JS -->
                        </div>
                        @error('preferred_role')
                            <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ─── Section: Skills Matrix ─── --}}
                <div class="pt-2">
                    <h2 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-5 flex items-center gap-2">
                        <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                        Skills Matrix
                    </h2>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                        <input type="text" id="skill-search" placeholder="🔍 Search skills across all domains..." class="ig-input max-w-md text-sm" />
                        <span id="skill-selected-info" class="ig-mono text-xs font-semibold text-[var(--ig-accent)] whitespace-nowrap">0 / 10 Selected</span>
                    </div>

                    {{-- Hidden checkbox inputs for standard submission --}}
                    <div class="hidden">
                        @foreach($skills as $skill)
                            <input type="checkbox" name="skills[]" id="skill-checkbox-{{ $skill->id }}" value="{{ $skill->id }}"
                                   {{ $profile->skills->contains($skill->id) ? 'checked' : '' }}
                                   class="skill-checkbox-hidden">
                        @endforeach
                    </div>

                    {{-- Chips Grid --}}
                    <div class="flex flex-wrap gap-2" id="skills-chips-container">
                        @foreach($skills as $skill)
                            <div data-skill-id="{{ $skill->id }}" data-domain="{{ $skill->domain }}" data-name="{{ strtolower($skill->name) }}"
                                 class="skill-chip cursor-pointer px-3.5 py-1.5 bg-white border border-[var(--ig-line)] rounded-xl text-xs font-semibold text-[var(--ig-ink-2)] hover:border-[var(--ig-accent)]/60 hover:scale-105 active:scale-95 transition-all duration-200 select-none flex items-center gap-1.5">
                                <span class="status-icon text-[var(--ig-accent)] font-bold hidden">✓</span>
                                <span>{{ $skill->name }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-[var(--ig-muted)] mt-2 italic">Tip: Select your domain above to see relevant skills. Use the search bar to find skills across all domains.</p>
                </div>

                <!-- Portfolio Visibility & Talent Profile URL -->
                @if($profile->portfolio)
                    <div class="pt-2">
                        <h2 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-5 flex items-center gap-2">
                            <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                            Public Talent Profile
                        </h2>
                        <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-2xl p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="is_public" class="text-sm font-semibold text-[var(--ig-ink)]">Portfolio Visibility</label>
                                    <p class="text-xs text-[var(--ig-muted)] mt-0.5">When enabled, your talent profile is publicly accessible via URL.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="is_public" id="is_public" value="1"
                                        {{ $profile->portfolio->is_public ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-[var(--ig-line-2)] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--ig-accent)]"></div>
                                </label>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white border border-[var(--ig-line)] rounded-xl p-4">
                                <div>
                                    <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Your Talent Profile URL</p>
                                    <p class="text-xs text-[var(--ig-azure)] font-mono mt-1 select-all">{{ url('/talent/' . $profile->portfolio->custom_slug) }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('talent.profile', $profile->portfolio->custom_slug) }}" target="_blank" class="text-xs font-bold text-[var(--ig-ink)] hover:text-[var(--ig-accent)] transition">
                                        Preview →
                                    </a>
                                    <x-share-profile-button :url="route('talent.profile', $profile->portfolio->custom_slug)" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ─── Action Buttons ─── --}}
                <div class="flex items-center gap-4 pt-4 border-t border-[var(--ig-line)]">
                    <button type="submit" class="ig-btn ig-btn-primary flex-1 sm:flex-none sm:min-w-[220px] justify-center">
                        <span>Update Profile</span><span class="arrow">→</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- ─── Portfolio Projects Card ─── --}}
        <div class="ig-card p-6 sm:p-8 space-y-6" style="transform:none!important;">
            <div>
                <h2 class="text-sm font-black uppercase tracking-wider text-[var(--ig-muted)] mb-2 flex items-center gap-2">
                    <span class="w-5 h-[2px] bg-[var(--ig-accent)] rounded-full"></span>
                    Portfolio Proof-of-Work
                </h2>
                <h3 class="text-xl font-bold text-[var(--ig-ink)]">
                    Manage Portfolio Projects
                </h3>
                <p class="text-xs text-[var(--ig-muted)] mt-1 max-w-2xl leading-relaxed">
                    Your self-declared skills are only counted for task matching if backed by a verified platform task or a portfolio project with evidence links (GitHub or Demo URL).
                </p>
            </div>

            <!-- List of current projects -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[var(--ig-ink-2)]">Your Projects</h4>
                @if($profile->portfolio && $profile->portfolio->items->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($profile->portfolio->items as $item)
                            <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-2xl flex flex-col justify-between space-y-3 hover:border-[var(--ig-line-2)] transition-colors duration-200">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="font-bold text-sm text-[var(--ig-ink)]">{{ $item->project_title }}</h4>
                                        @if($item->task_id)
                                            <span class="px-2 py-0.5 bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] text-[10px] font-bold rounded-md flex items-center gap-1 flex-shrink-0">
                                                🏆 Platform Task
                                            </span>
                                        @else
                                            <form action="{{ route('student.portfolio.project.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this project?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-[var(--ig-rose)] hover:text-red-700 transition flex-shrink-0">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="text-xs text-[var(--ig-muted)] mt-1.5 line-clamp-2">
                                        {{ $item->auto_summary }}
                                    </p>
                                    @if(!empty($item->skills_demonstrated))
                                        <div class="flex flex-wrap gap-1 mt-3">
                                            @php
                                                $skillsDemonstrated = is_array($item->skills_demonstrated) 
                                                    ? $item->skills_demonstrated 
                                                    : json_decode($item->skills_demonstrated, true);
                                            @endphp
                                            @if(is_array($skillsDemonstrated))
                                                @foreach($skillsDemonstrated as $skillName)
                                                    <span class="px-2 py-0.5 bg-white border border-[var(--ig-line)] text-[10px] font-semibold text-[var(--ig-ink-2)] rounded-md">
                                                        {{ $skillName }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 pt-2 border-t border-[var(--ig-line)]/50">
                                    @if($item->github_url)
                                        <a href="{{ $item->github_url }}" target="_blank" class="text-[11px] font-bold text-[var(--ig-accent)] hover:underline flex items-center gap-1">
                                            🔗 GitHub
                                        </a>
                                    @endif
                                    @if($item->demo_url)
                                        <a href="{{ $item->demo_url }}" target="_blank" class="text-[11px] font-bold text-[var(--ig-accent)] hover:underline flex items-center gap-1">
                                            🌐 Live Demo
                                        </a>
                                    @endif
                                    @if(!$item->github_url && !$item->demo_url)
                                        <span class="text-[11px] font-bold text-[var(--ig-muted)]">No evidence links</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center gap-3 px-4 py-5 bg-[var(--ig-bg-2)] border border-dashed border-[var(--ig-line-2)] rounded-2xl">
                        <span class="text-2xl">📭</span>
                        <div>
                            <p class="text-sm font-semibold text-[var(--ig-ink)]">No projects added yet</p>
                            <p class="text-xs text-[var(--ig-muted)] mt-0.5">
                                Complete a platform task or add a personal project below to satisfy the Proof-of-Work Gate.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form to Add Project -->
            <div class="pt-6 border-t border-[var(--ig-line)]">
                <h4 class="text-sm font-bold text-[var(--ig-ink)] mb-4 flex items-center gap-2">
                    <span class="text-base">➕</span>
                    Add Personal / College Project
                </h4>
                <form action="{{ route('student.portfolio.project.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--ig-ink)] mb-1.5">Project Title <span class="text-[var(--ig-rose)]">*</span></label>
                            <input type="text" name="project_title" required class="ig-input text-sm" placeholder="e.g. E-Commerce Backend API">
                            @error('project_title')
                                <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--ig-ink)] mb-1.5">Description</label>
                            <input type="text" name="description" class="ig-input text-sm" placeholder="Briefly describe what you built...">
                            @error('description')
                                <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--ig-ink)] mb-1.5">GitHub Repository Link</label>
                            <input type="url" name="github_url" class="ig-input text-sm font-mono" placeholder="https://github.com/...">
                            @error('github_url')
                                <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--ig-ink)] mb-1.5">Live Demo Link</label>
                            <input type="url" name="demo_url" class="ig-input text-sm font-mono" placeholder="https://my-app.vercel.app">
                            @error('demo_url')
                                <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <div>
                                <label class="block text-xs font-semibold text-[var(--ig-ink)]">Demonstrated Skills <span class="text-[var(--ig-rose)]">*</span></label>
                                <p class="text-[10px] text-[var(--ig-muted)] mt-0.5">Search and select the skills you applied in this project.</p>
                            </div>
                            <span id="project-skill-count" class="ig-mono text-[10px] font-semibold text-[var(--ig-accent)] whitespace-nowrap">0 selected</span>
                        </div>
                        <input type="text" id="project-skill-search" placeholder="🔍 Search skills... e.g. React, Python, Figma" class="ig-input text-sm mb-3" />
                        <div class="flex flex-wrap gap-2 max-h-48 overflow-y-auto" id="project-skills-container">
                            @foreach($skills as $skill)
                                <label class="project-skill-chip inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[var(--ig-line)] rounded-xl text-xs font-semibold text-[var(--ig-ink-2)] cursor-pointer hover:border-[var(--ig-accent)]/60 transition-all duration-200 select-none" data-skill-name="{{ strtolower($skill->name) }}" data-skill-domain="{{ $skill->domain }}">
                                    <input type="checkbox" name="skills_demonstrated[]" value="{{ $skill->id }}" class="hidden project-skill-cb">
                                    <span class="project-skill-check text-[var(--ig-accent)] font-bold hidden">✓</span>
                                    <span>{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p id="project-skill-no-results" class="hidden text-xs text-[var(--ig-muted)] italic mt-2 text-center py-2">No skills match your search.</p>
                        @error('skills_demonstrated')
                            <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="ig-btn ig-btn-primary py-2.5 px-6 text-sm">
                            <span>Add Project to Portfolio</span><span class="arrow">→</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const domainSelect = document.getElementById('primary_domain');
            const roleSelect = document.getElementById('preferred_role');
            const domainCards = document.querySelectorAll('.domain-card');
            const roleSectionWrapper = document.getElementById('role-section-wrapper');
            const roleCardsContainer = document.getElementById('role-cards-container');
            const skillSearch = document.getElementById('skill-search');
            const skillChips = document.querySelectorAll('.skill-chip');
            const selectedInfo = document.getElementById('skill-selected-info');

            const domainsData = @json(\App\Models\StudentProfile::$domains);
            const initialDomain = "{{ old('primary_domain', $profile->primary_domain) }}";
            const initialRole = "{{ old('preferred_role', $profile->preferred_role) }}";

            // ─── Domain Card Selection ───
            domainCards.forEach(card => {
                card.addEventListener('click', function() {
                    selectDomain(this.getAttribute('data-domain'));
                });
            });

            function selectDomain(domainName) {
                domainSelect.value = domainName;
                domainSelect.dispatchEvent(new Event('change'));

                domainCards.forEach(c => {
                    if (c.getAttribute('data-domain') === domainName) {
                        c.classList.remove('border-[var(--ig-line)]');
                        c.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-2', 'ring-[var(--ig-accent)]/20', 'shadow-md');
                    } else {
                        c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-2', 'ring-[var(--ig-accent)]/20', 'shadow-md');
                        c.classList.add('border-[var(--ig-line)]');
                    }
                });

                const roles = domainsData[domainName] || [];
                if (roles.length > 0) {
                    roleSelect.disabled = false;
                    let options = '<option value="">Select Role</option>';
                    roles.forEach(role => {
                        options += `<option value="${role}">${role}</option>`;
                    });
                    roleSelect.innerHTML = options;

                    roleSectionWrapper.style.display = 'block';
                    roleCardsContainer.innerHTML = roles.map(role => `
                        <div data-role="${role}" class="role-card cursor-pointer px-4 py-2.5 bg-white border-2 border-[var(--ig-line)] rounded-xl text-center hover:shadow-sm hover:border-[var(--ig-accent)]/50 active:scale-[0.97] transition-all duration-200 group">
                            <p class="font-bold text-xs text-[var(--ig-ink-2)] group-hover:text-[var(--ig-ink)]">${role}</p>
                        </div>
                    `).join('');

                    roleCardsContainer.querySelectorAll('.role-card').forEach(card => {
                        card.addEventListener('click', function() {
                            selectRole(this.getAttribute('data-role'));
                        });
                    });
                } else {
                    roleSelect.disabled = true;
                    roleSelect.innerHTML = '<option value="">Select Role (Select Domain first)</option>';
                    roleSectionWrapper.style.display = 'none';
                    roleCardsContainer.innerHTML = '';
                }

                filterSkills();
            }

            function selectRole(roleName) {
                roleSelect.value = roleName;
                roleSelect.dispatchEvent(new Event('change'));

                roleCardsContainer.querySelectorAll('.role-card').forEach(c => {
                    if (c.getAttribute('data-role') === roleName) {
                        c.classList.remove('border-[var(--ig-line)]');
                        c.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-2', 'ring-[var(--ig-accent)]/20');
                    } else {
                        c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-2', 'ring-[var(--ig-accent)]/20');
                        c.classList.add('border-[var(--ig-line)]');
                    }
                });
            }

            // ─── Skills Chip Toggle ───
            skillChips.forEach(chip => {
                const skillId = chip.getAttribute('data-skill-id');
                const cb = document.getElementById(`skill-checkbox-${skillId}`);
                const icon = chip.querySelector('.status-icon');

                if (cb && cb.checked) {
                    chip.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]', 'text-[var(--ig-accent)]', 'shadow-[0_2px_8px_rgba(255,79,25,0.15)]');
                    if (icon) icon.classList.remove('hidden');
                }

                chip.addEventListener('click', function() {
                    if (cb.checked) {
                        cb.checked = false;
                        cb.dispatchEvent(new Event('change'));
                        chip.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]', 'text-[var(--ig-accent)]', 'shadow-[0_2px_8px_rgba(255,79,25,0.15)]');
                        if (icon) icon.classList.add('hidden');
                    } else {
                        const checkedCount = document.querySelectorAll('.skill-checkbox-hidden:checked').length;
                        if (checkedCount >= 10) {
                            chip.classList.add('animate-shake');
                            setTimeout(() => chip.classList.remove('animate-shake'), 400);
                            return;
                        }
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                        chip.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]', 'text-[var(--ig-accent)]', 'shadow-[0_2px_8px_rgba(255,79,25,0.15)]');
                        if (icon) icon.classList.remove('hidden');
                    }
                    updateSelectedSkillsCount();
                });
            });

            function updateSelectedSkillsCount() {
                const checkedCount = document.querySelectorAll('.skill-checkbox-hidden:checked').length;
                selectedInfo.textContent = `${checkedCount} / 10 Selected`;
            }

            // ─── Skills Filtering (Search + Domain) ───
            function filterSkills() {
                const term = skillSearch.value.toLowerCase().trim();
                const selectedDomain = domainSelect.value;

                skillChips.forEach(chip => {
                    const skillId = chip.getAttribute('data-skill-id');
                    const cb = document.getElementById(`skill-checkbox-${skillId}`);
                    const isChecked = cb && cb.checked;
                    const skillDomain = chip.getAttribute('data-domain');
                    const skillName = chip.getAttribute('data-name');

                    if (term) {
                        chip.style.display = skillName.includes(term) ? 'inline-flex' : 'none';
                    } else {
                        const domainMatches = selectedDomain && skillDomain === selectedDomain;
                        chip.style.display = (domainMatches || isChecked) ? 'inline-flex' : 'none';
                    }
                });
            }

            if (skillSearch) skillSearch.addEventListener('input', filterSkills);
            domainSelect.addEventListener('change', filterSkills);

            // ─── Project Skills Chip Toggle ───
            const projectSkillChips = document.querySelectorAll('.project-skill-chip');
            const projectSkillSearch = document.getElementById('project-skill-search');
            const projectSkillCount = document.getElementById('project-skill-count');
            const projectNoResults = document.getElementById('project-skill-no-results');

            function updateProjectSkillCount() {
                const count = document.querySelectorAll('.project-skill-cb:checked').length;
                if (projectSkillCount) projectSkillCount.textContent = count > 0 ? `${count} selected` : '0 selected';
            }

            projectSkillChips.forEach(label => {
                const cb = label.querySelector('.project-skill-cb');
                const check = label.querySelector('.project-skill-check');
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    cb.checked = !cb.checked;
                    if (cb.checked) {
                        label.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]', 'text-[var(--ig-accent)]');
                        if (check) check.classList.remove('hidden');
                    } else {
                        label.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]', 'text-[var(--ig-accent)]');
                        if (check) check.classList.add('hidden');
                    }
                    updateProjectSkillCount();
                });
            });

            // ─── Project Skills Search Filter ───
            function filterProjectSkills() {
                const term = projectSkillSearch.value.toLowerCase().trim();
                let visibleCount = 0;

                projectSkillChips.forEach(label => {
                    const name = label.getAttribute('data-skill-name');
                    const cb = label.querySelector('.project-skill-cb');
                    const isChecked = cb && cb.checked;

                    if (!term) {
                        // No search term: show all skills
                        label.style.display = 'inline-flex';
                        visibleCount++;
                    } else if (name.includes(term) || isChecked) {
                        // Show matching skills + already selected ones
                        label.style.display = 'inline-flex';
                        visibleCount++;
                    } else {
                        label.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (projectNoResults) {
                    projectNoResults.classList.toggle('hidden', visibleCount > 0);
                }
            }

            if (projectSkillSearch) {
                projectSkillSearch.addEventListener('input', filterProjectSkills);
            }

            // ─── Initialize ───
            if (initialDomain) {
                selectDomain(initialDomain);
                if (initialRole) {
                    setTimeout(() => selectRole(initialRole), 50);
                }
            }
            updateSelectedSkillsCount();
        });
    </script>
</x-app-layout>
