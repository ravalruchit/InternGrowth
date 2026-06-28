<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Premium Feature · Unlocked</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    🚀 AI Career Coach
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1">Get customized career pathing, role preparation, and matching advice based on your profile.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost text-xs">← Back to Dashboard</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sidebar Tips -->
            <div class="lg:col-span-4 space-y-6">
                <div class="ig-card p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-3">🔥 Coach Suggestions</h3>
                    <div class="space-y-3" style="font-size:12px; line-height: 1.5;">
                        <div class="p-3 bg-white border border-slate-100 rounded-xl">
                            <span class="font-bold text-slate-800">Optimize Profile:</span> Adding 3 more skills like Node.js can boost matching rate by 40%.
                        </div>
                        <div class="p-3 bg-white border border-slate-100 rounded-xl">
                            <span class="font-bold text-slate-800">Target Role:</span> Backend Developer is currently hot in Bangalore and remote startups.
                        </div>
                        <div class="p-3 bg-white border border-slate-100 rounded-xl">
                            <span class="font-bold text-slate-800">Mock Prep:</span> Practice Database Design mock interviews before next application.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat interface -->
            <div class="lg:col-span-8">
                <div class="ig-card p-6 min-h-[500px] flex flex-col justify-between" style="border: 1px solid var(--ig-line);">
                    <!-- Chat Messages list -->
                    <div class="space-y-4 overflow-y-auto max-h-[380px] pr-2" id="chat-box" style="flex:1;">
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm">🤖</span>
                            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs max-w-[80%] text-slate-800 leading-relaxed shadow-sm">
                                Hello, <strong>{{ auth()->user()->name }}</strong>! I am your AI Career Coach. I've reviewed your skills and verified achievements. Ask me anything about roles, resumes, matching insights, or mock session preparations!
                            </div>
                        </div>
                    </div>

                    <!-- Input message box -->
                    <div class="mt-6 pt-4 border-t border-[var(--ig-line)]">
                        <form onsubmit="sendCoachMessage(event)" class="flex gap-2">
                            <input type="text" id="message-input" placeholder="e.g. How can I improve my backend engineering profile?" required
                                   style="flex:1;padding:12px 16px;background:var(--ig-bg);border:1px solid var(--ig-line-2);border-radius:14px;font-size:12px;color:var(--ig-ink);box-sizing:border-box;outline:none;">
                            <button type="submit" style="background:var(--ig-accent);color:white;font-weight:700;font-size:12px;padding:10px 24px;border-radius:14px;border:none;cursor:pointer;">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sendCoachMessage(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const chatBox = document.getElementById('chat-box');
            if (!input.value.trim()) return;

            // Append user message
            const userDiv = document.createElement('div');
            userDiv.className = "flex items-start gap-3 justify-end";
            userDiv.innerHTML = `
                <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 text-xs max-w-[80%] text-slate-800 leading-relaxed shadow-sm">
                    ${input.value}
                </div>
                <span class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-sm">👤</span>
            `;
            chatBox.appendChild(userDiv);

            const promptText = input.value;
            input.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            // Generate AI Response
            setTimeout(() => {
                const aiDiv = document.createElement('div');
                aiDiv.className = "flex items-start gap-3";
                
                let response = "Based on your verified skills, here is a recommendation: focus on expanding projects in Github and complete assessments. Startups prioritizing candidates with active streaks.";
                if (promptText.toLowerCase().includes('backend') || promptText.toLowerCase().includes('php')) {
                    response = "You have completed multiple task updates in PHP and backend development. To highlight your profile, build a REST API in Node.js or complete the databases assessment to get the badge.";
                } else if (promptText.toLowerCase().includes('resume') || promptText.toLowerCase().includes('improve')) {
                    response = "To optimize your resume, ensure your projects are structured with clean problem descriptions, metrics (e.g. optimized DB execution by 20%), and list verified links to pull requests.";
                }

                aiDiv.innerHTML = `
                    <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm">🤖</span>
                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs max-w-[80%] text-slate-800 leading-relaxed shadow-sm">
                        ${response}
                    </div>
                `;
                chatBox.appendChild(aiDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 800);
        }
    </script>
</x-app-layout>
