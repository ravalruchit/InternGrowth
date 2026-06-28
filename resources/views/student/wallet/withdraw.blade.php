<x-app-layout>
    <div class="ig-container py-12">
        <!-- Flash Messages & Validation Errors -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-6 text-sm">
                <p class="font-bold text-emerald-950">✓ {{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-950 font-bold shadow-sm">
                <p>⚠️ {{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="ig-banner mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-sm text-red-955 font-semibold shadow-sm">
                <p class="font-black text-red-955 mb-1.5">Please fix the following issues:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Wallet & Payouts</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Request <span class="ig-serif text-[var(--ig-accent)]">Withdrawal.</span><br>
                    Transfer earnings to your bank or UPI.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('wallet.index') }}" class="ig-btn ig-btn-secondary">
                    <span>← Back to Wallet</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
            <!-- Left: Request Form -->
            <div class="lg:col-span-5 ig-card p-6 md:p-8 ig-reveal">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-[var(--ig-line)]">
                    <h2 class="ig-display text-2xl">Payout Details</h2>
                    <span class="ig-mono text-xs text-[var(--ig-muted)]">Balance: ₹{{ number_format($student->wallet_balance, 2) }}</span>
                </div>

                @if(session('error'))
                    <div class="bg-[var(--ig-rose)]/10 border border-[var(--ig-rose)]/20 text-[var(--ig-rose)] p-4 rounded-xl mb-6 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if($hasPending)
                    <div class="bg-[var(--ig-accent)]/10 border border-[var(--ig-accent)]/20 text-[var(--ig-ink)] p-5 rounded-xl mb-6 text-sm leading-relaxed">
                        <p class="font-bold mb-1">Pending Request Active</p>
                        <p class="text-[12.5px] text-[var(--ig-muted)]">You can only have one pending withdrawal request at a time. Please wait for our admin team to process your current request before submitting another one.</p>
                    </div>
                @else
                    <form action="{{ route('student.wallet.withdraw.store') }}" method="POST">
                        @csrf
                        
                        <!-- Amount -->
                        <div class="mb-6">
                            <label for="amount" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Withdrawal Amount (₹)</label>
                            <input type="number" name="amount" id="amount" min="100" step="any" value="{{ old('amount') }}" placeholder="e.g. 500" class="ig-input" required>
                            <p class="text-[11px] text-[var(--ig-muted)] mt-1.5 font-mono">Minimum: ₹100. Available: ₹{{ number_format($student->wallet_balance, 2) }}</p>
                            @error('amount')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Method -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Payout Method</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="border border-[var(--ig-line)] rounded-xl p-4 flex items-center gap-3 cursor-pointer hover:bg-[var(--ig-bg)] transition-colors">
                                    <input type="radio" name="method" value="bank" id="method_bank" {{ old('method', 'bank') === 'bank' ? 'checked' : '' }} class="text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]" onclick="toggleFields('bank')">
                                    <span class="text-sm font-semibold text-[var(--ig-ink)]">Bank Transfer</span>
                                </label>
                                <label class="border border-[var(--ig-line)] rounded-xl p-4 flex items-center gap-3 cursor-pointer hover:bg-[var(--ig-bg)] transition-colors">
                                    <input type="radio" name="method" value="upi" id="method_upi" {{ old('method') === 'upi' ? 'checked' : '' }} class="text-[var(--ig-accent)] focus:ring-[var(--ig-accent)]" onclick="toggleFields('upi')">
                                    <span class="text-sm font-semibold text-[var(--ig-ink)]">UPI Payout</span>
                                </label>
                            </div>
                            @error('method')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Details Group -->
                        <div id="bank_details_group" class="space-y-4 mb-6">
                            <div>
                                <label for="bank_name" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" placeholder="e.g. HDFC Bank" class="ig-input">
                                @error('bank_name')
                                    <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="account_number" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">Account Number</label>
                                <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" placeholder="e.g. 50100234567890" class="ig-input">
                                @error('account_number')
                                    <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="ifsc_code" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">IFSC Code</label>
                                <input type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="e.g. HDFC0000060" class="ig-input">
                                @error('ifsc_code')
                                    <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- UPI Details Group -->
                        <div id="upi_details_group" class="mb-6 hidden">
                            <div>
                                <label for="upi_id" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">UPI ID</label>
                                <input type="text" name="upi_id" id="upi_id" value="{{ old('upi_id') }}" placeholder="e.g. name@okhdfcbank" class="ig-input">
                                @error('upi_id')
                                    <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="ig-btn ig-btn-primary w-full justify-center">
                            <span>Submit Request</span><span class="arrow">→</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Right: History List -->
            <div class="lg:col-span-7 ig-card p-6 md:p-8 ig-reveal" data-reveal-delay="100">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-[var(--ig-line)]">
                    <h2 class="ig-display text-2xl">Withdrawal Log</h2>
                    <span class="ig-mono text-xs text-[var(--ig-muted)]">{{ count($requests) }} requests</span>
                </div>

                <div class="divide-y divide-[var(--ig-line)]">
                    @forelse($requests as $req)
                        <div class="py-5 flex flex-col md:flex-row md:items-center md:justify-between hover:bg-[var(--ig-bg)] transition-colors px-3 rounded-xl gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="ig-display text-lg font-semibold text-[var(--ig-ink)]">₹{{ number_format($req->amount, 2) }}</span>
                                    <span class="text-xs text-[var(--ig-muted)]">• {{ strtoupper($req->method) }}</span>
                                </div>
                                <p class="ig-mono text-[11px] text-[var(--ig-muted)]">Requested on {{ $req->created_at->format('M d, Y · h:i A') }}</p>
                                
                                @if($req->method === 'bank')
                                    <p class="text-[12px] text-[var(--ig-muted)] mt-1.5">
                                        {{ $req->bank_name }} • A/C ending in ...{{ substr($req->account_number, -4) }}
                                    </p>
                                @else
                                    <p class="text-[12px] text-[var(--ig-muted)] mt-1.5">
                                        UPI: {{ $req->upi_id }}
                                    </p>
                                @endif

                                @if($req->admin_notes)
                                    <div class="bg-[var(--ig-bg)] border border-[var(--ig-line)] text-[11.5px] p-2.5 rounded-lg mt-3 text-[var(--ig-muted)] leading-relaxed">
                                        <span class="font-bold text-[var(--ig-ink)] block mb-0.5">Admin Remark:</span>
                                        {{ $req->admin_notes }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-left md:text-right flex flex-col items-start md:items-end justify-center">
                                @if($req->status === 'approved')
                                    <span class="ig-chip ig-chip-lime">Approved & Transferred</span>
                                    <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-1">Processed {{ $req->processed_at?->format('M d, Y') }}</p>
                                @elseif($req->status === 'rejected')
                                    <span class="ig-chip ig-chip-rose">Rejected</span>
                                    <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-1">Processed {{ $req->processed_at?->format('M d, Y') }}</p>
                                @else
                                    <span class="ig-chip ig-chip-amber animate-pulse">Pending Review</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-[var(--ig-muted)]">
                            <p class="ig-display text-xl mb-1">No withdrawal requests found.</p>
                            <p class="text-xs">Your payout requests and transfer logs will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Javascript -->
    <script>
        function toggleFields(method) {
            const bankGroup = document.getElementById('bank_details_group');
            const upiGroup = document.getElementById('upi_details_group');
            
            if (method === 'bank') {
                bankGroup.classList.remove('hidden');
                upiGroup.classList.add('hidden');
                document.getElementById('bank_name').required = true;
                document.getElementById('account_number').required = true;
                document.getElementById('ifsc_code').required = true;
                document.getElementById('upi_id').required = false;
            } else {
                bankGroup.classList.add('hidden');
                upiGroup.classList.remove('hidden');
                document.getElementById('bank_name').required = false;
                document.getElementById('account_number').required = false;
                document.getElementById('ifsc_code').required = false;
                document.getElementById('upi_id').required = true;
            }
        }

        // Run on load to set correct fields based on old input
        document.addEventListener('DOMContentLoaded', function() {
            const isUpi = document.getElementById('method_upi').checked;
            toggleFields(isUpi ? 'upi' : 'bank');
        });
    </script>
</x-app-layout>
