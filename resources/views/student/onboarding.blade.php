<x-app-layout>
    <div class="ig-container py-12 max-w-2xl">
        <!-- Progress Stepper -->
        <div class="mb-10 text-center">
            <h1 class="ig-display text-4xl mb-2">Set up your profile</h1>
            <p class="text-xs text-[var(--ig-muted)]">Just 3 quick steps to get onboarded</p>

            <div class="flex items-center justify-center gap-4 mt-6">
                <!-- Step 1 pill -->
                <div id="pill-step-1" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-accent)] text-white text-xs font-black transition-all">
                    <span>1</span> <span class="hidden sm:inline">Basics</span>
                </div>
                <div class="w-8 h-0.5 bg-[var(--ig-line-2)]" id="line-step-1"></div>
                
                <!-- Step 2 pill -->
                <div id="pill-step-2" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-bg-2)] text-[var(--ig-muted)] text-xs font-bold transition-all border border-[var(--ig-line)]">
                    <span>2</span> <span class="hidden sm:inline">Skills</span>
                </div>
                <div class="w-8 h-0.5 bg-[var(--ig-line-2)]" id="line-step-2"></div>
                
                <!-- Step 3 pill -->
                <div id="pill-step-3" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-bg-2)] text-[var(--ig-muted)] text-xs font-bold transition-all border border-[var(--ig-line)]">
                    <span>3</span> <span class="hidden sm:inline">Verify (Optional)</span>
                </div>
            </div>
        </div>

        <!-- Onboarding Card -->
        <div class="ig-card p-8 bg-white border border-[var(--ig-line)] rounded-3xl relative overflow-hidden">
            
            <!-- Step 1 content -->
            <div id="content-step-1" class="space-y-6">
                <div>
                    <h3 class="ig-display text-2xl text-[var(--ig-ink)] font-extrabold mb-1">Tell us about yourself</h3>
                    <p class="text-xs text-[var(--ig-muted)]">Help startups match you with tasks that fit your education and career interest.</p>
                </div>

                <form id="form-step-1" class="space-y-5" onsubmit="submitStep1(event)">
                    @csrf
                    <div>
                        <label for="college_name" class="block text-sm font-semibold mb-2">College / University Name</label>
                        <input id="college_name" name="college_name" type="text" required value="{{ old('college_name', $profile->college_name) }}" placeholder="e.g. Stanford University" class="ig-input" />
                        <span class="text-xs text-red-500 mt-1 hidden" id="err-college_name"></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="graduation_year" class="block text-sm font-semibold mb-2">Graduation Year</label>
                            <input id="graduation_year" name="graduation_year" type="number" required min="2020" max="2035" value="{{ old('graduation_year', $profile->graduation_year ?? now()->year + 2) }}" class="ig-input" />
                            <span class="text-xs text-red-500 mt-1 hidden" id="err-graduation_year"></span>
                        </div>

                        <div>
                            <label for="primary_domain" class="block text-sm font-semibold mb-2">Career Domain</label>
                            <select id="primary_domain" name="primary_domain" required class="ig-input">
                                <option value="">Select Domain</option>
                                @foreach($domains as $dom => $roles)
                                    <option value="{{ $dom }}" {{ old('primary_domain', $profile->primary_domain) == $dom ? 'selected' : '' }}>{{ $dom }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-red-500 mt-1 hidden" id="err-primary_domain"></span>
                        </div>
                    </div>

                    <button type="submit" class="ig-btn ig-btn-primary w-full justify-center mt-4">
                        <span>Continue</span>
                        <span class="arrow">→</span>
                    </button>
                </form>
            </div>

            <!-- Step 2 content (Skills selection) -->
            <div id="content-step-2" class="space-y-6 hidden">
                <div>
                    <h3 class="ig-display text-2xl text-[var(--ig-ink)] font-extrabold mb-1">Select your skills</h3>
                    <p class="text-xs text-[var(--ig-muted)]">Choose the tech stack or skills you excel at. Startups search by these badges.</p>
                </div>

                <form id="form-step-2" class="space-y-6" onsubmit="submitStep2(event)">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold mb-3">Skills (Select all that apply)</label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($skills as $skill)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="peer sr-only" {{ $profile->skills->contains($skill->id) ? 'checked' : '' }}>
                                    <span class="inline-block px-4 py-2 text-xs font-bold border-2 border-gray-200 rounded-2xl bg-white text-[var(--ig-ink-2)] peer-checked:border-[var(--ig-accent)] peer-checked:bg-[var(--ig-accent-soft)]/20 peer-checked:text-[var(--ig-accent)] shadow-sm hover:border-gray-300 transition-all select-none">
                                        {{ $skill->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <span class="text-xs text-red-500 mt-1.5 block hidden" id="err-skills"></span>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" onclick="goToStep(1)" class="flex-1 ig-btn ig-btn-ghost py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Back
                        </button>
                        <button type="submit" class="flex-1 ig-btn ig-btn-primary py-3 justify-center text-xs font-bold rounded-2xl">
                            <span>Continue</span>
                            <span class="arrow">→</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3 content (Verification - Optional) -->
            <div id="content-step-3" class="space-y-6 hidden">
                <div>
                    <h3 class="ig-display text-2xl text-[var(--ig-ink)] font-extrabold mb-1">Verify your student identity</h3>
                    <p class="text-xs text-[var(--ig-muted)]">Upload a photo of your college ID card or enter your college email. You can skip this and verify later from the dashboard settings.</p>
                </div>

                <form id="form-step-3" class="space-y-5" onsubmit="submitStep3(event)" enctype="multipart/form-data">
                    @csrf
                    
                    <div x-data="{ method: 'id_card' }" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="ver_method" value="id_card" x-model="method" class="sr-only">
                                <div :class="method === 'id_card' ? 'border-[var(--ig-ink)] bg-slate-50' : 'border-gray-200'" class="p-3 border-2 rounded-2xl text-center transition-all">
                                    <p class="font-bold text-xs">Upload Student ID</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="ver_method" value="email" x-model="method" class="sr-only">
                                <div :class="method === 'email' ? 'border-[var(--ig-ink)] bg-slate-50' : 'border-gray-200'" class="p-3 border-2 rounded-2xl text-center transition-all">
                                    <p class="font-bold text-xs">College Email</p>
                                </div>
                            </label>
                        </div>

                        <!-- ID Card Upload option -->
                        <div x-show="method === 'id_card'" class="space-y-2">
                            <label for="id_card" class="block text-sm font-semibold">Student ID Card (Image)</label>
                            <div class="relative border-2 border-dashed border-gray-200 rounded-3xl p-6 text-center hover:border-[var(--ig-accent)] transition cursor-pointer">
                                <input type="file" id="id_card" name="id_card" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileLabel(this)">
                                <div class="space-y-1.5">
                                    <span class="text-2xl block">📷</span>
                                    <span class="text-xs font-bold text-slate-700 block" id="file-label">Choose ID Card Image</span>
                                    <span class="text-[10px] text-[var(--ig-muted)] block">PNG, JPG, or JPEG up to 5MB</span>
                                </div>
                            </div>
                            <span class="text-xs text-red-500 mt-1 hidden" id="err-id_card"></span>
                        </div>

                        <!-- College Email option -->
                        <div x-show="method === 'email'" class="space-y-2">
                            <label for="college_email" class="block text-sm font-semibold">College Email Address</label>
                            <input id="college_email" name="college_email" type="email" placeholder="you@college.edu" class="ig-input" />
                            <span class="text-xs text-red-500 mt-1 hidden" id="err-college_email"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="button" onclick="goToStep(2)" class="flex-1 ig-btn ig-btn-ghost py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Back
                        </button>
                        <button type="button" onclick="skipVerification()" class="flex-1 ig-btn ig-btn-ghost border-amber-300 hover:bg-amber-50 text-amber-800 py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Skip & Verify Later
                        </button>
                        <button type="submit" class="flex-1 ig-btn ig-btn-primary py-3 justify-center text-xs font-bold rounded-2xl">
                            <span>Submit & Done</span>
                            <span class="arrow">→</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Onboarding Step Navigation & AJAX script -->
    <script>
        let currentStep = 1;

        function goToStep(step) {
            // Hide all contents
            document.getElementById('content-step-1').classList.add('hidden');
            document.getElementById('content-step-2').classList.add('hidden');
            document.getElementById('content-step-3').classList.add('hidden');

            // Show selected step content
            document.getElementById(`content-step-${step}`).classList.remove('hidden');

            // Manage indicators
            updateIndicators(step);
            currentStep = step;
        }

        function updateIndicators(step) {
            const steps = [1, 2, 3];
            steps.forEach(s => {
                const pill = document.getElementById(`pill-step-${s}`);
                if (s === step) {
                    pill.className = "flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-accent)] text-white text-xs font-black transition-all shadow-md";
                } else if (s < step) {
                    pill.className = "flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-600 text-white text-xs font-bold transition-all";
                    pill.innerHTML = `✓ <span class="hidden sm:inline">${s === 1 ? 'Basics' : 'Skills'}</span>`;
                } else {
                    pill.className = "flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-bg-2)] text-[var(--ig-muted)] text-xs font-bold transition-all border border-[var(--ig-line)]";
                    pill.innerHTML = `<span>${s}</span> <span class="hidden sm:inline">${s === 2 ? 'Skills' : 'Verify'}</span>`;
                }

                // Line connection
                if (s < 3) {
                    const line = document.getElementById(`line-step-${s}`);
                    if (s < step) {
                        line.className = "w-8 h-0.5 bg-emerald-600 transition-all duration-300";
                    } else {
                        line.className = "w-8 h-0.5 bg-[var(--ig-line-2)] transition-all duration-300";
                    }
                }
            });
        }

        function updateFileLabel(input) {
            if (input.files && input.files[0]) {
                document.getElementById('file-label').innerText = input.files[0].name;
            }
        }

        // Form Submit: Step 1
        function submitStep1(e) {
            e.preventDefault();
            const form = document.getElementById('form-step-1');
            const data = new FormData(form);
            
            // Clear errors
            document.querySelectorAll("[id^='err-']").forEach(span => span.classList.add('hidden'));

            fetch("{{ route('student.onboarding.step1') }}", {
                method: "POST",
                body: data,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    goToStep(2);
                } else if (res.errors) {
                    showValidationErrors(res.errors);
                }
            })
            .catch(err => console.error(err));
        }

        // Form Submit: Step 2
        function submitStep2(e) {
            e.preventDefault();
            const form = document.getElementById('form-step-2');
            const data = new FormData(form);
            
            document.querySelectorAll("[id^='err-']").forEach(span => span.classList.add('hidden'));

            fetch("{{ route('student.onboarding.step2') }}", {
                method: "POST",
                body: data,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    goToStep(3);
                } else if (res.errors) {
                    showValidationErrors(res.errors);
                }
            })
            .catch(err => console.error(err));
        }

        // Form Submit: Step 3 (Verification)
        function submitStep3(e) {
            e.preventDefault();
            const form = document.getElementById('form-step-3');
            const data = new FormData(form);
            
            document.querySelectorAll("[id^='err-']").forEach(span => span.classList.add('hidden'));

            fetch("{{ route('student.onboarding.step3') }}", {
                method: "POST",
                body: data,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.redirect) {
                    window.location.href = res.redirect;
                } else if (res.errors) {
                    showValidationErrors(res.errors);
                }
            })
            .catch(err => console.error(err));
        }

        // Skip Verification handler
        function skipVerification() {
            fetch("{{ route('student.onboarding.step3') }}", {
                method: "POST",
                body: JSON.stringify({ skip: true, _token: "{{ csrf_token() }}" }),
                headers: { 
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.redirect) {
                    window.location.href = res.redirect;
                }
            })
            .catch(err => console.error(err));
        }

        function showValidationErrors(errors) {
            for (const key in errors) {
                const span = document.getElementById(`err-${key}`);
                if (span) {
                    span.innerText = errors[key][0];
                    span.classList.remove('hidden');
                }
            }
        }
    </script>
</x-app-layout>
