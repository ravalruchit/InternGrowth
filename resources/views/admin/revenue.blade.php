<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Platform Operations</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    📊 Revenue & Unit Economics
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1.5">View real-time SaaS metrics, recurring revenue counts, and server infrastructure overhead margins.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost text-xs">← Dashboard</a>
                <a href="{{ route('admin.subscriptions.index') }}" class="ig-btn text-xs bg-indigo-600 text-white font-bold hover:bg-indigo-700">💳 Subscriptions Ledger</a>
            </div>
        </div>

        <!-- Row 1: KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
            <!-- MRR -->
            <div class="ig-card p-5" style="border: 1px solid var(--ig-line);">
                <p class="ig-eyebrow text-slate-500">MRR</p>
                <p class="text-2xl font-black text-gray-900 mt-2 font-poppins">₹{{ number_format($metrics['mrr'], 2) }}</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Monthly Recurring</p>
            </div>
            <!-- ARR -->
            <div class="ig-card p-5" style="border: 1px solid var(--ig-line);">
                <p class="ig-eyebrow text-slate-500">ARR</p>
                <p class="text-2xl font-black text-gray-900 mt-2 font-poppins">₹{{ number_format($metrics['arr'], 2) }}</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Annual Run Rate</p>
            </div>
            <!-- Subscribers -->
            <div class="ig-card p-5" style="border: 1px solid var(--ig-line);">
                <p class="ig-eyebrow text-slate-500">Subscribers</p>
                <p class="text-2xl font-black text-indigo-700 mt-2 font-poppins">{{ $metrics['active_subscribers'] }}</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Pro: {{ $metrics['student_pro_count'] }} | Growth: {{ $metrics['startup_growth_count'] }}</p>
            </div>
            <!-- ARPU -->
            <div class="ig-card p-5" style="border: 1px solid var(--ig-line);">
                <p class="ig-eyebrow text-slate-500">ARPU</p>
                <p class="text-2xl font-black text-gray-900 mt-2 font-poppins">₹{{ number_format($metrics['arpu'], 2) }}</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Avg Revenue / Sub</p>
            </div>
            <!-- Conversion -->
            <div class="ig-card p-5" style="border: 1px solid var(--ig-line);">
                <p class="ig-eyebrow text-slate-500">Conversion Rate</p>
                <p class="text-2xl font-black text-[#059669] mt-2 font-poppins">{{ $metrics['conversion_rate'] }}%</p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Subscribed Ratio</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-10">
            <!-- Left: Streams breakdown -->
            <div class="lg:col-span-6 space-y-6">
                <div class="ig-card p-6" style="border:1px solid var(--ig-line);">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-4">— Platform Revenue Breakdown</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-600">SaaS Subscriptions (Monthly Run)</span>
                                <span class="text-slate-900 font-bold">₹{{ number_format($metrics['mrr'], 2) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $metrics['total_revenue'] > 0 ? ($metrics['mrr'] / $metrics['total_revenue']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-600">Hiring Fees & Success Commissions</span>
                                <span class="text-slate-900 font-bold">₹{{ number_format($metrics['total_hiring_fees'], 2) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-teal-600 h-full rounded-full" style="width: {{ $metrics['total_revenue'] > 0 ? ($metrics['total_hiring_fees'] / $metrics['total_revenue']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[var(--ig-line)] flex justify-between font-extrabold text-sm">
                            <span class="text-slate-800">Total Account Inflows</span>
                            <span class="text-[var(--ig-accent)]">₹{{ number_format($metrics['total_revenue'], 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Unit Economics & Margins -->
                <div class="ig-card p-6" style="border:1px solid var(--ig-line);">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-4">— Platform Operating Overhead</h3>
                    <div class="grid grid-cols-2 gap-4 text-xs font-semibold text-slate-600 mb-6">
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-[10px] text-gray-400 uppercase font-bold">Server Infrastructure</span>
                            <span class="block text-slate-800 font-black mt-1">₹{{ number_format($serverCosts) }} / mo</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-[10px] text-gray-400 uppercase font-bold">AI Processing (Gemini API)</span>
                            <span class="block text-slate-800 font-black mt-1">₹{{ number_format($aiCosts) }} / mo</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-[10px] text-gray-400 uppercase font-bold">Cloud Asset Storage</span>
                            <span class="block text-slate-800 font-black mt-1">₹{{ number_format($storageCosts) }} / mo</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-[10px] text-gray-400 uppercase font-bold">SMTP Mail Services</span>
                            <span class="block text-slate-800 font-black mt-1">₹{{ number_format($emailCosts) }} / mo</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-[var(--ig-line)] pt-4">
                        <div>
                            <span class="text-xs text-slate-500 font-bold">Gross Margin:</span>
                            <span class="text-sm font-black text-[#059669] block mt-0.5">{{ $grossMargin }}%</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-500 font-bold">Total Operations Cost:</span>
                            <span class="text-sm font-black text-slate-800 block mt-0.5">₹{{ number_format($totalOperatingCosts) }} / mo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Recent transactions ledger -->
            <div class="lg:col-span-6">
                <div class="ig-card p-6" style="border:1px solid var(--ig-line);">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-4">— Recent Inflow Transactions</h3>
                    
                    <div class="space-y-3">
                        @forelse($recentTransactions as $tx)
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <span class="text-[9px] uppercase font-black px-2 py-0.5 rounded bg-indigo-50 text-indigo-700">
                                        {{ str_replace('_', ' ', $tx->type) }}
                                    </span>
                                    <h4 class="font-bold text-xs text-slate-800 mt-1 truncate">{{ $tx->description }}</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Ref: {{ $tx->reference_id }} | {{ $tx->created_at->format('d M H:i') }}</p>
                                </div>
                                <span class="font-extrabold text-sm text-[#059669] flex-shrink-0">
                                    +₹{{ number_format($tx->amount, 0) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-[var(--ig-muted)] text-center py-8 italic">No matching transactions logged yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
