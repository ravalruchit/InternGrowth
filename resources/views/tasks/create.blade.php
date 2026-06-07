<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-gradient-to-br from-white via-indigo-50 to-purple-50 rounded-2xl shadow-2xl p-8 border border-indigo-100 animate-scale-in">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Post New Task</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl mb-6">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form (left 2/3) -->
                <div class="lg:col-span-2">
                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6" id="task-form">
                        @csrf

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Task Title</label>
                            <input type="text" name="title" id="f-title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all bg-white/80"
                                   placeholder="e.g., Build a responsive landing page">
                            @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                                <span id="desc-chars" class="ml-2 text-xs font-normal text-gray-400">0 characters</span>
                            </label>
                            <textarea name="description" id="f-description" rows="6" required
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all bg-white/80 resize-none"
                                      placeholder="Describe the task requirements, deliverables, and expectations...">{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">More detail = more points for the student ✨</p>
                            @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Skills --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Required Skills
                                <span id="skill-count" class="ml-2 text-xs font-normal text-indigo-500">0 selected</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($skills as $skill)
                                    <label class="skill-label relative flex items-center p-3 bg-white/80 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:shadow-md transition-all group">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                               class="skill-check w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                               {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $skill->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('skills')<p class="text-red-500 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Stipend --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stipend (Optional)
                                <span class="ml-1 text-xs font-normal text-gray-400">— higher stipend = more points</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₹</span>
                                <input type="number" name="stipend" id="f-stipend" value="{{ old('stipend') }}" step="1" min="0"
                                       class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all bg-white/80"
                                       placeholder="0">
                            </div>
                            @error('stipend')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center space-x-4 pt-2">
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-2xl transform hover:scale-105 transition-all flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Post Task</span>
                            </button>
                            <a href="{{ route('dashboard') }}"
                               class="px-6 py-4 border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Live Points Card (right 1/3) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <!-- Points Display -->
                        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-6 text-white shadow-2xl mb-4">
                            <p class="text-sm font-medium text-indigo-200 mb-1">Auto-calculated Reward</p>
                            <div class="flex items-end gap-2 mb-4">
                                <span id="pts-display" class="text-6xl font-black tabular-nums transition-all duration-300">50</span>
                                <span class="text-xl font-semibold text-indigo-200 mb-2">pts</span>
                            </div>
                            <p class="text-xs text-indigo-200">Points are calculated automatically based on your task details. The better the task, the more students earn.</p>
                        </div>

                        <!-- Breakdown Card -->
                        <div class="bg-white rounded-2xl shadow-lg border border-indigo-100 p-5 space-y-3">
                            <p class="text-sm font-bold text-gray-700 mb-3">Points Breakdown</p>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span> Base
                                </span>
                                <span class="font-semibold text-gray-800">50 pts</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span> Stipend bonus
                                </span>
                                <span id="b-stipend" class="font-semibold text-green-600">+0 pts</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-purple-400 inline-block"></span> Skills bonus
                                </span>
                                <span id="b-skills" class="font-semibold text-purple-600">+0 pts</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-pink-400 inline-block"></span> Description depth
                                </span>
                                <span id="b-desc" class="font-semibold text-pink-600">+0 pts</span>
                            </div>

                            <div class="border-t border-gray-100 pt-3 mt-1">
                                <div class="flex justify-between items-center text-sm font-bold">
                                    <span class="text-gray-700">Total</span>
                                    <span id="b-total" class="text-indigo-600 text-base">50 pts</span>
                                </div>
                            </div>

                            <!-- Progress bar -->
                            <div class="mt-2">
                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                    <span>50</span><span>430</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div id="pts-bar"
                                         class="h-2.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-500"
                                         style="width: 0%"></div>
                                </div>
                                <p id="pts-label" class="text-xs text-center text-gray-400 mt-1">Fill in more details to increase points</p>
                            </div>
                        </div>

                        <!-- Tips -->
                        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-700 space-y-1">
                            <p class="font-semibold text-amber-800 mb-1">💡 Tips to maximise points</p>
                            <p>• Write a detailed description (every 100 chars = +10 pts, max +50)</p>
                            <p>• Add more required skills (+20 pts each, max +100)</p>
                            <p>• Offer a stipend (+0.5 pts per ₹1, max +200)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Formula (mirrors PHP) ──────────────────────────────────────────
        function calcPoints(stipend, skillCount, descLen) {
            const base        = 50;
            const stipendBonus = Math.min(Math.floor(stipend * 0.5), 200);
            const skillBonus   = Math.min(skillCount * 20, 100);
            const descBonus    = Math.min(Math.floor(descLen / 100) * 10, 50);
            return { base, stipendBonus, skillBonus, descBonus,
                     total: base + stipendBonus + skillBonus + descBonus };
        }

        // ── Animated counter ──────────────────────────────────────────────
        let currentPts = 50;
        function animateTo(target) {
            const el    = document.getElementById('pts-display');
            const start = currentPts;
            const diff  = target - start;
            const steps = 20;
            let   step  = 0;
            const timer = setInterval(() => {
                step++;
                const val = Math.round(start + diff * (step / steps));
                el.textContent = val;
                if (step >= steps) { clearInterval(timer); currentPts = target; }
            }, 16);
        }

        // ── Labels ────────────────────────────────────────────────────────
        const labels = [
            [50,  100, 'Just getting started…'],
            [101, 200, 'Looking good! 👍'],
            [201, 300, 'Great task! Students will love this ⭐'],
            [301, 400, 'Excellent! High-value task 🔥'],
            [401, 430, 'Maximum value! Top-tier task 🏆'],
        ];
        function getLabel(pts) {
            for (const [lo, hi, txt] of labels)
                if (pts >= lo && pts <= hi) return txt;
            return '';
        }

        // ── Update UI ─────────────────────────────────────────────────────
        function update() {
            const stipend    = parseFloat(document.getElementById('f-stipend').value) || 0;
            const skillCount = document.querySelectorAll('.skill-check:checked').length;
            const descLen    = document.getElementById('f-description').value.length;

            const { base, stipendBonus, skillBonus, descBonus, total } = calcPoints(stipend, skillCount, descLen);

            animateTo(total);

            document.getElementById('b-stipend').textContent = '+' + stipendBonus + ' pts';
            document.getElementById('b-skills').textContent  = '+' + skillBonus   + ' pts';
            document.getElementById('b-desc').textContent    = '+' + descBonus    + ' pts';
            document.getElementById('b-total').textContent   = total + ' pts';

            // Progress bar (50 = 0%, 430 = 100%)
            const pct = Math.min(((total - 50) / 380) * 100, 100);
            document.getElementById('pts-bar').style.width = pct + '%';
            document.getElementById('pts-label').textContent = getLabel(total);

            // Char counter
            document.getElementById('desc-chars').textContent = descLen + ' characters';

            // Skill count
            document.getElementById('skill-count').textContent = skillCount + ' selected';

            // Pulse the card on change
            const card = document.getElementById('pts-display').closest('.bg-gradient-to-br');
            card.classList.add('scale-105');
            setTimeout(() => card.classList.remove('scale-105'), 200);
        }

        // ── Listeners ─────────────────────────────────────────────────────
        document.getElementById('f-description').addEventListener('input', update);
        document.getElementById('f-stipend').addEventListener('input', update);
        document.querySelectorAll('.skill-check').forEach(cb => cb.addEventListener('change', update));

        // Highlight selected skill labels
        document.querySelectorAll('.skill-check').forEach(cb => {
            cb.addEventListener('change', function() {
                const label = this.closest('.skill-label');
                if (this.checked) {
                    label.classList.add('border-indigo-500', 'bg-indigo-50', 'shadow-md');
                } else {
                    label.classList.remove('border-indigo-500', 'bg-indigo-50', 'shadow-md');
                }
            });
            // Init state for old() values
            if (cb.checked) cb.closest('.skill-label').classList.add('border-indigo-500', 'bg-indigo-50', 'shadow-md');
        });

        // Run once on load for old() values
        update();
    </script>
</x-app-layout>
