<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Manage Wallets</h1>
            <a href="{{ route('admin.topup.index') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition flex items-center gap-2">
                <span>Top-up Requests</span>
                @php
                    $pendingCount = \App\Models\WalletTopupRequest::where('status','pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                @endif
            </a>
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

        <!-- Startups Wallets -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Startup Wallets</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($startups as $startup)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $startup->company_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $startup->user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-lg font-bold text-green-600">₹{{ number_format($startup->wallet_balance, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="openAddModal('startup', {{ $startup->id }}, '{{ $startup->company_name }}')" 
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm mr-2 hover:bg-green-600">
                                        Add Money
                                    </button>
                                    <button onclick="openDeductModal('startup', {{ $startup->id }}, '{{ $startup->company_name }}', {{ $startup->wallet_balance }})" 
                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Deduct
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Students Wallets -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Student Wallets</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-lg font-bold text-green-600">₹{{ number_format($student->wallet_balance, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="openAddModal('student', {{ $student->id }}, '{{ $student->user->name }}')" 
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm mr-2 hover:bg-green-600">
                                        Add Money
                                    </button>
                                    <button onclick="openDeductModal('student', {{ $student->id }}, '{{ $student->user->name }}', {{ $student->wallet_balance }})" 
                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Deduct
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Money Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Add Money</h3>
            <form method="POST" action="{{ route('admin.wallet.add') }}">
                @csrf
                <input type="hidden" name="user_type" id="add_user_type">
                <input type="hidden" name="user_id" id="add_user_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">User: <span id="add_user_name" class="font-bold"></span></label>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" min="1" required 
                        class="w-full border rounded px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Description (Optional)</label>
                    <input type="text" name="description" 
                        class="w-full border rounded px-3 py-2" placeholder="e.g., Test payment">
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAddModal()" 
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" 
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Money</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deduct Money Modal -->
    <div id="deductModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Deduct Money</h3>
            <form method="POST" action="{{ route('admin.wallet.deduct') }}">
                @csrf
                <input type="hidden" name="user_type" id="deduct_user_type">
                <input type="hidden" name="user_id" id="deduct_user_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">User: <span id="deduct_user_name" class="font-bold"></span></label>
                    <label class="block text-sm text-gray-600">Current Balance: ₹<span id="deduct_current_balance"></span></label>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" min="1" required 
                        class="w-full border rounded px-3 py-2">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Description (Optional)</label>
                    <input type="text" name="description" 
                        class="w-full border rounded px-3 py-2" placeholder="e.g., Refund">
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDeductModal()" 
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" 
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Deduct Money</button>
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
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openDeductModal(userType, userId, userName, balance) {
            document.getElementById('deduct_user_type').value = userType;
            document.getElementById('deduct_user_id').value = userId;
            document.getElementById('deduct_user_name').textContent = userName;
            document.getElementById('deduct_current_balance').textContent = balance.toFixed(2);
            document.getElementById('deductModal').classList.remove('hidden');
        }

        function closeDeductModal() {
            document.getElementById('deductModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
