<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Risk Management / Operations</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Circumvention <span class="ig-serif text-[var(--ig-accent)]">Auditor.</span><br>
                    Verify placements & prevent leakage.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Banners -->
        @if($errors->any())
            <div class="ig-banner mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-sm text-red-955 font-semibold shadow-sm">
                <p class="font-black text-red-955 mb-1.5">Please fix the following issues:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Auditor Ledger -->
        <div class="ig-card p-0 overflow-hidden mb-10 ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] flex items-center justify-between bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">Completed & Joined Placements</h2>
                <span class="ig-chip ig-chip-accent">
                    {{ $placements->count() }} active/ended contracts
                </span>
            </div>

            <div class="divide-y divide-[var(--ig-line)]">
                @forelse($placements as $offer)
                    <div class="p-6 md:p-8 hover:bg-[var(--ig-bg-2)]/40 transition duration-300">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                            
                            <!-- Left: Placement Details -->
                            <div class="lg:col-span-8 space-y-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="ig-tag uppercase tracking-wider {{ $offer->offer_type === 'internship' ? 'ig-chip-accent' : 'ig-chip-ink' }}">
                                        {{ $offer->offer_type }}
                                    </span>
                                    <h3 class="font-bold text-xl text-[var(--ig-ink)]">{{ $offer->title }}</h3>
                                    
                                    @if($offer->status === 'bypassed_penalized')
                                        <span class="ig-chip ig-chip-danger font-bold text-xs">⚠️ PENALIZED (₹{{ number_format($offer->bypass_penalty_charged, 2) }})</span>
                                    @elseif($offer->audited_at)
                                        <span class="ig-chip ig-chip-success text-xs">✓ Audited & Verified</span>
                                    @else
                                        <span class="ig-chip ig-chip-warn text-xs">⏳ Unaudited</span>
                                    @endif
                                </div>

                                <!-- Grid info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/50 border border-[var(--ig-line-2)] rounded-2xl p-5 text-sm">
                                    <div>
                                        <p class="text-xs uppercase font-extrabold text-[var(--ig-muted)] mb-1">— Startup</p>
                                        <p class="font-bold text-[var(--ig-ink)]">{{ $offer->startup->company_name }}</p>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->startup->user->email }}</p>
                                        <p class="text-xs mt-2">Wallet Balance: <strong class="{{ $offer->startup->wallet_balance < 0 ? 'text-red-500' : 'text-emerald-700' }}">₹{{ number_format($offer->startup->wallet_balance, 2) }}</strong></p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase font-extrabold text-[var(--ig-muted)] mb-1">— Candidate</p>
                                        <p class="font-bold text-[var(--ig-ink)]">{{ $offer->student->user->name }}</p>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->student->user->email }}</p>
                                        <p class="text-xs mt-2">Current IPRS score: <strong>{{ round($offer->student->reputationScore->overall_score ?? 50) }}/100</strong></p>
                                    </div>
                                </div>

                                <!-- Compensation & Dates -->
                                <div class="flex flex-wrap gap-x-8 gap-y-2 text-xs text-[var(--ig-ink-2)] font-medium">
                                    <p>Compensation: <strong>₹{{ number_format($offer->compensation, 2) }} / {{ $offer->compensation_period }}</strong></p>
                                    <p>Timeline: <strong>{{ $offer->start_date->format('M d, Y') }} — {{ $offer->end_date ? $offer->end_date->format('M d, Y') : 'Ongoing' }}</strong></p>
                                    @if($offer->audited_at)
                                        <p class="text-emerald-700">Last Audited: <strong>{{ $offer->audited_at->diffForHumans() }}</strong></p>
                                    @endif
                                </div>

                                @if($offer->bypass_notes)
                                    <div class="bg-red-50/50 border border-red-200/50 rounded-xl p-4 text-xs text-red-900">
                                        <p class="font-bold mb-1">Audit Notes:</p>
                                        <p class="italic">"{{ $offer->bypass_notes }}"</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Auditor Toolbox Actions -->
                            <div class="lg:col-span-4 space-y-4">
                                <p class="text-xs uppercase font-extrabold text-[var(--ig-muted)] tracking-wider">— Social Auditing Links</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="https://www.linkedin.com/search/results/all/?keywords={{ urlencode($offer->student->user->name . ' ' . $offer->startup->company_name) }}" 
                                       target="_blank" class="ig-btn justify-center text-[10px] py-2 bg-[#0A66C2] hover:bg-[#004182] border-none text-white font-bold rounded-xl transition">
                                        🔗 LinkedIn Check
                                    </a>
                                    <a href="https://github.com/search?q={{ urlencode($offer->student->user->name) }}&type=users" 
                                       target="_blank" class="ig-btn justify-center text-[10px] py-2 bg-slate-900 hover:bg-slate-950 border-none text-white font-bold rounded-xl transition">
                                        🐙 GitHub Search
                                    </a>
                                </div>

                                <div class="pt-4 border-t border-[var(--ig-line)] flex flex-col gap-2">
                                    @if($offer->status !== 'bypassed_penalized')
                                        <!-- Form to mark audited -->
                                        <form method="POST" action="{{ route('admin.circumvention.audit', $offer->id) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full ig-btn ig-btn-ghost justify-center text-[11px] font-extrabold py-2 border-dashed border-slate-300 hover:border-slate-500">
                                                ✓ Mark Audited (Clean)
                                            </button>
                                        </form>

                                        <!-- Trigger to reveal penalty form -->
                                        <button onclick="togglePenaltyForm({{ $offer->id }})" class="w-full ig-btn justify-center text-[11px] bg-[var(--ig-rose)] hover:bg-red-700 text-white font-extrabold py-2 border-none">
                                            🚨 Flag Circumvention & Fine
                                        </button>

                                        <!-- Hidden Penalty Form -->
                                        <div id="penalty-container-{{ $offer->id }}" class="hidden bg-red-50/20 border border-red-200/40 rounded-2xl p-4 mt-2 space-y-3">
                                            <form method="POST" action="{{ route('admin.circumvention.penalize', $offer->id) }}" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Penalty Amount (₹)</label>
                                                    <input type="number" name="penalty_amount" required min="0" value="20000" 
                                                           class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs focus:ring-2 focus:ring-red-500 focus:border-transparent font-semibold">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-1">Auditor Notes / Reason</label>
                                                    <textarea name="notes" placeholder="Explain how circumvention was detected (e.g. LinkedIn update verified)..." rows="2" 
                                                              class="w-full px-3 py-2 bg-white border border-gray-250 rounded-xl text-xs focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                                                </div>
                                                <button type="submit" class="w-full bg-[var(--ig-rose)] hover:bg-red-700 text-white font-extrabold py-2 px-4 rounded-xl text-[10px] transition text-center shadow-sm">
                                                    Deduct Fine & Penalize
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <button disabled class="w-full ig-btn justify-center text-[11px] bg-red-100 text-red-500 font-bold py-2 border-none cursor-not-allowed">
                                            Penalized & Closed
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-[var(--ig-muted)]">
                        <svg class="mx-auto h-12 w-12 text-[var(--ig-faint)] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <p class="font-bold text-base text-[var(--ig-ink)]">No completed or active placements to audit.</p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1">Once startups confirm candidate joinings, they will list here for compliance reviews.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function togglePenaltyForm(offerId) {
            const container = document.getElementById('penalty-container-' + offerId);
            if (container) {
                container.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
