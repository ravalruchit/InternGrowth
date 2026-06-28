<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-[var(--ig-line)] pb-8 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Platform Operations</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    💳 Subscriptions Ledger
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1.5">View user billing logs, active subscriptions list, and manually trigger tier upgrades.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost text-xs">← Dashboard</a>
                <a href="{{ route('admin.revenue') }}" class="ig-btn text-xs bg-teal-600 text-white font-bold hover:bg-teal-750">📊 Revenue Analytics</a>
            </div>
        </div>

        @if(session('success'))
            <div class="ig-banner ig-banner-success mb-8 text-sm"><p class="font-bold text-emerald-950">✓ {{ session('success') }}</p></div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Manual Upgrade Box -->
            <div class="lg:col-span-4">
                <div class="ig-card p-6" style="border: 1px solid var(--ig-line);">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-4">— Manual Grant Plan</h3>
                    
                    <form method="POST" action="{{ route('admin.subscriptions.manual-upgrade') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Select User Account</label>
                            <select name="user_id" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none text-xs font-semibold">
                                <option value="">-- Choose User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }} - {{ ucfirst($user->role) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Plan Type</label>
                            <select name="plan_type" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none text-xs font-semibold">
                                <option value="student_pro">Student Pro</option>
                                <option value="startup_growth">Startup Growth</option>
                                <option value="enterprise">Enterprise Plan</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Billing Cycle</label>
                                <select name="billing_cycle" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none text-xs font-semibold">
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Amount (₹)</label>
                                <input type="number" name="amount" required min="0" value="99" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none text-xs font-semibold">
                            </div>
                        </div>

                        <button type="submit" style="width:100%;background:var(--ig-accent);color:white;font-weight:700;font-size:12px;padding:12px;border-radius:12px;border:none;cursor:pointer;margin-top:6px;">
                            ⚡ Grant Premium Access
                        </button>
                    </form>
                </div>
            </div>

            <!-- Subscriptions List Table -->
            <div class="lg:col-span-8">
                <div class="ig-card p-6" style="border: 1px solid var(--ig-line); overflow-x: auto;">
                    <h3 class="font-bold text-sm text-[var(--ig-ink)] mb-4">— Subscriptions History</h3>

                    <table class="w-full text-xs text-left" style="font-size: 11.5px; border-collapse: collapse;">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase text-[9px] tracking-wider font-extrabold">
                                <th class="pb-3">User</th>
                                <th class="pb-3">Plan</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Amount</th>
                                <th class="pb-3">Expires At</th>
                                <th class="pb-3">Ref ID</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($subscriptions as $sub)
                                <tr>
                                    <td class="py-3 font-semibold text-slate-800">
                                        {{ $sub->user->name ?? 'Deleted User' }}
                                        <span class="block text-[9px] text-gray-400 font-normal">{{ $sub->user->email ?? '' }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="font-bold text-slate-700">{{ str_replace('_', ' ', $sub->plan_type) }}</span>
                                        <span class="block text-[9px] text-gray-400 font-medium">{{ ucfirst($sub->billing_cycle) }}</span>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $stMap = [
                                                'active' => ['#f0fdf4', '#166534', 'Active'],
                                                'trial' => ['#eff6ff', '#1e40af', 'Trial'],
                                                'cancelled' => ['#fef3c7', '#92400e', 'Cancelled'],
                                                'expired' => ['#fef2f2', '#991b1b', 'Expired']
                                            ];
                                            $st = $stMap[$sub->status] ?? ['#f1f5f9', '#475569', $sub->status];
                                        @endphp
                                        <span style="background:{{ $st[0] }};color:{{ $st[1] }};" class="px-2 py-0.5 rounded-full font-bold text-[9px]">
                                            {{ $st[2] }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-bold text-slate-800">₹{{ number_format($sub->amount, 0) }}</td>
                                    <td class="py-3 text-slate-500">{{ $sub->expires_at ? $sub->expires_at->format('d M Y') : 'Never' }}</td>
                                    <td class="py-3 text-slate-400 font-mono" style="font-size: 10px;">{{ $sub->payment_reference }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 italic">No subscriptions have been created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
