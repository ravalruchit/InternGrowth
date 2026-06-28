<x-app-layout>
    <div class="ig-container py-10 max-w-2xl">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 border-b border-[var(--ig-line)] pb-6 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Full-Time Conversion Offer</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    Convert <span class="ig-serif text-[var(--ig-accent)]">{{ $offer->student->user->name }}</span>
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1 uppercase tracking-wider">
                    Send a direct permanent full-time position contract
                </p>
            </div>
            <div>
                <a href="{{ route('startup.team.index') }}" class="ig-btn ig-btn-ghost text-xs py-2">
                    <span>← Back to Team</span>
                </a>
            </div>
        </div>

        <!-- Banners -->
        @if(session('error'))
            <div class="ig-banner mb-8 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-955 font-bold shadow-sm">
                <p>⚠️ {{ session('error') }}</p>
            </div>
        @endif

        <div class="ig-card p-6 md:p-8">
            <form method="POST" action="{{ route('startup.team.convert', $offer->id) }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Job Title</label>
                    <input type="text" name="title" required value="Full-time Software Engineer" 
                           class="w-full px-4 py-2.5 bg-white border border-gray-250 rounded-xl text-sm focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Job Description</label>
                    <textarea name="description" required rows="4" 
                              class="w-full px-4 py-2.5 bg-white border border-gray-250 rounded-xl text-sm focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent resize-none">Following your outstanding performance during your internship, we are thrilled to offer you a full-time position on our engineering team...</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Annual Compensation (CTC in INR)</label>
                    <input type="number" name="compensation" required min="0" placeholder="e.g. 600000"
                           class="w-full px-4 py-2.5 bg-white border border-gray-250 rounded-xl text-sm focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent font-semibold">
                    <p class="text-[10px] text-gray-500 mt-1.5">Note: Pre-placement offer conversion incurs a one-time 5% CTC success fee (₹30,000 for a ₹6,00.000 package) debited from your wallet balance upon acceptance.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Contract Terms & Perks</label>
                    <textarea name="contract_terms" placeholder="Include remote settings, healthcare details, paid leaves..." rows="3" 
                              class="w-full px-4 py-2.5 bg-white border border-gray-250 rounded-xl text-sm focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent resize-none"></textarea>
                </div>

                <div class="pt-4 border-t border-[var(--ig-line)] flex items-center justify-between">
                    <button type="submit" class="w-full ig-btn justify-center py-3 bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold border-none shadow-sm text-center">
                        💼 Send Full-Time Job Offer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
