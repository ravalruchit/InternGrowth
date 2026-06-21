<x-app-layout>
    <div class="ig-container py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start ig-anim-fade-up">
            <!-- Form Input (Left) -->
            <div class="lg:col-span-8 space-y-8">
                <div class="mb-2">
                    <p class="ig-eyebrow mb-3">— Create Task</p>
                    <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                        Post New <span class="ig-serif text-[var(--ig-accent)]">Task.</span>
                    </h1>
                </div>

                @if ($errors->any())
                    <div class="ig-banner ig-banner-warn text-sm">
                        <div>
                            <p class="font-bold text-amber-955 mb-1">Please fix the following issues:</p>
                            <ul class="list-disc list-inside space-y-1 text-amber-900">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="ig-card p-6 sm:p-8">
                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6" id="task-form">
                        @csrf

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Task Title</label>
                            <input type="text" name="title" id="f-title" value="{{ old('title') }}" required
                                   class="ig-input"
                                   placeholder="e.g., Build a responsive landing page">
                            @error('title')<p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2 flex justify-between">
                                <span>Description</span>
                                <span id="desc-chars" class="ig-mono text-xs font-normal text-[var(--ig-muted)]">0 characters</span>
                            </label>
                            <textarea name="description" id="f-description" rows="8" required
                                      class="ig-input resize-none"
                                      placeholder="Describe the task requirements, deliverables, and expectations...">{{ old('description') }}</textarea>
                            <p class="text-xs text-[var(--ig-muted)] mt-1.5">Provide clear deliverables, task requirements, and output formats.</p>
                            @error('description')<p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Hidden selects for form submission compatibility --}}
                        <select id="domain" name="domain" class="hidden">
                            <option value="">Select Domain</option>
                            @foreach(\App\Models\StudentProfile::$domains as $dom => $roles)
                                <option value="{{ $dom }}" {{ old('domain') == $dom ? 'selected' : '' }}>{{ $dom }}</option>
                            @endforeach
                        </select>
                        <select id="role" name="role" class="hidden" disabled>
                            <option value="">Select Role</option>
                        </select>

                        {{-- Domain Selection Cards --}}
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-3">Domain</label>
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
                                    <div data-domain="{{ $domName }}" class="domain-card cursor-pointer p-4 bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:shadow-xl hover:scale-105 hover:border-[var(--ig-accent)]/60 active:scale-95 transition-all duration-300 flex flex-col justify-between group">
                                        <div>
                                            <div class="text-3xl mb-2 transition-transform duration-300 group-hover:scale-110">{{ $info['icon'] }}</div>
                                            <h4 class="font-bold text-sm text-[var(--ig-ink)] mb-1">{{ $domName }}</h4>
                                            <p class="text-[11.5px] text-[var(--ig-muted)] leading-normal">{{ $info['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('domain')<p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Role Selection Cards --}}
                        <div id="role-section-wrapper" class="space-y-3" style="display: none;">
                            <label class="block text-sm font-semibold text-[var(--ig-ink)]">Role</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="role-cards-container">
                                <!-- Dynamic role cards populated in JS -->
                            </div>
                            @error('role')<p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Skills Selection (Modern Chips) --}}
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                <label class="block text-sm font-semibold text-[var(--ig-ink)]">Required Skills</label>
                                <span id="skill-selected-info" class="ig-mono text-xs font-semibold text-[var(--ig-accent)]">0 / 10 Skills Selected</span>
                            </div>
                            <input type="text" id="skill-search" placeholder="Search skills..." class="ig-input mb-3 max-w-md" />

                            {{-- Hidden checkbox inputs for standard submission --}}
                            <div class="hidden">
                                @foreach($skills as $skill)
                                    <input type="checkbox" name="skills[]" id="skill-checkbox-{{ $skill->id }}" value="{{ $skill->id }}"
                                           {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}
                                           class="skill-checkbox-hidden">
                                @endforeach
                            </div>

                            {{-- Chips Grid --}}
                            <div class="flex flex-wrap gap-2" id="skills-chips-container">
                                @foreach($skills as $skill)
                                    <div data-skill-id="{{ $skill->id }}" data-domain="{{ $skill->domain }}" data-name="{{ strtolower($skill->name) }}"
                                         class="skill-chip cursor-pointer px-4 py-2 bg-white border border-[var(--ig-line)] rounded-xl text-xs font-semibold text-[var(--ig-ink-2)] hover:border-[var(--ig-accent)]/60 hover:scale-105 active:scale-95 transition-all duration-200 select-none flex items-center gap-1.5">
                                        <span class="status-icon text-[var(--ig-accent)] font-bold hidden">✓</span>
                                        <span>{{ $skill->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @error('skills')<p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Stipend --}}
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                Stipend (Optional)
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--ig-muted)] font-semibold">₹</span>
                                <input type="number" name="stipend" id="f-stipend" value="{{ old('stipend') }}" step="1" min="0"
                                       class="ig-input pl-8"
                                       placeholder="0">
                            </div>
                            <p class="text-xs text-[var(--ig-muted)] mt-1.5">Specify stipend amount. Escrow is automatically locked upon submission posting.</p>
                            @error('stipend')<p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="ig-btn ig-btn-primary flex-1 justify-center">
                                <span>Post Task</span><span class="arrow">→</span>
                            </button>
                            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Guidelines Sidebar (Right) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-4">
                <!-- Quality Card -->
                <div class="ig-card-dark p-6 relative overflow-hidden shadow-xl">
                    <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-20" style="background:var(--ig-accent)"></div>
                    <div class="relative">
                        <p class="ig-eyebrow mb-1" style="color: #9C9580">IPRS Scoring Impact</p>
                        <h4 class="text-xl font-bold text-white mb-2">Build Ecosystem Trust</h4>
                        <p class="text-xs" style="color: #C9C1AE leading-relaxed">Posting detailed, high-quality tasks attracts top students and establishes your startup credibility score.</p>
                    </div>
                </div>

                <!-- Guidance Checklist Card -->
                <div class="ig-card p-5 space-y-3 text-xs leading-relaxed">
                    <p class="text-sm font-bold text-[var(--ig-ink)] mb-3">Task Guidelines</p>
                    
                    <div class="space-y-3 text-[var(--ig-muted)]">
                        <p><strong>📝 Clear Scope:</strong> Be specific about task expectations, output formats, and timelines to avoid revision loops.</p>
                        <p><strong>🎯 Skills Profile:</strong> Select matching skills accurately so AI filters candidates with correct backgrounds.</p>
                        <p><strong>💰 Secure Escrow:</strong> Offering a stipend locks the amount in escrow. Released automatically upon submission approval.</p>
                    </div>
                </div>

                <!-- Tips -->
                <div class="ig-banner ig-banner-warn text-xs space-y-1">
                    <div>
                        <p class="font-semibold text-amber-955 mb-1">💡 Pro Tips for Founders</p>
                        <p class="text-amber-900">• Detail tasks extensively to set clear student expectations.</p>
                        <p class="text-amber-900">• Select primary programming or design skills required.</p>
                        <p class="text-amber-900 font-medium">• Verify task submissions within 48h to maintain a high response score.</p>
                    </div>
                </div>
         <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const domainSelect = document.getElementById('domain');
            const roleSelect = document.getElementById('role');
            const domainCards = document.querySelectorAll('.domain-card');
            const roleSectionWrapper = document.getElementById('role-section-wrapper');
            const roleCardsContainer = document.getElementById('role-cards-container');
            const skillSearch = document.getElementById('skill-search');
            const skillChips = document.querySelectorAll('.skill-chip');
            const selectedInfo = document.getElementById('skill-selected-info');
            const descInput = document.getElementById('f-description');
            const descChars = document.getElementById('desc-chars');

            const domainsData = @json(\App\Models\StudentProfile::$domains);
            const oldDomain = "{{ old('domain') }}";
            const oldRole = "{{ old('role') }}";

            // Character count description
            if (descInput && descChars) {
                descInput.addEventListener('input', function() {
                    descChars.textContent = this.value.length + ' characters';
                });
                descChars.textContent = descInput.value.length + ' characters';
            }

            // Domain Cards Click Listeners
            domainCards.forEach(card => {
                card.addEventListener('click', function() {
                    const domainName = this.getAttribute('data-domain');
                    selectDomain(domainName);
                });
            });

            function selectDomain(domainName) {
                domainSelect.value = domainName;
                domainSelect.dispatchEvent(new Event('change'));

                // Update Domain cards classes
                domainCards.forEach(c => {
                    if (c.getAttribute('data-domain') === domainName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.25)]');
                    } else {
                        c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.25)]');
                        c.classList.add('border-gray-200');
                    }
                });

                // Populate and show role cards
                const roles = domainsData[domainName] || [];
                if (roles.length > 0) {
                    roleSelect.disabled = false;
                    let options = '<option value="">Select Role</option>';
                    roles.forEach(role => {
                        options += `<option value="${role}">${role}</option>`;
                    });
                    roleSelect.innerHTML = options;

                    // Render cards
                    roleSectionWrapper.style.display = 'block';
                    roleCardsContainer.innerHTML = roles.map(role => `
                        <div data-role="${role}" class="role-card cursor-pointer p-3 bg-white border-2 border-gray-200 rounded-2xl text-center shadow-sm hover:shadow-md hover:scale-105 hover:border-[var(--ig-accent)]/60 active:scale-95 transition-all duration-300 group">
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

                // Filter skills to domain, unchecking hidden ones
                filterAndCleanSkills();
            }

            function selectRole(roleName) {
                roleSelect.value = roleName;
                roleSelect.dispatchEvent(new Event('change'));

                roleCardsContainer.querySelectorAll('.role-card').forEach(c => {
                    if (c.getAttribute('data-role') === roleName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.2)]');
                    } else {
                        c.classList.remove('border-[var(--ig-accent)]', 'bg-[var(--ig-accent-soft)]/20', 'ring-4', 'ring-[var(--ig-accent)]/15', 'shadow-[0_0_15px_rgba(255,79,25,0.2)]');
                        c.classList.add('border-gray-200');
                    }
                });
            }

            // Sync skills chips with hidden checkboxes
            skillChips.forEach(chip => {
                const skillId = chip.getAttribute('data-skill-id');
                const cb = document.getElementById(`skill-checkbox-${skillId}`);
                const icon = chip.querySelector('.status-icon');

                // Sync initial state from check state
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
                selectedInfo.textContent = `${checkedCount} / 10 Skills Selected`;
            }

            // Filter chips on search or domain change
            function filterAndCleanSkills() {
                const term = skillSearch.value.toLowerCase().trim();
                const selectedDomain = domainSelect.value;

                skillChips.forEach(chip => {
                    const skillDomain = chip.getAttribute('data-domain');
                    const skillId = chip.getAttribute('data-skill-id');
                    const skillName = chip.getAttribute('data-name');
                    const cb = document.getElementById(`skill-checkbox-${skillId}`);
                    const isChecked = cb && cb.checked;

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
                updateSelectedSkillsCount();
            }

            if (skillSearch) skillSearch.addEventListener('input', filterAndCleanSkills);
            domainSelect.addEventListener('change', filterAndCleanSkills);

            // Populate initial state if redirected back with old input
            if (oldDomain) {
                selectDomain(oldDomain);
                if (oldRole) {
                    setTimeout(() => {
                        selectRole(oldRole);
                    }, 50);
                }
            }
            updateSelectedSkillsCount();
        });
    </script>
</x-app-layout>
