<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Financial Ledger</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Top-up <span class="ig-serif text-[var(--ig-accent)]">Requests.</span><br>
                    Verify bank & UPI deposits.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.wallets') }}" class="ig-btn ig-btn-ghost">
                    <span>← Manage Wallets</span>
                </a>
            </div>
        </div>



        <!-- Pending Requests -->
        <div class="ig-card p-0 overflow-hidden mb-10 ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] flex items-center justify-between bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">Pending Requests</h2>
                <span class="ig-chip ig-chip-accent">
                    {{ $pending->count() }} pending
                </span>
            </div>

            @if($pending->isEmpty())
                <div class="p-12 text-center text-[var(--ig-muted)] font-mono text-sm">No pending requests found.</div>
            @else
                <div class="divide-y divide-[var(--ig-line)]">
                    @foreach($pending as $req)
                        <div class="p-6 md:p-8 hover:bg-[var(--ig-bg-2)]/30 transition duration-300">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                                <div class="space-y-4 flex-1">
                                    <div>
                                        <h3 class="ig-display text-2xl font-bold">{{ $req->startup->company_name }}</h3>
                                        <p class="ig-mono text-xs text-[var(--ig-muted)] mt-1">{{ $req->startup->user->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-[var(--ig-bg-2)]/60 border border-[var(--ig-line)] rounded-2xl">
                                        <div>
                                            <p class="text-[9px] text-[var(--ig-muted)] font-mono uppercase tracking-wider font-bold">Amount</p>
                                            <p class="text-emerald-700 font-bold text-base mt-0.5">₹{{ number_format($req->amount, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] text-[var(--ig-muted)] font-mono uppercase tracking-wider font-bold">Method</p>
                                            <p class="text-[var(--ig-ink)] font-bold text-sm mt-0.5 capitalize">{{ str_replace('_', ' ', $req->payment_method) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] text-[var(--ig-muted)] font-mono uppercase tracking-wider font-bold">Reference</p>
                                            <p class="text-[var(--ig-ink)] font-mono font-bold text-sm mt-0.5">{{ $req->transaction_reference ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] text-[var(--ig-muted)] font-mono uppercase tracking-wider font-bold">Wallet Balance</p>
                                            <p class="text-[var(--ig-ink)] font-semibold text-sm mt-0.5">₹{{ number_format($req->startup->wallet_balance, 2) }}</p>
                                        </div>
                                    </div>
                                    @if($req->notes)
                                        <div class="p-3 bg-white border border-[var(--ig-line-2)] rounded-xl text-xs text-[var(--ig-ink-2)] italic">
                                            "{{ $req->notes }}"
                                        </div>
                                    @endif
                                    <p class="text-[10px] text-[var(--ig-muted)] font-semibold font-mono">Requested: {{ $req->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex sm:flex-col gap-2.5 flex-shrink-0 justify-end">
                                    <!-- Approve -->
                                    <form method="POST" action="{{ route('admin.topup.approve', $req->id) }}">
                                        @csrf
                                        <input type="hidden" name="admin_notes" value="Payment verified and credited.">
                                        <button type="submit"
                                            onclick="return confirm('Approve ₹{{ number_format($req->amount, 2) }} for {{ $req->startup->company_name }}?')"
                                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-emerald-700 w-full justify-center">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <!-- Reject -->
                                    <button onclick="openRejectModal({{ $req->id }}, '{{ addslashes($req->startup->company_name) }}')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-red-700 w-full justify-center">
                                        ✗ Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Reviewed Requests -->
        <div class="ig-card p-0 overflow-hidden ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">Reviewed Requests</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ig-line)]">
                    <thead class="bg-[var(--ig-bg-2)]/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Company</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Reference</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Reviewed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)] bg-white">
                        @forelse($reviewed as $req)
                            <tr class="hover:bg-[var(--ig-bg-2)]/30 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-[var(--ig-ink)]">{{ $req->startup->company_name }}</p>
                                    <p class="text-xs text-[var(--ig-muted)] font-mono">{{ $req->startup->user->email ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-[var(--ig-ink)]">₹{{ number_format($req->amount, 2) }}</td>
                                <td class="px-6 py-4 capitalize text-sm text-[var(--ig-ink-2)]">{{ str_replace('_', ' ', $req->payment_method) }}</td>
                                <td class="px-6 py-4 text-sm text-[var(--ig-muted)] font-mono">{{ $req->transaction_reference ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($req->status === 'approved')
                                        <span class="ig-chip ig-chip-success">Approved</span>
                                    @else
                                        <span class="ig-chip ig-chip-danger">Rejected</span>
                                    @endif
                                    @if($req->admin_notes)
                                        <p class="text-xs text-[var(--ig-muted)] mt-1 italic">"{{ $req->admin_notes }}"</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-[var(--ig-muted)] font-mono">
                                    {{ $req->reviewed_at ? $req->reviewed_at->format('M d, Y') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-[var(--ig-muted)] text-sm font-mono">No reviewed requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reviewed->hasPages())
                <div class="p-4 border-t border-[var(--ig-line)] bg-[var(--ig-bg-2)]/30">
                    {{ $reviewed->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 items-center justify-center bg-[var(--ig-surface-ink)]/60 backdrop-blur-md z-50 p-4">
        <div class="ig-card max-w-md w-full p-8 bg-white relative ig-anim-scale-in">
            <h3 class="ig-display text-2xl mb-4 text-[var(--ig-ink)] font-bold">Reject Request</h3>
            <div class="mb-5 p-3.5 bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl">
                <p class="text-[10px] text-[var(--ig-muted)] uppercase tracking-wider font-mono font-bold">Company Profile</p>
                <p id="reject_company" class="font-semibold text-sm text-[var(--ig-ink)] mt-0.5"></p>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Reason for Rejection *</label>
                    <textarea name="admin_notes" rows="3" required
                        class="ig-input"
                        placeholder="e.g., UPI verification failed, duplicate reference..."></textarea>
                </div>
                <div class="flex justify-between mt-8 pt-4 border-t border-[var(--ig-line)]">
                    <button type="button" onclick="closeRejectModal()"
                        class="ig-btn ig-btn-ghost px-5 py-2">Cancel</button>
                    <button type="submit"
                        class="ig-btn ig-btn-primary px-5 py-2 bg-red-600 hover:bg-red-700 border-red-600">Reject Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, company) {
            document.getElementById('reject_company').textContent = company;
            document.getElementById('rejectForm').action = '/admin/topup/' + id + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
    </script>
</x-app-layout>
