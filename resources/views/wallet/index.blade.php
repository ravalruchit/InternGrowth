<x-app-layout>
    <div class="ig-container">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Ledger & Vault</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    My <span class="ig-serif text-[var(--ig-accent)]">Wallet.</span><br>
                    Track your earnings & deposits.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                @if(auth()->user()->isStartup())
                    <a href="{{ route('wallet.topup') }}" class="ig-btn ig-btn-primary">
                        <span>+ Request Top-up</span><span class="arrow">→</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
            <!-- Balance Card -->
            <div class="lg:col-span-4 ig-card-dark p-8 relative overflow-hidden ig-reveal">
                <div class="absolute -top-24 -right-24 w-48 h-48 rounded-full blur-3xl opacity-30" style="background:var(--ig-accent)"></div>
                <div class="relative">
                    <p class="ig-eyebrow mb-3" style="color:#9C9580">Available Balance</p>
                    <p class="ig-display text-5xl text-white">₹{{ number_format($profile->wallet_balance, 2) }}</p>
                    
                    @if(auth()->user()->isStartup())
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <p class="text-sm leading-relaxed" style="color:#C9C1AE">
                                Need to add funds to hire more talent?
                            </p>
                            <a href="{{ route('wallet.topup') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--ig-lime)] hover:underline mt-2">
                                Request a top-up
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    @else
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <p class="text-[12.5px] leading-relaxed" style="color:#C9C1AE">
                                Withdraw your earnings directly to your bank account anytime.
                            </p>
                            <a href="{{ route('student.wallet.withdraw') }}" class="ig-btn ig-btn-primary mt-4 w-full justify-center">
                                <span>Request Withdrawal</span><span class="arrow">→</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Ledger -->
            <div class="lg:col-span-8 ig-card p-6 md:p-8 ig-reveal" data-reveal-delay="100">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-[var(--ig-line)]">
                    <h2 class="ig-display text-2xl">Transaction History</h2>
                    <span class="ig-mono text-xs text-[var(--ig-muted)]">{{ count($transactions) }} records</span>
                </div>

                <div class="divide-y divide-[var(--ig-line)]">
                    @forelse($transactions as $transaction)
                        @php
                            $isCredit = in_array($transaction->type, ['credit', 'escrow_release']);
                            $sign = $isCredit ? '+' : '-';
                        @endphp
                        <div class="py-5 flex justify-between items-center hover:bg-[var(--ig-bg)] transition-colors px-3 rounded-xl">
                            <div>
                                <p class="font-semibold text-[15px] text-[var(--ig-ink)]">{{ $transaction->description }}</p>
                                <p class="ig-mono text-[11px] text-[var(--ig-muted)] mt-1">{{ $transaction->created_at->format('M d, Y · h:i A') }}</p>
                                @if($transaction->reference_id)
                                    <p class="ig-mono text-[10px] text-[var(--ig-faint)] mt-0.5">Ref: {{ $transaction->reference_id }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="ig-display text-xl {{ $isCredit ? 'text-[var(--ig-ink)]' : 'text-[var(--ig-rose)]' }}">
                                    {{ $sign }}₹{{ number_format($transaction->amount, 2) }}
                                </span>
                                <p class="ig-mono text-[10px] text-[var(--ig-muted)] capitalize mt-1">{{ str_replace('_', ' ', $transaction->type) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-[var(--ig-muted)]">
                            <p class="ig-display text-xl mb-1">No transaction history yet.</p>
                            <p class="text-xs">Your completed tasks and payments will appear here.</p>
                        </div>
                    @endforelse
                </div>

                @if($transactions->hasPages())
                    <div class="pt-6 border-t border-[var(--ig-line)] mt-6">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
