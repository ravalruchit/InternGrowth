<x-app-layout>
    <div class="ig-container py-12 space-y-8 ig-anim-fade-up">
        <!-- Reputation Card Component -->
        <x-reputation-card />

        <div class="mb-2">
            <p class="ig-eyebrow mb-3">— Settings</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                Edit Student <span class="ig-serif text-[var(--ig-accent)]">Profile.</span>
            </h1>
        </div>

        <div class="ig-card p-6 sm:p-8">
            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                @csrf
                
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2 flex items-center space-x-2">
                        <span>Full Name</span>
                    </label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" 
                           class="ig-input" required>
                    @error('name')
                        <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email (Disabled) --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2 flex items-center space-x-2">
                        <span>Email Address</span>
                    </label>
                    <input type="email" value="{{ auth()->user()->email }}" 
                           class="ig-input bg-[var(--ig-bg-2)] cursor-not-allowed opacity-80" disabled>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5 flex items-center gap-1">
                        🛡️ Email address cannot be changed.
                    </p>
                </div>
                
                {{-- Bio --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2 flex items-center space-x-2">
                        <span>Bio</span>
                    </label>
                    <textarea name="bio" rows="5" 
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

                {{-- Domain Selection Cards --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-3">Primary Domain</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="domain-cards-container">
                        @php
                            $domainDetails = [
                                'Software Development' => ['icon' => '💻', 'desc' => 'Build web apps, mobile apps, backend systems, and write clean code.'],
                                'UI/UX Design' => ['icon' => '🎨', 'desc' => 'Create visual interfaces, wireframes, prototype flows, and design systems.'],
                                'Digital Marketing' => ['icon' => '📈', 'desc' => 'Drive traffic, manage ads, optimize search presence, and write copy.'],
                                'Data & AI' => ['icon' => '🤖', 'desc' => 'Build AI models, analyze large data sets, and extract key insights.'],
                                'Content & Business' => ['icon' => '📋', 'desc' => 'Write engaging content, construct business strategies, and perform market research.']
                            ];
                        @endphp
                        @foreach($domainDetails as $domName => $info)
                            <div data-domain="{{ $domName }}" class="domain-card cursor-pointer p-4 bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:shadow-xl hover:scale-105 hover:border-indigo-400 active:scale-95 transition-all duration-300 flex flex-col justify-between group">
                                <div>
                                    <div class="text-3xl mb-2 transition-transform duration-300 group-hover:scale-110">{{ $info['icon'] }}</div>
                                    <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1">{{ $domName }}</h4>
                                    <p class="text-[11.5px] text-[var(--ig-muted)] leading-normal">{{ $info['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('primary_domain')
                        <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role Selection Cards --}}
                <div id="role-section-wrapper" class="space-y-3" style="display: none;">
                    <label class="block text-sm font-semibold text-[var(--ig-ink)]">Preferred Role</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="role-cards-container">
                        <!-- Dynamic role cards populated in JS -->
                    </div>
                    @error('preferred_role')
                        <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Skills Selection (Modern Chips) --}}
                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-1">Skills Matrix</label>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <input type="text" id="skill-search" placeholder="Search skills..." class="ig-input max-w-md" />
                        <span id="skill-selected-info" class="ig-mono text-xs font-semibold text-[var(--ig-accent)]">0 / 10 Skills Selected</span>
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
                                 class="skill-chip cursor-pointer px-4 py-2 bg-white border border-[var(--ig-line)] rounded-xl text-xs font-semibold text-[var(--ig-ink-2)] hover:border-indigo-400 hover:scale-105 active:scale-95 transition-all duration-200 select-none flex items-center gap-1.5">
                                <span class="status-icon text-indigo-500 font-bold hidden">✓</span>
                                <span>{{ $skill->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Portfolio Visibility & Talent Profile URL -->
                @if($profile->portfolio)
                    <div class="ig-card p-6 bg-[var(--ig-bg-2)] border-[var(--ig-line)]">
                        <h3 class="text-sm font-bold text-[var(--ig-ink)] mb-3 flex items-center gap-2">
                            📂 Public Talent Profile Settings
                        </h3>

                        <div class="flex items-center justify-between mb-4">
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
                                    Preview Profile →
                                </a>
                                <x-share-profile-button :url="route('talent.profile', $profile->portfolio->custom_slug)" />
                            </div>
                        </div>
                    </div>
                @endif

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

            // Initialize Domain Card selection and handlers
            domainCards.forEach(card => {
                card.addEventListener('click', function() {
                    const domainName = this.getAttribute('data-domain');
                    selectDomain(domainName);
                });
            });

            function selectDomain(domainName) {
                domainSelect.value = domainName;
                domainSelect.dispatchEvent(new Event('change'));

                // Domain cards styles
                domainCards.forEach(c => {
                    if (c.getAttribute('data-domain') === domainName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.25)]');
                    } else {
                        c.classList.remove('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.25)]');
                        c.classList.add('border-gray-200');
                    }
                });

                // Populate preferred role options & cards
                const roles = domainsData[domainName] || [];
                if (roles.length > 0) {
                    roleSelect.disabled = false;
                    let options = '<option value="">Select Role</option>';
                    roles.forEach(role => {
                        options += `<option value="${role}">${role}</option>`;
                    });
                    roleSelect.innerHTML = options;

                    // Render Role Cards
                    roleSectionWrapper.style.display = 'block';
                    roleCardsContainer.innerHTML = roles.map(role => `
                        <div data-role="${role}" class="role-card cursor-pointer p-3 bg-white border-2 border-gray-200 rounded-2xl text-center shadow-sm hover:shadow-md hover:scale-105 hover:border-indigo-400 active:scale-95 transition-all duration-300 group">
                            <p class="font-bold text-xs text-[var(--ig-ink-2)] group-hover:text-[var(--ig-ink)]">${role}</p>
                        </div>
                    `).join('');

                    // Add click event listeners to role cards
                    roleCardsContainer.querySelectorAll('.role-card').forEach(card => {
                        card.addEventListener('click', function() {
                            const roleName = this.getAttribute('data-role');
                            selectRole(roleName);
                        });
                    });
                } else {
                    roleSelect.disabled = true;
                    roleSelect.innerHTML = '<option value="">Select Role (Select Domain first)</option>';
                    roleSectionWrapper.style.display = 'none';
                    roleCardsContainer.innerHTML = '';
                }

                // Filter skills to domain
                filterSkills();
            }

            function selectRole(roleName) {
                roleSelect.value = roleName;
                roleSelect.dispatchEvent(new Event('change'));

                roleCardsContainer.querySelectorAll('.role-card').forEach(c => {
                    if (c.getAttribute('data-role') === roleName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.2)]');
                    } else {
                        c.classList.remove('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.2)]');
                        c.classList.add('border-gray-200');
                    }
                });
            }

            // Skills Chip click sync with hidden checkboxes
            skillChips.forEach(chip => {
                const skillId = chip.getAttribute('data-skill-id');
                const cb = document.getElementById(`skill-checkbox-${skillId}`);
                const icon = chip.querySelector('.status-icon');

                // Sync initial visual state from checkbox
                if (cb && cb.checked) {
                    chip.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-900', 'shadow-[0_2px_8px_rgba(99,102,241,0.15)]');
                    if (icon) icon.classList.remove('hidden');
                }

                chip.addEventListener('click', function() {
                    if (cb.checked) {
                        cb.checked = false;
                        cb.dispatchEvent(new Event('change'));
                        chip.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-900', 'shadow-[0_2px_8px_rgba(99,102,241,0.15)]');
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
                        chip.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-900', 'shadow-[0_2px_8px_rgba(99,102,241,0.15)]');
                        if (icon) icon.classList.remove('hidden');
                    }
                    updateSelectedSkillsCount();
                });
            });

            function updateSelectedSkillsCount() {
                const checkedCount = document.querySelectorAll('.skill-checkbox-hidden:checked').length;
                selectedInfo.textContent = `${checkedCount} / 10 Skills Selected`;
            }

            // Real-time skill filtering (Search + Domain filter)
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
                        // Global search: search all skills across all domains
                        if (skillName.includes(term)) {
                            chip.style.display = 'inline-flex';
                        } else {
                            chip.style.display = 'none';
                        }
                    } else {
                        // Default view: show only skills of selected domain OR already checked skills
                        const domainMatches = selectedDomain && skillDomain === selectedDomain;
                        if (domainMatches || isChecked) {
                            chip.style.display = 'inline-flex';
                        } else {
                            chip.style.display = 'none';
                        }
                    }
                });
            }

            if (skillSearch) skillSearch.addEventListener('input', filterSkills);
            domainSelect.addEventListener('change', filterSkills);

            // Trigger initial state mapping
            if (initialDomain) {
                selectDomain(initialDomain);
                if (initialRole) {
                    setTimeout(() => {
                        selectRole(initialRole);
                    }, 50);
                }
            }
            updateSelectedSkillsCount();
        });
    </script>
</x-app-layout>
