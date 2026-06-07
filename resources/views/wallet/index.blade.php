<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Wallet</h1>
            @if(auth()->user()->isStartup())
                <a href="{{ route('wallet.topup') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition">
                    + Request Top-up
                </a>
            @endif
        </div>
        
        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-8 rounded-lg shadow-lg mb-6">
            <p class="text-sm opacity-90 mb-2">Available Balance</p>
            <p class="text-4xl font-bold">₹{{ number_format($profile->wallet_balance, 2) }}</p>
            @if(auth()->user()->isStartup())
                <p class="text-sm opacity-90 mt-4">
                    Need more funds?
                    <a href="{{ route('wallet.topup') }}" class="underline font-medium">Request a top-up →</a>
                </p>
            @else
                <p class="text-sm opacity-90 mt-4">Withdraw your earnings anytime</p>
            @endif
        </div>
        
        <!-- Transaction History -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Transaction History</h2>
            </div>
            
            <div class="divide-y">
                @forelse($transactions as $transaction)
                    <div class="p-6 flex justify-between items-center hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $transaction->description }}</p>
                            <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                            @if($transaction->reference_id)
                                <p class="text-xs text-gray-400">Ref: {{ $transaction->reference_id }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ in_array($transaction->type, ['credit', 'escrow_release']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($transaction->type, ['credit', 'escrow_release']) ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $transaction->type) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No transactions yet
                    </div>
                @endforelse
            </div>
            
            @if($transactions->hasPages())
                <div class="p-6 border-t">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
