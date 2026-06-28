<x-app-layout>
    <div class="ig-container py-10">
        <!-- Flash Messages & Validation Errors -->
        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-6 text-sm">
                <p class="font-bold text-emerald-950">✓ {{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="ig-banner mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-955 font-bold shadow-sm">
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
                <p class="ig-eyebrow mb-3">— Ledger & Finance</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Wallet <span class="ig-serif text-[var(--ig-accent)]">Control.</span><br>
                    Adjust balances & ledgers.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.topup.index') }}" class="ig-btn ig-btn-ghost font-bold border-stone-300">
                    <span>Top-up Requests</span>
                    @php
                        $pendingCount = \App\Models\WalletTopupRequest::where('status','pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold bg-[var(--ig-accent)] text-white rounded-full font-mono">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>
        </div>



        <!-- Startups Wallets -->
        <div class="ig-card p-0 overflow-hidden mb-10 ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] flex justify-between items-center bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">Startup Wallets</h2>
                <span class="ig-chip ig-chip-ink">{{ count($startups) }} firms</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ig-line)]">
                    <thead class="bg-[var(--ig-bg-2)]/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Company</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Balance</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)] bg-white">
                        @forelse($startups as $startup)
                            <tr class="hover:bg-[var(--ig-bg-2)]/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[var(--ig-ink)]">
                                    {{ $startup->company_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--ig-muted)] font-mono">
                                    {{ $startup->user->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="ig-mono text-base font-bold text-emerald-700">₹{{ number_format($startup->wallet_balance, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <button onclick="openAddModal('startup', {{ $startup->id }}, '{{ addslashes($startup->company_name) }}')" 
                                        class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-emerald-700">
                                        Add Money
                                    </button>
                                    <button onclick="openDeductModal('startup', {{ $startup->id }}, '{{ addslashes($startup->company_name) }}', {{ $startup->wallet_balance }})" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-red-700">
                                        Deduct
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[var(--ig-muted)] text-sm font-mono">
                                    No startups registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Students Wallets -->
        <div class="ig-card p-0 overflow-hidden ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] flex justify-between items-center bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">Student Wallets</h2>
                <span class="ig-chip ig-chip-ink">{{ count($students) }} scholars</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ig-line)]">
                    <thead class="bg-[var(--ig-bg-2)]/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Balance</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)] bg-white">
                        @forelse($students as $student)
                            <tr class="hover:bg-[var(--ig-bg-2)]/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[var(--ig-ink)]">
                                    {{ $student->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--ig-muted)] font-mono">
                                    {{ $student->user->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="ig-mono text-base font-bold text-emerald-700">₹{{ number_format($student->wallet_balance, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <button onclick="openAddModal('student', {{ $student->id }}, '{{ addslashes($student->user->name ?? 'N/A') }}')" 
                                        class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-emerald-700">
                                        Add Money
                                    </button>
                                    <button onclick="openDeductModal('student', {{ $student->id }}, '{{ addslashes($student->user->name ?? 'N/A') }}', {{ $student->wallet_balance }})" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-red-700">
                                        Deduct
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[var(--ig-muted)] text-sm font-mono">
                                    No students registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Money Modal -->
    <div id="addModal" class="hidden fixed inset-0 items-center justify-center bg-[var(--ig-surface-ink)]/60 backdrop-blur-md z-50 p-4">
        <div class="ig-card max-w-md w-full p-8 bg-white relative ig-anim-scale-in">
            <h3 class="ig-display text-2xl mb-4 text-[var(--ig-ink)]">Add Funds</h3>
            <form method="POST" action="{{ route('admin.wallet.add') }}">
                @csrf
                <input type="hidden" name="user_type" id="add_user_type">
                <input type="hidden" name="user_id" id="add_user_id">
                
                <div class="mb-5 p-3.5 bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl">
                    <p class="text-[10px] text-[var(--ig-muted)] uppercase tracking-wider font-mono font-bold">Recipient Account</p>
                    <p id="add_user_name" class="font-semibold text-sm text-[var(--ig-ink)] mt-0.5"></p>
                </div>
                
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Amount (₹) *</label>
                    <input type="number" name="amount" step="0.01" min="1" required 
                        class="ig-input" placeholder="e.g. 5000">
                </div>
                
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Description / Reference</label>
                    <input type="text" name="description" 
                        class="ig-input" placeholder="e.g. Manual stipend adjustment">
                </div>
                
                <div class="flex justify-between mt-8 pt-4 border-t border-[var(--ig-line)]">
                    <button type="button" onclick="closeAddModal()" 
                        class="ig-btn ig-btn-ghost px-5 py-2">Cancel</button>
                    <button type="submit" 
                        class="ig-btn ig-btn-primary px-5 py-2">Add Money</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deduct Money Modal -->
    <div id="deductModal" class="hidden fixed inset-0 items-center justify-center bg-[var(--ig-surface-ink)]/60 backdrop-blur-md z-50 p-4">
        <div class="ig-card max-w-md w-full p-8 bg-white relative ig-anim-scale-in">
            <h3 class="ig-display text-2xl mb-4 text-[var(--ig-ink)]">Deduct Funds</h3>
            <form method="POST" action="{{ route('admin.wallet.deduct') }}">
                @csrf
                <input type="hidden" name="user_type" id="deduct_user_type">
                <input type="hidden" name="user_id" id="deduct_user_id">
                
                <div class="mb-5 p-3.5 bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl space-y-1">
                    <div>
                        <p class="text-[10px] text-[var(--ig-muted)] uppercase tracking-wider font-mono font-bold">Account Name</p>
                        <p id="deduct_user_name" class="font-semibold text-sm text-[var(--ig-ink)]"></p>
                    </div>
                    <div class="pt-1.5 border-t border-[var(--ig-line)]">
                        <p class="text-[10px] text-[var(--ig-muted)] uppercase tracking-wider font-mono font-bold">Current Balance</p>
                        <p class="text-sm font-bold text-red-600">₹<span id="deduct_current_balance" class="ig-mono"></span></p>
                    </div>
                </div>
                
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Amount (₹) *</label>
                    <input type="number" name="amount" step="0.01" min="1" required 
                        class="ig-input" placeholder="e.g. 1000">
                </div>
                
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Description / Reference</label>
                    <input type="text" name="description" 
                        class="ig-input" placeholder="e.g. Penalty or correction">
                </div>
                
                <div class="flex justify-between mt-8 pt-4 border-t border-[var(--ig-line)]">
                    <button type="button" onclick="closeDeductModal()" 
                        class="ig-btn ig-btn-ghost px-5 py-2">Cancel</button>
                    <button type="submit" 
                        class="ig-btn ig-btn-primary px-5 py-2 bg-red-600 hover:bg-red-700 border-red-600">Deduct Money</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal(userType, userId, userName) {
            document.getElementById('add_user_type').value = userType;
            document.getElementById('add_user_id').value = userId;
            document.getElementById('add_user_name').textContent = userName;
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openDeductModal(userType, userId, userName, balance) {
            document.getElementById('deduct_user_type').value = userType;
            document.getElementById('deduct_user_id').value = userId;
            document.getElementById('deduct_user_name').textContent = userName;
            document.getElementById('deduct_current_balance').textContent = balance.toFixed(2);
            document.getElementById('deductModal').classList.remove('hidden');
            document.getElementById('deductModal').classList.add('flex');
        }

        function closeDeductModal() {
            document.getElementById('deductModal').classList.add('hidden');
            document.getElementById('deductModal').classList.remove('flex');
        }
    </script>
</x-app-layout>
