<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Request Wallet Top-up</h1>
            <a href="{{ route('wallet.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Wallet</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Current Balance -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white p-6 rounded-xl shadow mb-6">
            <p class="text-sm opacity-90">Current Wallet Balance</p>
            <p class="text-4xl font-bold mt-1">₹{{ number_format($startup->wallet_balance, 2) }}</p>
        </div>

        <!-- How it works -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
            <h2 class="font-semibold text-blue-800 mb-2">How to add money</h2>
            <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
                <li>Transfer the amount to our bank account / UPI below.</li>
                <li>Fill in the form with the amount and your payment reference (UTR/transaction ID).</li>
                <li>Admin will verify and credit your wallet within 24 hours.</li>
            </ol>
            <div class="mt-4 bg-white rounded-lg p-4 text-sm text-gray-700 border border-blue-100">
                <p class="font-semibold text-gray-800 mb-1">Payment Details</p>
                <p><span class="font-medium">Bank:</span> HDFC Bank</p>
                <p><span class="font-medium">Account Name:</span> InternGrowth Pvt Ltd</p>
                <p><span class="font-medium">Account No:</span> 1234567890</p>
                <p><span class="font-medium">IFSC:</span> HDFC0001234</p>
                <p><span class="font-medium">UPI:</span> interngrowth@hdfcbank</p>
            </div>
        </div>

        <!-- Request Form -->
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Submit Top-up Request</h2>
            <form method="POST" action="{{ route('wallet.topup.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₹) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" min="100" step="1" required
                            value="{{ old('amount') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Minimum ₹100">
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                        <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer (NEFT/IMPS)</option>
                            <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Reference / UTR</label>
                    <input type="text" name="transaction_reference"
                        value="{{ old('transaction_reference') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                        placeholder="e.g. UTR123456789 or UPI transaction ID">
                    @error('transaction_reference') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea name="notes" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Any additional info for admin">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition">
                    Submit Top-up Request
                </button>
            </form>
        </div>

        <!-- Past Requests -->
        <div class="bg-white rounded-xl shadow">
            <div class="p-5 border-b">
                <h2 class="text-lg font-semibold">My Top-up Requests</h2>
            </div>
            <div class="divide-y">
                @forelse($requests as $req)
                    <div class="p-5 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">₹{{ number_format($req->amount, 2) }}
                                <span class="text-sm text-gray-500 font-normal ml-1">via {{ str_replace('_', ' ', $req->payment_method) }}</span>
                            </p>
                            @if($req->transaction_reference)
                                <p class="text-xs text-gray-500">Ref: {{ $req->transaction_reference }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $req->created_at->format('M d, Y h:i A') }}</p>
                            @if($req->admin_notes)
                                <p class="text-xs text-gray-600 mt-1 italic">Admin: {{ $req->admin_notes }}</p>
                            @endif
                        </div>
                        <div>
                            @if($req->status === 'pending')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">⏳ Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">✓ Approved</span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">✗ Rejected</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">No top-up requests yet.</div>
                @endforelse
            </div>
            @if($requests->hasPages())
                <div class="p-4 border-t">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
