<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-black font-poppins bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Welcome! 🎉
        </h2>
        <p class="text-gray-600 text-sm font-medium">Choose your account type to continue</p>
    </div>

    <form method="POST" action="{{ route('auth.google.complete') }}" class="space-y-4">
        @csrf
        
        <div class="space-y-3">
            <label class="block">
                <input type="radio" name="role" value="student" class="sr-only peer" required>
                <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🎓</div>
                        <div>
                            <div class="font-bold text-gray-900">Student</div>
                            <div class="text-sm text-gray-600">Looking for opportunities</div>
                        </div>
                    </div>
                </div>
            </label>
            
            <label class="block">
                <input type="radio" name="role" value="startup" class="sr-only peer" required>
                <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🚀</div>
                        <div>
                            <div class="font-bold text-gray-900">Startup</div>
                            <div class="text-sm text-gray-600">Looking for talent</div>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <!-- Student Career Domain & Role Selection (shown only for students) -->
        <div id="student-career-selection" class="space-y-6 mt-4" style="display: none;">
            <!-- Hidden original select inputs for form compatibility -->
            <select id="primary_domain" name="primary_domain" class="hidden">
                <option value="">Select Domain</option>
                @foreach(\App\Models\StudentProfile::$domains as $domain => $roles)
                    <option value="{{ $domain }}" {{ old('primary_domain') == $domain ? 'selected' : '' }}>{{ $domain }}</option>
                @endforeach
            </select>
            <select id="preferred_role" name="preferred_role" class="hidden" disabled>
                <option value="">Select Role</option>
            </select>

            <!-- Domain selection UI -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Choose Your Career Domain</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="domain-cards-container">
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
            </div>

            <!-- Role selection UI -->
            <div id="role-section-wrapper" class="space-y-3" style="display: none;">
                <label class="block text-sm font-semibold text-gray-700">Select Your Preferred Role</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="role-cards-container">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const careerSelection = document.getElementById('student-career-selection');
            const domainSelect = document.getElementById('primary_domain');
            const roleSelect = document.getElementById('preferred_role');
            const domainCards = document.querySelectorAll('.domain-card');
            const roleSectionWrapper = document.getElementById('role-section-wrapper');
            const roleCardsContainer = document.getElementById('role-cards-container');

            const domainsData = @json(\App\Models\StudentProfile::$domains);
            const initialDomain = "{{ old('primary_domain') }}";
            const initialRole = "{{ old('preferred_role') }}";

            function toggleCareerSelection() {
                const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
                if (selectedRole === 'student') {
                    careerSelection.style.display = 'block';
                } else {
                    careerSelection.style.display = 'none';
                    clearDomainSelection();
                }
            }

            function clearDomainSelection() {
                domainSelect.value = '';
                roleSelect.value = '';
                roleSelect.disabled = true;
                roleSelect.innerHTML = '<option value="">Select Role (Select Domain first)</option>';
                
                domainCards.forEach(c => {
                    c.classList.remove('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.25)]');
                    c.classList.add('border-gray-200');
                });
                roleSectionWrapper.style.display = 'none';
                roleCardsContainer.innerHTML = '';
            }

            function selectDomain(domainName) {
                domainSelect.value = domainName;
                domainSelect.dispatchEvent(new Event('change'));

                // Visual classes updates
                domainCards.forEach(c => {
                    if (c.getAttribute('data-domain') === domainName) {
                        c.classList.remove('border-gray-200');
                        c.classList.add('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.25)]');
                    } else {
                        c.classList.remove('border-indigo-600', 'bg-indigo-50/20', 'ring-4', 'ring-indigo-500/15', 'shadow-[0_0_15px_rgba(99,102,241,0.25)]');
                        c.classList.add('border-gray-200');
                    }
                });

                // Populate and show roles
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

                    // Click listeners for roles
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

            // Click listener for domain cards
            domainCards.forEach(card => {
                card.addEventListener('click', function() {
                    const dom = this.getAttribute('data-domain');
                    selectDomain(dom);
                });
            });

            roleInputs.forEach(input => input.addEventListener('change', toggleCareerSelection));

            toggleCareerSelection();

            // Populate initial state if redirected back with old input
            if (initialDomain) {
                selectDomain(initialDomain);
                if (initialRole) {
                    setTimeout(() => {
                        selectRole(initialRole);
                    }, 50);
                }
            }
        });
        </script>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="submit" class="group relative w-full py-3 rounded-xl text-white font-bold overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl mt-6">
            <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 animate-gradient bg-[length:200%_200%]"></span>
            <span class="relative">Continue</span>
        </button>
    </form>
</x-guest-layout>
