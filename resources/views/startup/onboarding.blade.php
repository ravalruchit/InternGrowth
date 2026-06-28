<x-app-layout>
    <div class="ig-container py-12 max-w-2xl">
        <!-- Progress Stepper -->
        <div class="mb-10 text-center">
            <h1 class="ig-display text-4xl mb-2">Set up company profile</h1>
            <p class="text-xs text-[var(--ig-muted)]">3 quick steps to activate your workspace</p>

            <div class="flex items-center justify-center gap-4 mt-6">
                <!-- Step 1 pill -->
                <div id="pill-step-1" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-accent)] text-white text-xs font-black transition-all">
                    <span>1</span> <span class="hidden sm:inline">Basics</span>
                </div>
                <div class="w-8 h-0.5 bg-[var(--ig-line-2)]" id="line-step-1"></div>
                
                <!-- Step 2 pill -->
                <div id="pill-step-2" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-bg-2)] text-[var(--ig-muted)] text-xs font-bold transition-all border border-[var(--ig-line)]">
                    <span>2</span> <span class="hidden sm:inline">Verify (Optional)</span>
                </div>
                <div class="w-8 h-0.5 bg-[var(--ig-line-2)]" id="line-step-2"></div>
                
                <!-- Step 3 pill -->
                <div id="pill-step-3" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-bg-2)] text-[var(--ig-muted)] text-xs font-bold transition-all border border-[var(--ig-line)]">
                    <span>3</span> <span class="hidden sm:inline">First Task</span>
                </div>
            </div>
        </div>

        <!-- Onboarding Card -->
        <div class="ig-card p-8 bg-white border border-[var(--ig-line)] rounded-3xl relative overflow-hidden">
            
            <!-- Step 1 content -->
            <div id="content-step-1" class="space-y-6">
                <div>
                    <h3 class="ig-display text-2xl text-[var(--ig-ink)] font-extrabold mb-1">Company Details</h3>
                    <p class="text-xs text-[var(--ig-muted)]">Let candidates know who you are and what industry you focus on.</p>
                </div>

                <form id="form-step-1" class="space-y-5" onsubmit="submitStep1(event)">
                    @csrf
                    <div>
                        <label for="company_name" class="block text-sm font-semibold mb-2">Company / Startup Name</label>
                        <input id="company_name" name="company_name" type="text" required value="{{ old('company_name', $profile->company_name) }}" placeholder="e.g. Acme Corp" class="ig-input" />
                        <span class="text-xs text-red-500 mt-1 hidden" id="err-company_name"></span>
                    </div>

                    <div>
                        <label for="industry" class="block text-sm font-semibold mb-2">Industry Area</label>
                        <input id="industry" name="industry" type="text" required value="{{ old('industry', $profile->industry) }}" placeholder="e.g. SaaS, Artificial Intelligence" class="ig-input" />
                        <span class="text-xs text-red-500 mt-1 hidden" id="err-industry"></span>
                    </div>

                    <button type="submit" class="ig-btn ig-btn-primary w-full justify-center mt-4">
                        <span>Continue</span>
                        <span class="arrow">→</span>
                    </button>
                </form>
            </div>

            <!-- Step 2 content (Verification) -->
            <div id="content-step-2" class="space-y-6 hidden">
                <div>
                    <h3 class="ig-display text-2xl text-[var(--ig-ink)] font-extrabold mb-1">Company Verification</h3>
                    <p class="text-xs text-[var(--ig-muted)]">Upload a Certificate of Incorporation, GST invoice, or company registry document. You can skip this and upload later.</p>
                </div>

                <form id="form-step-2" class="space-y-6" onsubmit="submitStep2(event)" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="registration_doc" class="block text-sm font-semibold mb-2">Verification Document</label>
                        <div class="relative border-2 border-dashed border-gray-200 rounded-3xl p-8 text-center hover:border-[var(--ig-accent)] transition cursor-pointer">
                            <input type="file" id="registration_doc" name="registration_doc" accept=".pdf,.png,.jpg,.jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileLabel(this)">
                            <div class="space-y-1.5">
                                <span class="text-2xl block">📄</span>
                                <span class="text-xs font-bold text-slate-700 block" id="file-label">Choose Document File</span>
                                <span class="text-[10px] text-[var(--ig-muted)] block">PDF, PNG, or JPG up to 10MB</span>
                            </div>
                        </div>
                        <span class="text-xs text-red-500 mt-1.5 block hidden" id="err-registration_doc"></span>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" onclick="goToStep(1)" class="flex-1 ig-btn ig-btn-ghost py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Back
                        </button>
                        <button type="button" onclick="skipStep2()" class="flex-1 ig-btn ig-btn-ghost border-amber-355 text-amber-800 py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Skip & Verify Later
                        </button>
                        <button type="submit" class="flex-1 ig-btn ig-btn-primary py-3 justify-center text-xs font-bold rounded-2xl">
                            <span>Continue</span>
                            <span class="arrow">→</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3 content (First Task - Optional / Mandatory but customizable) -->
            <div id="content-step-3" class="space-y-6 hidden">
                <div>
                    <h3 class="ig-display text-2xl text-[var(--ig-ink)] font-extrabold mb-1">Post your first task</h3>
                    <p class="text-xs text-[var(--ig-muted)]">Post a short mock task to attract students immediately, or skip to view the dashboard.</p>
                </div>

                <form id="form-step-3" class="space-y-5" onsubmit="submitStep3(event)">
                    @csrf
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-2">Task Title</label>
                        <input id="title" name="title" type="text" placeholder="e.g. Design Landing Page Mockup in Figma" class="ig-input" />
                        <span class="text-xs text-red-500 mt-1 hidden" id="err-title"></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stipend" class="block text-sm font-semibold mb-2">Stipend reward (INR)</label>
                            <input id="stipend" name="stipend" type="number" min="0" placeholder="₹1,500" class="ig-input" />
                            <span class="text-xs text-red-500 mt-1 hidden" id="err-stipend"></span>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">Required Skills</label>
                            <div class="relative">
                                <select name="skills[]" multiple class="ig-input h-10 py-1" style="min-height: 48px;">
                                    @foreach($skills as $skill)
                                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-xs text-red-500 mt-1 hidden" id="err-skills"></span>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold mb-2">Short Description & Requirements</label>
                        <textarea id="description" name="description" rows="3" placeholder="Provide a brief task description and criteria..." class="ig-input py-2"></textarea>
                        <span class="text-xs text-red-500 mt-1 hidden" id="err-description"></span>
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="button" onclick="goToStep(2)" class="flex-1 ig-btn ig-btn-ghost py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Back
                        </button>
                        <button type="button" onclick="skipStep3()" class="flex-1 ig-btn ig-btn-ghost py-3 justify-center text-xs font-bold rounded-2xl cursor-pointer">
                            Skip posting task
                        </button>
                        <button type="submit" class="flex-1 ig-btn ig-btn-primary py-3 justify-center text-xs font-bold rounded-2xl">
                            <span>Post & Go</span>
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
            document.getElementById('content-step-1').classList.add('hidden');
            document.getElementById('content-step-2').classList.add('hidden');
            document.getElementById('content-step-3').classList.add('hidden');

            document.getElementById(`content-step-${step}`).classList.remove('hidden');
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
                    pill.innerHTML = `✓ <span class="hidden sm:inline">${s === 1 ? 'Basics' : 'Verify'}</span>`;
                } else {
                    pill.className = "flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--ig-bg-2)] text-[var(--ig-muted)] text-xs font-bold transition-all border border-[var(--ig-line)]";
                    pill.innerHTML = `<span>${s}</span> <span class="hidden sm:inline">${s === 2 ? 'Verify' : 'First Task'}</span>`;
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
            
            document.querySelectorAll("[id^='err-']").forEach(span => span.classList.add('hidden'));

            fetch("{{ route('startup.onboarding.step1') }}", {
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

            fetch("{{ route('startup.onboarding.step2') }}", {
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

        function skipStep2() {
            fetch("{{ route('startup.onboarding.step2') }}", {
                method: "POST",
                body: JSON.stringify({ skip: true, _token: "{{ csrf_token() }}" }),
                headers: { 
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    goToStep(3);
                }
            })
            .catch(err => console.error(err));
        }

        // Form Submit: Step 3
        function submitStep3(e) {
            e.preventDefault();
            const form = document.getElementById('form-step-3');
            const data = new FormData(form);
            
            document.querySelectorAll("[id^='err-']").forEach(span => span.classList.add('hidden'));

            fetch("{{ route('startup.onboarding.step3') }}", {
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

        function skipStep3() {
            fetch("{{ route('startup.onboarding.step3') }}", {
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
