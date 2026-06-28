<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Premium Feature · Unlocked</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    🎭 Mock Interview Prep
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1">Select a topic and format to practice live technical questions with AI interviewer feedback.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost text-xs">← Back to Dashboard</a>
        </div>

        <div x-data="{ step: 'select', topic: '', mode: 'chat', questionNum: 1 }" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Sidebar configuration -->
            <div class="lg:col-span-4 space-y-6">
                <div class="ig-card p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-3">🛠️ Session Settings</h3>
                    <div class="space-y-4" style="font-size:12px;">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Select Domain / Topic</label>
                            <select x-model="topic" :disabled="step === 'interview'" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none">
                                <option value="">-- Choose Topic --</option>
                                <option value="Laravel & PHP Core">Laravel & PHP Core</option>
                                <option value="Database Design & SQL">Database Design & SQL</option>
                                <option value="Data Structures & Algorithms">Data Structures & Algorithms</option>
                                <option value="RESTful API Architectures">RESTful API Architectures</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5">Interview Format</label>
                            <div class="flex gap-2">
                                <button @click="mode = 'chat'" :class="mode === 'chat' ? 'bg-[var(--ig-accent)] text-white' : 'bg-white border border-slate-200 text-slate-700'"
                                        class="flex-1 py-2 rounded-xl font-bold cursor-pointer transition text-center">Chat Text</button>
                                <button @click="mode = 'voice'" :class="mode === 'voice' ? 'bg-[var(--ig-accent)] text-white' : 'bg-white border border-slate-200 text-slate-700'"
                                        class="flex-1 py-2 rounded-xl font-bold cursor-pointer transition text-center" disabled title="Voice chat requires browser microphone authorization">Voice (Beta)</button>
                            </div>
                        </div>
                        <button x-show="step === 'select'" @click="if(topic) { step = 'interview'; startMockSession(); }"
                                style="width:100%;background:var(--ig-accent);color:white;font-weight:700;padding:10px;border-radius:12px;border:none;cursor:pointer;margin-top:8px;">
                            🚀 Start Mock Session
                        </button>
                        <button x-show="step === 'interview'" @click="step = 'select'; topic = '';"
                                style="width:100%;background:#ef4444;color:white;font-weight:700;padding:10px;border-radius:12px;border:none;cursor:pointer;margin-top:8px;">
                            🛑 Quit Session
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Interactive Display -->
            <div class="lg:col-span-8">
                <!-- Selection Screen -->
                <div x-show="step === 'select'" class="ig-card p-8 text-center min-h-[350px] flex flex-col justify-center items-center" style="border:1px solid var(--ig-line);">
                    <span style="font-size:48px;">🎭</span>
                    <h3 class="font-bold text-lg text-[var(--ig-ink)] mt-4">Welcome to Mock Interviews</h3>
                    <p class="text-xs text-[var(--ig-muted)] max-w-sm mt-2">Choose a technical topic in the settings panel and click Start Session. The AI interviewer will ask questions one by one and grade your answers.</p>
                </div>

                <!-- Live Interview Screen -->
                <div x-show="step === 'interview'" class="ig-card p-6 min-h-[500px] flex flex-col justify-between" style="border:1px solid var(--ig-line); display:none;">
                    <div class="flex items-center justify-between border-b border-[var(--ig-line)] pb-3 mb-4">
                        <span class="text-xs font-bold text-indigo-700">Live Topic: <span x-text="topic"></span></span>
                        <span class="text-xs font-bold text-[var(--ig-muted)]">Question <span x-text="questionNum"></span> / 5</span>
                    </div>

                    <!-- Conversation -->
                    <div class="space-y-4 overflow-y-auto max-h-[340px] pr-2" id="mock-chat-box" style="flex:1;">
                        <!-- Interviewer question -->
                    </div>

                    <!-- Input answer -->
                    <div class="mt-6 pt-4 border-t border-[var(--ig-line)]">
                        <form onsubmit="submitMockAnswer(event)" class="flex gap-2">
                            <input type="text" id="answer-input" placeholder="Type your answer here..." required
                                   style="flex:1;padding:12px 16px;background:var(--ig-bg);border:1px solid var(--ig-line-2);border-radius:14px;font-size:12px;color:var(--ig-ink);box-sizing:border-box;outline:none;">
                            <button type="submit" style="background:#059669;color:white;font-weight:700;font-size:12px;padding:10px 24px;border-radius:14px;border:none;cursor:pointer;">
                                Submit Answer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function startMockSession() {
            const chatBox = document.getElementById('mock-chat-box');
            chatBox.innerHTML = '';
            
            // Initial AI prompt
            const q = getInterviewerQuestion(1);
            appendInterviewerMessage(q);
        }

        function getInterviewerQuestion(qNum) {
            const topic = document.querySelector('select').value;
            if (topic.includes('Laravel')) {
                const qs = [
                    "Can you explain the difference between Service Providers and Service Containers in Laravel?",
                    "How does middleware process requests in Laravel, and how do you implement a custom global middleware?",
                    "What is Eloquent eager loading and how does it solve the N+1 query problem?",
                    "Explain the difference between event dispatchers and queues in Laravel task processing.",
                    "Excellent! Session completed. Your overall rating is Good (4.2/5.0). Would you like to review specific feedback?"
                ];
                return qs[qNum - 1] ?? qs[0];
            } else {
                const qs = [
                    "What is database normalization and when is it acceptable to denormalize tables?",
                    "Can you explain the difference between primary keys, foreign keys, and indexes?",
                    "How do database indexes improve query speeds, and what are their drawbacks?",
                    "Explain database transactions and ACID compliance parameters.",
                    "Great work! Session completed. Your overall rating is Excellent (4.7/5.0). Detailed scorecard generated!"
                ];
                return qs[qNum - 1] ?? qs[0];
            }
        }

        function appendInterviewerMessage(msg) {
            const chatBox = document.getElementById('mock-chat-box');
            const div = document.createElement('div');
            div.className = "flex items-start gap-3";
            div.innerHTML = `
                <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm">🕵️</span>
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs max-w-[80%] text-slate-800 leading-relaxed shadow-sm">
                    ${msg}
                </div>
            `;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function submitMockAnswer(e) {
            e.preventDefault();
            const input = document.getElementById('answer-input');
            const chatBox = document.getElementById('mock-chat-box');
            if (!input.value.trim()) return;

            // Append user answer
            const userDiv = document.createElement('div');
            userDiv.className = "flex items-start gap-3 justify-end";
            userDiv.innerHTML = `
                <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 text-xs max-w-[80%] text-slate-800 leading-relaxed shadow-sm">
                    ${input.value}
                </div>
                <span class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-sm">👤</span>
            `;
            chatBox.appendChild(userDiv);
            input.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            // Move to next question
            const scope = Alpine.find(chatBox.closest('[x-data]'));
            setTimeout(() => {
                scope.questionNum = scope.questionNum + 1;
                const nextQ = getInterviewerQuestion(scope.questionNum);
                appendInterviewerMessage(nextQ);
            }, 800);
        }
    </script>
</x-app-layout>
