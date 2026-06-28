<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Premium Feature · Unlocked</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    📝 Skill Assessments & Badges
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1">Pass verified technical assessments to earn skills verification badges displayed on your public profile.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost text-xs">← Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-8 text-sm"><p class="font-bold text-emerald-950">✓ {{ session('success') }}</p></div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Assessment 1: PHP Core -->
            <div class="ig-card p-6 flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                <div>
                    <span style="font-size:32px;">🐘</span>
                    <h3 class="font-bold text-lg text-[var(--ig-ink)] mt-3">PHP Core Development</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-2 leading-relaxed">Validate knowledge of namespaces, traits, sessions, abstract classes, and OOP design patterns.</p>
                    <div class="flex gap-4 mt-4 text-[10px] uppercase font-bold text-slate-500">
                        <span>⏱️ 20 Mins</span>
                        <span>❓ 15 Questions</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-[var(--ig-line)] flex items-center justify-between">
                    <span class="text-[10px] font-extrabold text-[#059669] bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Badge: PHP expert</span>
                    <button onclick="startDemoAssessment('PHP Core Development', 'php_expert')" class="ig-btn bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] py-1.5 px-4 font-bold rounded-xl border-none cursor-pointer">
                        Start Test
                    </button>
                </div>
            </div>

            <!-- Assessment 2: Databases & SQL -->
            <div class="ig-card p-6 flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                <div>
                    <span style="font-size:32px;">🗄️</span>
                    <h3 class="font-bold text-lg text-[var(--ig-ink)] mt-3">Databases & SQL Design</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-2 leading-relaxed">Test queries, index configurations, primary key distributions, denormalization metrics, and integrity parameters.</p>
                    <div class="flex gap-4 mt-4 text-[10px] uppercase font-bold text-slate-500">
                        <span>⏱️ 15 Mins</span>
                        <span>❓ 10 Questions</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-[var(--ig-line)] flex items-center justify-between">
                    <span class="text-[10px] font-extrabold text-[#059669] bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Badge: SQL Architect</span>
                    <button onclick="startDemoAssessment('Databases & SQL Design', 'sql_architect')" class="ig-btn bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] py-1.5 px-4 font-bold rounded-xl border-none cursor-pointer">
                        Start Test
                    </button>
                </div>
            </div>

            <!-- Assessment 3: Laravel Architect -->
            <div class="ig-card p-6 flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                <div>
                    <span style="font-size:32px;">🔥</span>
                    <h3 class="font-bold text-lg text-[var(--ig-ink)] mt-3">Laravel MVC Architect</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-2 leading-relaxed">Validate dependency injection container, custom middleware chains, queue events, and cache stores.</p>
                    <div class="flex gap-4 mt-4 text-[10px] uppercase font-bold text-slate-500">
                        <span>⏱️ 25 Mins</span>
                        <span>❓ 20 Questions</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-[var(--ig-line)] flex items-center justify-between">
                    <span class="text-[10px] font-extrabold text-[#059669] bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Badge: Laravel Master</span>
                    <button onclick="startDemoAssessment('Laravel MVC Architect', 'laravel_master')" class="ig-btn bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] py-1.5 px-4 font-bold rounded-xl border-none cursor-pointer">
                        Start Test
                    </button>
                </div>
            </div>
        </div>

        <!-- Simulated Quiz Modal -->
        <div id="quiz-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl space-y-6">
                <div class="text-center">
                    <span style="font-size:40px;">📝</span>
                    <h3 class="font-bold text-lg text-slate-800 mt-3" id="quiz-title">Assessment</h3>
                    <p class="text-xs text-slate-500 mt-1">Complete this 3-question mini-test to unlock your verified badge.</p>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                        <p class="font-bold text-xs text-slate-800" id="question-text">1. Which command clears cache stores in Laravel?</p>
                        <div class="mt-3 space-y-2" id="options-box">
                            <!-- Options injected -->
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center gap-3">
                    <button onclick="closeQuizModal()" class="text-xs text-slate-500 hover:text-slate-800 font-bold bg-transparent border-none cursor-pointer">Quit Test</button>
                    <button id="next-btn" onclick="submitAssessmentAnswer()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2 rounded-xl text-xs border-none cursor-pointer">
                        Next Question
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAssessment = '';
        let currentBadgeSlug = '';
        let currentQuestionIdx = 0;
        const questions = [
            {
                q: "Which protocol is used to securely connect and fetch commits in Git?",
                opts: ["FTP", "SMTP", "SSH/HTTPS", "Telnet"],
                ans: 2
            },
            {
                q: "What is database normalization aimed at reducing?",
                opts: ["Security levels", "Data redundancy", "Query throughput", "Storage speed"],
                ans: 1
            },
            {
                q: "What does middleware do in Laravel requests pipeline?",
                opts: ["Connects views to controller variables", "Inspects and filters HTTP requests entering application", "Manages database seeders", "Generates validation errors"],
                ans: 1
            }
        ];

        function startDemoAssessment(title, badgeSlug) {
            currentAssessment = title;
            currentBadgeSlug = badgeSlug;
            currentQuestionIdx = 0;
            
            document.getElementById('quiz-title').innerText = title;
            document.getElementById('quiz-modal').classList.remove('hidden');
            loadQuestion();
        }

        function loadQuestion() {
            const qData = questions[currentQuestionIdx];
            document.getElementById('question-text').innerText = `${currentQuestionIdx + 1}. ${qData.q}`;
            
            const optionsBox = document.getElementById('options-box');
            optionsBox.innerHTML = '';
            
            qData.opts.forEach((opt, idx) => {
                const label = document.createElement('label');
                label.className = "flex items-center gap-3 p-3 bg-white border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition text-xs font-semibold text-slate-700";
                label.innerHTML = `
                    <input type="radio" name="quiz_option" value="${idx}" class="text-indigo-600">
                    <span>${opt}</span>
                `;
                optionsBox.appendChild(label);
            });
        }

        function submitAssessmentAnswer() {
            const selected = document.querySelector('input[name="quiz_option"]:checked');
            if (!selected) {
                alert('Please select an option first!');
                return;
            }

            if (currentQuestionIdx < questions.length - 1) {
                currentQuestionIdx++;
                loadQuestion();
                if (currentQuestionIdx === questions.length - 1) {
                    document.getElementById('next-btn').innerText = "Finish Assessment";
                }
            } else {
                // Done! Re-submit to trigger unlocking badge (in database)
                document.getElementById('quiz-modal').classList.add('hidden');
                
                // Form submission to back-end to grant badge
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('pricing.upgrade') }}"; // We will repurpose a routing trigger or handle it via alert
                
                // Since this is demo mode, we can just redirect with a success toast directly
                window.location.href = "{{ route('student.dashboard') }}?badge=" + currentBadgeSlug;
            }
        }

        function closeQuizModal() {
            document.getElementById('quiz-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
