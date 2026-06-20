<x-app-layout>
    <div class="ig-container max-w-4xl py-12 space-y-8 ig-anim-fade-up">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-[var(--ig-line)] pb-5">
            <div>
                <p class="ig-eyebrow mb-2">— Billing & Wallet</p>
                <h1 class="ig-display text-3xl sm:text-4xl text-[var(--ig-ink)]">
                    Request Wallet <span class="ig-serif text-[var(--ig-accent)]">Top-up.</span>
                </h1>
            </div>
            <a href="{{ route('wallet.index') }}" class="ig-btn ig-btn-ghost text-xs py-2 px-4">
                ← Back to Wallet
            </a>
        </div>

        <!-- Notification Banners -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success">
                <span class="text-lg">✓</span>
                <div>
                    <h3 class="font-bold text-[var(--ig-ink)]">Request Submitted</h3>
                    <p class="text-sm text-[var(--ig-muted)]">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="ig-banner ig-banner-warn">
                <span class="text-lg">⚠️</span>
                <div>
                    <h3 class="font-bold text-[var(--ig-ink)]">Attention Required</h3>
                    <p class="text-sm text-[var(--ig-muted)]">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Current Balance -->
        <div class="ig-card-dark p-6 relative overflow-hidden bg-gradient-to-br from-[var(--ig-surface-ink)] to-[#1b2027]">
            <p class="ig-eyebrow text-[#FAF6EB]/60">Current Wallet Balance</p>
            <p class="ig-stat-num text-4xl sm:text-5xl text-[var(--ig-lime)] mt-2 font-bold">₹{{ number_format($startup->wallet_balance, 2) }}</p>
        </div>

        <!-- How it works & Payment Details -->
        <div class="ig-card p-6 bg-[var(--ig-bg-2)] border-[var(--ig-line)]">
            <h2 class="text-md font-bold text-[var(--ig-ink)] mb-3 flex items-center gap-2">
                💡 How to add funds
            </h2>
            <ol class="text-sm text-[var(--ig-ink-2)] space-y-2 list-decimal list-inside pl-1">
                <li>Transfer the desired amount to our bank account or UPI listed below.</li>
                <li>Submit the request form below with the amount and transaction reference (UTR ID).</li>
                <li>Our operations team will verify the payment and credit your wallet within 24 hours.</li>
            </ol>
            
            <div class="mt-5 bg-white rounded-xl p-5 text-sm text-[var(--ig-ink-2)] border border-[var(--ig-line)] space-y-3">
                <p class="font-bold text-[var(--ig-ink)] border-b border-[var(--ig-line)] pb-2 uppercase tracking-wider text-xs">Official Payment Details</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-mono">
                    <p><span class="text-[var(--ig-muted)]">Bank Name:</span> HDFC Bank</p>
                    <p><span class="text-[var(--ig-muted)]">Account Name:</span> InternGrowth Pvt Ltd</p>
                    <p><span class="text-[var(--ig-muted)]">Account No:</span> 1234567890</p>
                    <p><span class="text-[var(--ig-muted)]">IFSC Code:</span> HDFC0001234</p>
                    <p class="sm:col-span-2"><span class="text-[var(--ig-muted)]">UPI ID:</span> <span class="text-[var(--ig-accent)] font-semibold select-all">interngrowth@hdfcbank</span></p>
                </div>
            </div>
        </div>

        <!-- Request Form -->
        <div class="ig-card p-6 sm:p-8">
            <h2 class="text-lg font-bold text-[var(--ig-ink)] mb-6">Submit Top-up Request</h2>
            
            <form method="POST" action="{{ route('wallet.topup.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Amount (₹) <span class="text-[var(--ig-rose)]">*</span></label>
                        <input type="number" name="amount" min="100" step="1" required
                            value="{{ old('amount') }}"
                            class="ig-input"
                            placeholder="Minimum ₹100">
                        @error('amount') <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Payment Method <span class="text-[var(--ig-rose)]">*</span></label>
                        <select name="payment_method" required class="ig-input">
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer (NEFT/IMPS)</option>
                            <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method') <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Transaction Reference / UTR</label>
                    <input type="text" name="transaction_reference"
                        value="{{ old('transaction_reference') }}"
                        class="ig-input"
                        placeholder="e.g. UTR123456789 or UPI transaction ID">
                    @error('transaction_reference') <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Additional Notes</label>
                    <textarea name="notes" rows="3" class="ig-input resize-none"
                        placeholder="Any additional details to help us verify your transaction...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-[var(--ig-rose)] text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="ig-btn ig-btn-primary w-full justify-center">
                    Submit Top-up Request
                </button>
            </form>
        </div>

        <!-- Past Requests -->
        <div class="ig-card overflow-hidden">
            <div class="p-5 border-b border-[var(--ig-line)] bg-[var(--ig-bg-2)]">
                <h2 class="text-md font-bold text-[var(--ig-ink)]">My Top-up Requests</h2>
            </div>
            
            <div class="divide-y divide-[var(--ig-line)]">
                @forelse($requests as $req)
                    <div class="p-5 flex items-center justify-between hover:bg-[var(--ig-bg-2)]/30 transition-colors">
                        <div>
                            <p class="font-bold text-[var(--ig-ink)] text-base">₹{{ number_format($req->amount, 2) }}
                                <span class="text-xs text-[var(--ig-muted)] font-mono font-normal ml-2">via {{ strtoupper(str_replace('_', ' ', $req->payment_method)) }}</span>
                            </p>
                            @if($req->transaction_reference)
                                <p class="text-xs text-[var(--ig-muted)] mt-1 font-mono">Ref: {{ $req->transaction_reference }}</p>
                            @endif
                            <p class="text-[10px] text-[var(--ig-faint)] mt-1">{{ $req->created_at->format('M d, Y h:i A') }}</p>
                            @if($req->admin_notes)
                                <p class="text-xs text-[var(--ig-rose)] mt-1 italic">Admin: {{ $req->admin_notes }}</p>
                            @endif
                        </div>
                        <div>
                            @if($req->status === 'pending')
                                <span class="ig-chip ig-chip-warn">⏳ Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="ig-chip ig-chip-success">✓ Approved</span>
                            @else
                                <span class="ig-chip ig-chip-danger">✗ Rejected</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-[var(--ig-muted)] text-sm">No top-up requests yet.</div>
                @endforelse
            </div>
            
            @if($requests->hasPages())
                <div class="p-4 border-t border-[var(--ig-line)]">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
