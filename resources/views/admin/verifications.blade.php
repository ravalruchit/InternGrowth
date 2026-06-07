<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Dashboard Header -->
        <div class="mb-10 text-center md:text-left flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 font-poppins">
                    Startup Verification Queue 📋
                </h1>
                <p class="text-gray-600 text-lg mt-1">Review official credentials and approve or reject startup registration requests.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center space-x-2 bg-white/80 hover:bg-white border border-purple-100 text-gray-750 px-5 py-2.5 rounded-xl font-bold shadow-md transition duration-300">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-lg flex items-center justify-between animate-slide-down">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-500 text-white rounded-full p-1.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="font-semibold text-base">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Grid Layout for Pending and Recent reviews -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Pending Requests Queue (Col span 2) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white/80 backdrop-blur-lg border border-purple-100 rounded-3xl shadow-xl overflow-hidden">
                    <div class="p-6 border-b border-purple-55 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900 font-poppins flex items-center space-x-2">
                            <span>Pending Reviews</span>
                            <span class="bg-indigo-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $pendingVerifications->count() }}</span>
                        </h2>
                    </div>

                    <div class="divide-y divide-purple-50">
                        @forelse($pendingVerifications as $startup)
                            <div class="p-6 md:p-8 hover:bg-indigo-50/10 transition duration-300">
                                <!-- Startup Profile Details Header -->
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900 font-poppins">{{ $startup->company_name }}</h3>
                                        <p class="text-gray-500 font-medium text-sm flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $startup->user->email }}</span>
                                        </p>
                                        @if($startup->verification_submitted_at)
                                            <p class="text-xs text-gray-400 mt-2 font-semibold">
                                                Submitted: {{ $startup->verification_submitted_at->format('M d, Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-yellow-150 text-yellow-800 border border-yellow-250 animate-pulse-slow">
                                            ● Awaiting Verification
                                        </span>
                                    </div>
                                </div>

                                <!-- Grid Parameters -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-5 bg-purple-50/30 rounded-2xl border border-purple-100/50">
                                    <div>
                                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Registration Number</p>
                                        <p class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $startup->company_registration_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">GSTIN</p>
                                        <p class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $startup->gst_number ?? 'Not Provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Phone Number</p>
                                        <p class="text-sm font-bold text-gray-850 mt-1">{{ $startup->contact_phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Website URL</p>
                                        <p class="text-sm font-bold mt-1">
                                            @if($startup->website)
                                                <a href="{{ $startup->website }}" target="_blank" class="text-indigo-650 hover:underline inline-flex items-center space-x-0.5">
                                                    <span>{{ $startup->website }}</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-gray-400 font-normal">Not Provided</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-span-1 md:col-span-2 border-t border-purple-100/50 pt-3 mt-1">
                                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Registered Address</p>
                                        <p class="text-sm text-gray-800 mt-1 leading-relaxed">{{ $startup->company_address }}</p>
                                    </div>
                                </div>

                                <!-- Documents Section -->
                                @if($startup->verification_documents && count($startup->verification_documents) > 0)
                                    <div class="mb-6">
                                        <p class="text-sm font-bold text-gray-800 mb-3">Submitted Evidence Documents:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach($startup->verification_documents as $doc)
                                                <div class="flex items-center justify-between bg-white border border-purple-100 p-4 rounded-xl shadow-sm hover:shadow transition duration-200">
                                                    <div class="flex items-center space-x-3 min-w-0">
                                                        <div class="bg-indigo-50 text-indigo-600 p-2.5 rounded-lg">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="truncate">
                                                            <p class="text-xs font-bold text-gray-850 truncate" title="{{ $doc['name'] }}">{{ $doc['name'] }}</p>
                                                            <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ number_format($doc['size'] / 1024, 1) }} KB</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2 flex-shrink-0">
                                                        <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-650 px-2.5 py-1.5 rounded-lg text-xs font-bold transition">
                                                            View
                                                        </a>
                                                        <a href="{{ asset('storage/' . $doc['path']) }}" download="{{ $doc['name'] }}" class="bg-gray-150 hover:bg-gray-200 text-gray-700 px-2.5 py-1.5 rounded-lg text-xs font-bold transition">
                                                            Get
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Decision Controls -->
                                <div class="flex gap-4">
                                    <button onclick="toggleDecisionForm('approve', {{ $startup->id }})" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:shadow-md hover:shadow-green-100 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition duration-200">
                                        ✓ Approve Application
                                    </button>
                                    <button onclick="toggleDecisionForm('reject', {{ $startup->id }})" class="bg-gradient-to-r from-red-500 to-rose-600 hover:shadow-md hover:shadow-red-100 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition duration-200">
                                        ✗ Reject with Notes
                                    </button>
                                </div>

                                <!-- Inline Approval Form -->
                                <div id="approve-panel-{{ $startup->id }}" class="hidden mt-4 p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200 animate-slide-down">
                                    <form method="POST" action="{{ route('admin.verifications.approve', $startup->id) }}">
                                        @csrf
                                        <p class="text-sm font-bold text-green-850 mb-3">
                                            Confirm Startup Approval:
                                        </p>
                                        <p class="text-xs text-green-700 leading-relaxed mb-4">
                                            This grants verification credentials to <strong>{{ $startup->company_name }}</strong>. They will be immediately unlocked to post internship tasks, search student candidate ledgers, and extend job offers.
                                        </p>
                                        <div class="flex gap-3">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow transition">
                                                Confirm Approval
                                            </button>
                                            <button type="button" onclick="toggleDecisionForm('approve', {{ $startup->id }}, true)" class="bg-white hover:bg-gray-50 border border-green-200 text-green-800 px-4 py-2 rounded-xl text-xs font-bold transition">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Inline Rejection Form -->
                                <div id="reject-panel-{{ $startup->id }}" class="hidden mt-4 p-5 bg-gradient-to-r from-red-50 to-rose-50 rounded-2xl border border-red-200 animate-slide-down">
                                    <form method="POST" action="{{ route('admin.verifications.reject', $startup->id) }}">
                                        @csrf
                                        <label class="block text-sm font-bold text-red-850 mb-2">Rejection Feedback Notes (Required)</label>
                                        <p class="text-xs text-red-700 mb-3">Provide a detailed explanation. This note will guide the founder on what edits or document qualities are required to resubmit.</p>
                                        
                                        <textarea name="notes" rows="3" required class="w-full border-red-200 focus:ring-red-500 focus:border-red-500 rounded-xl mb-4 text-sm px-4 py-3 placeholder-red-350" placeholder="e.g. The company registration document uploaded is blurry. Please upload a clear scan."></textarea>
                                        
                                        <div class="flex gap-3">
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow transition">
                                                Confirm Rejection
                                            </button>
                                            <button type="button" onclick="toggleDecisionForm('reject', {{ $startup->id }}, true)" class="bg-white hover:bg-gray-50 border border-red-200 text-red-800 px-4 py-2 rounded-xl text-xs font-bold transition">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-16 text-center">
                                <div class="w-16 h-16 bg-purple-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 font-poppins">Verification Queue Clear</h3>
                                <p class="text-gray-500 text-sm mt-1">There are no pending startup verification requests to review at this time.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recently Reviewed Panel (Col span 1) -->
            <div class="space-y-6">
                <div class="bg-white/80 backdrop-blur-lg border border-purple-100 rounded-3xl shadow-xl overflow-hidden">
                    <div class="p-6 border-b border-purple-55 bg-gradient-to-r from-pink-50/50 to-purple-50/50">
                        <h2 class="text-lg font-bold text-gray-900 font-poppins flex items-center space-x-2">
                            <span>Recent Decisions</span>
                        </h2>
                    </div>

                    <div class="divide-y divide-purple-50">
                        @forelse($recentlyReviewed as $startup)
                            <div class="p-5 hover:bg-gray-50/50 transition duration-200">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-bold text-gray-850 text-sm truncate font-poppins" title="{{ $startup->company_name }}">{{ $startup->company_name }}</h4>
                                    
                                    @if($startup->verification_status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 truncate">{{ $startup->user->email }}</p>
                                
                                @if($startup->verification_reviewed_at)
                                    <p class="text-[10px] text-gray-400 mt-2 font-medium">Reviewed: {{ $startup->verification_reviewed_at->format('M d, H:i') }}</p>
                                @endif

                                @if($startup->verification_notes)
                                    <div class="mt-3 p-3 bg-red-50/40 rounded-xl border border-red-100">
                                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider">Feedback notes:</p>
                                        <p class="text-xs text-red-850 mt-1 italic leading-relaxed">"{{ Str::limit($startup->verification_notes, 80) }}"</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500 text-sm">
                                No recently reviewed verifications
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle logic for panel triggers -->
    <script>
        function toggleDecisionForm(action, startupId, forceClose = false) {
            const approvePanel = document.getElementById(`approve-panel-${startupId}`);
            const rejectPanel = document.getElementById(`reject-panel-${startupId}`);
            
            if (forceClose) {
                if (approvePanel) approvePanel.classList.add('hidden');
                if (rejectPanel) rejectPanel.classList.add('hidden');
                return;
            }

            if (action === 'approve') {
                approvePanel.classList.toggle('hidden');
                rejectPanel.classList.add('hidden');
            } else {
                rejectPanel.classList.toggle('hidden');
                approvePanel.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
