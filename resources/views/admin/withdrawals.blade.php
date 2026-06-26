<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Financial Operations</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Withdrawals <span class="ig-serif text-[var(--ig-accent)]">Queue.</span><br>
                    Review student payout requests.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <div class="flex flex-wrap gap-3 justify-start md:justify-end">
                    <a href="{{ route('admin.wallets') }}" class="ig-btn ig-btn-ghost">
                        <span>Startup Wallets</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost">
                        <span>← Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        @php
            $pendingCount = $requests->where('status', 'pending')->count();
            $approvedCount = $requests->where('status', 'approved')->count();
            $approvedSum = $requests->where('status', 'approved')->sum('amount');
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-12 ig-anim-fade-up">
            <div class="ig-card p-6 border-l-4 border-amber-500">
                <p class="ig-eyebrow mb-1 text-amber-700">Pending Review</p>
                <p class="ig-stat-num text-4xl mt-3 text-[var(--ig-ink)]">{{ $pendingCount }} requests</p>
            </div>
            <div class="ig-card p-6 border-l-4 border-[var(--ig-lime)]">
                <p class="ig-eyebrow mb-1 text-[var(--ig-lime-deep)]">Approved Payouts</p>
                <p class="ig-stat-num text-4xl mt-3 text-[var(--ig-ink)]">₹{{ number_format($approvedSum, 2) }}</p>
            </div>
            <div class="ig-card p-6 border-l-4 border-blue-500">
                <p class="ig-eyebrow mb-1 text-blue-700">Total Processed</p>
                <p class="ig-stat-num text-4xl mt-3 text-[var(--ig-ink)]">{{ $approvedCount }} transfers</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-4 border-b border-[var(--ig-line)] pb-4 mb-8 ig-reveal">
            <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $status === 'pending' ? 'bg-[var(--ig-accent)] text-white' : 'text-[var(--ig-muted)] hover:bg-[var(--ig-bg)]' }}">
                Pending Queue ({{ $requests->where('status', 'pending')->count() }})
            </a>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $status === 'approved' ? 'bg-[var(--ig-accent)] text-white' : 'text-[var(--ig-muted)] hover:bg-[var(--ig-bg)]' }}">
                Approved Logs
            </a>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $status === 'rejected' ? 'bg-[var(--ig-accent)] text-white' : 'text-[var(--ig-muted)] hover:bg-[var(--ig-bg)]' }}">
                Rejected Requests
            </a>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'all']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $status === 'all' ? 'bg-[var(--ig-accent)] text-white' : 'text-[var(--ig-muted)] hover:bg-[var(--ig-bg)]' }}">
                All Transactions
            </a>
        </div>

        @if(session('success'))
            <div class="bg-[var(--ig-lime)]/10 border border-[var(--ig-lime)]/20 text-[var(--ig-lime-deep)] p-4 rounded-xl mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @error('error')
            <div class="bg-[var(--ig-rose)]/10 border border-[var(--ig-rose)]/20 text-[var(--ig-rose)] p-4 rounded-xl mb-6 text-sm">
                {{ $message }}
            </div>
        @enderror

        {{-- Requests Queue --}}
        <div class="space-y-6">
            @forelse($requests as $req)
                <div class="ig-card p-6 md:p-8 relative overflow-hidden group ig-reveal">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        {{-- Left Col: Amount and Details --}}
                        <div class="lg:col-span-5 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="ig-display text-3xl font-bold text-[var(--ig-ink)]">₹{{ number_format($req->amount, 2) }}</span>
                                <span class="ig-chip {{ $req->status === 'approved' ? 'ig-chip-lime' : ($req->status === 'rejected' ? 'ig-chip-rose' : 'ig-chip-amber') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-[var(--ig-ink)]">
                                    Student: <span class="font-normal">{{ $req->studentProfile->user->name }}</span>
                                </p>
                                <p class="text-xs font-mono text-[var(--ig-muted)]">
                                    Email: {{ $req->studentProfile->user->email }}
                                </p>
                                <p class="text-xs font-mono text-[var(--ig-muted)]">
                                    Wallet Balance: ₹{{ number_format($req->studentProfile->wallet_balance, 2) }}
                                </p>
                                <p class="text-xs text-[var(--ig-muted)]">
                                    Submitted: {{ $req->created_at->format('M d, Y · h:i A') }}
                                </p>
                            </div>
                        </div>

                        {{-- Mid Col: Payment Credentials --}}
                        <div class="lg:col-span-4 bg-[var(--ig-bg)] border border-[var(--ig-line)] rounded-2xl p-5 space-y-3 font-mono text-xs">
                            <p class="font-sans font-bold text-[var(--ig-ink)] text-sm mb-1">
                                Payout Method: {{ strtoupper($req->method) }}
                            </p>
                            @if($req->method === 'bank')
                                <p><span class="text-[var(--ig-muted)]">Bank Name:</span> {{ $req->bank_name }}</p>
                                <p><span class="text-[var(--ig-muted)]">Account No:</span> {{ $req->account_number }}</p>
                                <p><span class="text-[var(--ig-muted)]">IFSC Code:</span> {{ $req->ifsc_code }}</p>
                            @else
                                <p><span class="text-[var(--ig-muted)]">UPI ID:</span> {{ $req->upi_id }}</p>
                            @endif
                        </div>

                        {{-- Right Col: Actions --}}
                        <div class="lg:col-span-3 lg:text-right flex flex-col gap-3 justify-center h-full">
                            @if($req->status === 'pending')
                                <!-- Approve Form Toggle -->
                                <button onclick="toggleActionForm('approve_{{ $req->id }}')" class="ig-btn ig-btn-primary w-full justify-center">
                                    <span>Approve & Pay</span>
                                </button>
                                
                                <!-- Reject Form Toggle -->
                                <button onclick="toggleActionForm('reject_{{ $req->id }}')" class="ig-btn ig-btn-secondary w-full justify-center">
                                    <span>Reject Request</span>
                                </button>
                            @else
                                <div class="space-y-1 font-mono text-[11px] text-[var(--ig-muted)] lg:text-right">
                                    <p>Processed: {{ $req->processed_at?->format('M d, Y · h:i A') }}</p>
                                    @if($req->admin_notes)
                                        <p class="font-sans text-xs bg-[var(--ig-bg)] border border-[var(--ig-line)] p-2 rounded-lg mt-2 text-left">
                                            <span class="font-bold block text-[10px] uppercase">Notes:</span>
                                            {{ $req->admin_notes }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Sliding Forms for Approve/Reject Actions --}}
                    @if($req->status === 'pending')
                        <!-- Approve Form -->
                        <div id="form_approve_{{ $req->id }}" class="hidden mt-6 pt-6 border-t border-[var(--ig-line)] bg-[var(--ig-bg)]/50 p-4 rounded-xl">
                            <form action="{{ route('admin.withdrawals.approve', $req->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-[var(--ig-ink)] uppercase tracking-wider mb-2">Admin Remarks / Reference ID (Optional)</label>
                                    <input type="text" name="admin_notes" placeholder="e.g. Bank Transfer Ref: Txn-998877" class="ig-input bg-white">
                                </div>
                                <div class="flex gap-3 justify-end">
                                    <button type="button" onclick="toggleActionForm('approve_{{ $req->id }}')" class="px-4 py-2 text-xs font-semibold text-[var(--ig-muted)] hover:bg-[var(--ig-line)] rounded-lg transition-colors">Cancel</button>
                                    <button type="submit" class="ig-btn ig-btn-primary">
                                        <span>Confirm Payout</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Reject Form -->
                        <div id="form_reject_{{ $req->id }}" class="hidden mt-6 pt-6 border-t border-[var(--ig-line)] bg-[var(--ig-bg)]/50 p-4 rounded-xl">
                            <form action="{{ route('admin.withdrawals.reject', $req->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-[var(--ig-ink)] uppercase tracking-wider mb-2">Rejection Reason (Required)</label>
                                    <textarea name="admin_notes" rows="2" placeholder="Explain why the withdrawal is being rejected..." class="ig-input bg-white" required></textarea>
                                </div>
                                <div class="flex gap-3 justify-end">
                                    <button type="button" onclick="toggleActionForm('reject_{{ $req->id }}')" class="px-4 py-2 text-xs font-semibold text-[var(--ig-muted)] hover:bg-[var(--ig-line)] rounded-lg transition-colors">Cancel</button>
                                    <button type="submit" class="ig-btn ig-btn-rose">
                                        <span>Reject Payout</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="ig-card p-12 text-center text-[var(--ig-muted)]">
                    <p class="ig-display text-xl mb-1">No requests found in this queue.</p>
                    <p class="text-xs">Any withdrawal requests matching this filter will show up here.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleActionForm(formId) {
            // Close other form first if open
            const approveForm = document.getElementById('form_approve_' + formId.split('_')[1]);
            const rejectForm = document.getElementById('form_reject_' + formId.split('_')[1]);
            const targetForm = document.getElementById('form_' + formId);

            if (formId.startsWith('approve')) {
                rejectForm.classList.add('hidden');
            } else {
                approveForm.classList.add('hidden');
            }

            targetForm.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
