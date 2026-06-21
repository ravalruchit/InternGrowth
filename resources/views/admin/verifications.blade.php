<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Validation Queue</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Startup <span class="ig-serif text-[var(--ig-accent)]">Verifications.</span><br>
                    Business credentials review.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>


        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
            <!-- Pending Reviews Queue (Col span 8) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="ig-card p-0 overflow-hidden">
                    <div class="p-6 border-b border-[var(--ig-line)] flex justify-between items-center bg-[var(--ig-bg-2)]">
                        <h2 class="ig-display text-xl flex items-center space-x-2">
                            <span>Pending Reviews Queue</span>
                            <span class="ml-2 ig-chip ig-chip-accent">{{ $pendingVerifications->count() }}</span>
                        </h2>
                    </div>

                    <div class="divide-y divide-[var(--ig-line)]">
                        @forelse($pendingVerifications as $startup)
                            <div class="p-6 md:p-8 hover:bg-[var(--ig-bg-2)]/40 transition duration-300">
                                <!-- Startup Header -->
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                                    <div>
                                        <h3 class="ig-display text-2xl font-bold">{{ $startup->company_name }}</h3>
                                        <p class="ig-mono text-xs text-[var(--ig-muted)] mt-1 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $startup->user->email }}</span>
                                        </p>
                                        @if($startup->verification_submitted_at)
                                            <p class="text-[10px] text-[var(--ig-muted)] font-semibold mt-1">
                                                Submitted: {{ $startup->verification_submitted_at->format('M d, Y @ H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        @if($startup->is_suspicious)
                                            <span class="ig-chip ig-chip-accent">🚨 Flagged Suspicious</span>
                                        @else
                                            <span class="ig-chip" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border-color: rgba(245, 158, 11, 0.2)">● Awaiting Review</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- AI Insights Card -->
                                @if($startup->ai_confidence_score !== null || $startup->ai_verification_result)
                                    @php
                                        $aiResult = $startup->ai_verification_result ?? [];
                                        $level = $startup->verification_level ?? 'C';
                                        $score = $startup->ai_confidence_score ?? 0;
                                        $fraudScore = $aiResult['fraud_score'] ?? 0;
                                        $fraudFlags = $aiResult['fraud_flags'] ?? [];
                                        $reason = $aiResult['reason'] ?? '';
                                        
                                        $priorityNum = 5;
                                        if ($level === 'A') $priorityNum = 1;
                                        elseif ($level === 'B') $priorityNum = 2;
                                        elseif ($level === 'C') $priorityNum = 3;
                                        elseif ($level === 'D') $priorityNum = 4;
                                        if (in_array('duplicate_company_details', $fraudFlags) || (isset($aiResult['security_flags']) && in_array('duplicate_company_details', $aiResult['security_flags']))) {
                                            $priorityNum = 4;
                                        }
                                        
                                        $fraudScoreColor = $fraudScore >= 65 ? 'text-red-400' : ($fraudScore >= 25 ? 'text-amber-400' : 'text-[var(--ig-lime)]');
                                    @endphp

                                    <div class="mb-6 bg-[#0f1217] text-white rounded-2xl p-5 border border-white/10 relative overflow-hidden group">
                                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-[var(--ig-accent)]/5 rounded-full blur-2xl group-hover:bg-[var(--ig-accent)]/10 transition-all duration-500"></div>
                                        
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4 relative z-10">
                                            <div>
                                                <div class="flex flex-wrap gap-2 items-center">
                                                    <span class="text-[9px] bg-white/10 text-[var(--ig-accent-soft)] font-extrabold px-2 py-0.5 rounded border border-white/10 uppercase tracking-wider">Priority {{ $priorityNum }}</span>
                                                    
                                                    @if($level === 'A')
                                                        <span class="text-[9px] bg-emerald-500/20 text-emerald-300 font-bold px-2 py-0.5 rounded border border-emerald-500/20">🟢 LEVEL A (Pre-Approved)</span>
                                                    @elseif($level === 'B')
                                                        <span class="text-[9px] bg-blue-500/20 text-blue-300 font-bold px-2 py-0.5 rounded border border-blue-500/20">🟡 LEVEL B (High Conf)</span>
                                                    @elseif($level === 'C')
                                                        <span class="text-[9px] bg-amber-500/20 text-amber-300 font-bold px-2 py-0.5 rounded border border-amber-500/20">🟠 LEVEL C (Med Conf)</span>
                                                    @else
                                                        <span class="text-[9px] bg-rose-500/20 text-rose-300 font-bold px-2 py-0.5 rounded border border-rose-500/20">🔴 LEVEL D (Low Match)</span>
                                                    @endif

                                                    @if(in_array('duplicate_company_details', $fraudFlags) || (isset($aiResult['security_flags']) && in_array('duplicate_company_details', $aiResult['security_flags'])))
                                                        <span class="text-[9px] bg-red-600 text-white font-extrabold px-2 py-0.5 rounded border border-red-500 animate-pulse">⚠️ DUPLICATE GST/CIN</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-300 mt-2 font-mono leading-relaxed">
                                                    <strong>AI Verdict:</strong> {{ $reason }}
                                                </p>
                                            </div>

                                            <div class="flex gap-4 flex-shrink-0 relative z-10 text-center">
                                                <div>
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Match Score</span>
                                                    <span class="text-[var(--ig-lime)] font-bold text-lg">{{ $score }}/100</span>
                                                </div>
                                                <div>
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Fraud Index</span>
                                                    <span class="font-bold text-lg {{ $fraudScoreColor }}">{{ $fraudScore }}/100</span>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!empty($fraudFlags))
                                            <div class="border-t border-white/10 pt-3 flex items-center space-x-2 relative z-10">
                                                <span class="text-[9px] text-rose-300 font-bold uppercase tracking-wider">Fraud Flags:</span>
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($fraudFlags as $flag)
                                                        <span class="text-[8px] bg-rose-500/20 text-rose-300 border border-rose-500/20 px-1.5 py-0.5 rounded uppercase tracking-wider font-bold">
                                                            {{ str_replace('_', ' ', $flag) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Info Parameter Fields -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-5 bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-2xl">
                                    <div>
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Registration Number</p>
                                        <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1 font-mono">{{ $startup->company_registration_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">GSTIN</p>
                                        <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1 font-mono">{{ $startup->gst_number ?? 'Not Provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Contact Phone</p>
                                        <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1">{{ $startup->contact_phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Website URL</p>
                                        <p class="text-sm font-semibold mt-1">
                                            @if($startup->website)
                                                <a href="{{ $startup->website }}" target="_blank" class="text-[var(--ig-accent)] hover:underline inline-flex items-center space-x-0.5">
                                                    <span>{{ $startup->website }}</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            @else
                                                <span class="text-[var(--ig-muted)] font-normal">Not Provided</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-span-1 md:col-span-2 border-t border-[var(--ig-line)] pt-3 mt-1">
                                        <p class="text-[9px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Registered Address</p>
                                        <p class="text-xs text-[var(--ig-ink-2)] mt-1 leading-relaxed">{{ $startup->company_address }}</p>
                                    </div>
                                </div>

                                <!-- Documents Section -->
                                @if($startup->verification_documents && count($startup->verification_documents) > 0)
                                    <div class="mb-6">
                                        <p class="text-xs font-bold text-[var(--ig-ink)] mb-3 uppercase tracking-wider">Evidence Files:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            @foreach($startup->verification_documents as $key => $doc)
                                                <div class="flex items-center justify-between bg-[var(--ig-bg-2)] border border-[var(--ig-line)] p-4 rounded-xl shadow-sm hover:border-[var(--ig-ink)] transition-colors duration-200">
                                                    <div class="flex items-center space-x-3 min-w-0">
                                                        <div class="bg-[var(--ig-accent-soft)]/20 text-[var(--ig-accent)] p-2.5 rounded-lg flex-shrink-0">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="truncate">
                                                            <span class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider block">{{ $key === 'supporting_document' ? 'DIRECTORS ID PROOF' : str_replace('_', ' ', $key) }}</span>
                                                            <p class="text-xs font-bold text-[var(--ig-ink)] truncate mt-0.5" title="{{ $doc['name'] }}">{{ $doc['name'] }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="bg-[var(--ig-bg)] border border-[var(--ig-line-2)] hover:border-[var(--ig-ink)] text-xs font-bold px-2.5 py-1 rounded transition flex-shrink-0 ml-2">
                                                        View
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Decision Controls -->
                                <div class="flex flex-wrap gap-3 items-center">
                                    <button onclick="toggleDecisionForm('approve', {{ $startup->id }})" class="ig-btn ig-btn-lime">
                                        ✓ Approve Application
                                    </button>
                                    <button onclick="toggleDecisionForm('reject', {{ $startup->id }})" class="ig-btn ig-btn-ghost text-red-600 border-red-200/50 hover:bg-red-50 hover:border-red-500">
                                        ✗ Reject with Notes
                                    </button>

                                    <!-- Toggle Suspicious Button -->
                                    <form method="POST" action="{{ route('admin.verifications.toggle-suspicious', $startup->id) }}" class="inline-block">
                                        @csrf
                                        @if($startup->is_suspicious)
                                            <button type="submit" class="ig-btn ig-btn-ghost text-amber-700 border-amber-300 hover:bg-amber-50">
                                                Clear Flag
                                            </button>
                                        @else
                                            <button type="submit" class="ig-btn ig-btn-ghost text-[var(--ig-muted)] border-[var(--ig-line-2)] hover:text-red-600 hover:border-red-500">
                                                Flag Suspicious
                                            </button>
                                        @endif
                                    </form>
                                </div>

                                <!-- Inline Approval Form -->
                                <div id="approve-panel-{{ $startup->id }}" class="hidden mt-4 p-5 bg-[var(--ig-lime-soft)]/20 border border-[var(--ig-lime)]/30 rounded-2xl transition duration-300">
                                    <form method="POST" action="{{ route('admin.verifications.approve', $startup->id) }}">
                                        @csrf
                                        <p class="text-sm font-bold text-green-900 mb-2">Confirm Startup Approval:</p>
                                        <p class="text-xs text-green-800 leading-relaxed mb-4">
                                            This grants verification credentials to <strong>{{ $startup->company_name }}</strong>. They will be immediately unlocked to post tasks, view student CVs, and issue offers. Initial Trust Score will start at <strong>50/100</strong>.
                                        </p>
                                        <div class="flex gap-3">
                                            <button type="submit" class="ig-btn ig-btn-lime">
                                                Confirm Approval
                                            </button>
                                            <button type="button" onclick="toggleDecisionForm('approve', {{ $startup->id }}, true)" class="ig-btn ig-btn-ghost">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Inline Rejection Form -->
                                <div id="reject-panel-{{ $startup->id }}" class="hidden mt-4 p-5 bg-[var(--ig-accent-soft)]/20 border border-[var(--ig-accent-soft)] rounded-2xl transition duration-300">
                                    <form method="POST" action="{{ route('admin.verifications.reject', $startup->id) }}">
                                        @csrf
                                        <label class="block text-sm font-bold text-red-900 mb-1">Rejection Feedback Notes (Required)</label>
                                        <p class="text-xs text-red-800 mb-3">Provide a clear description so the founder knows what files or credentials to fix and re-submit.</p>
                                        
                                        <textarea name="notes" rows="3" required class="ig-input w-full p-3 mb-4" placeholder="e.g. GSTIN certificate is cropped. Please re-upload full scan."></textarea>
                                        
                                        <div class="flex gap-3">
                                            <button type="submit" class="ig-btn ig-btn-accent">
                                                Confirm Rejection
                                            </button>
                                            <button type="button" onclick="toggleDecisionForm('reject', {{ $startup->id }}, true)" class="ig-btn ig-btn-ghost">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Logs History / Audit Trail -->
                                @if($startup->verificationLogs && $startup->verificationLogs->count() > 0)
                                    <div class="mt-6 border-t border-[var(--ig-line)] pt-4">
                                        <button onclick="toggleLogs({{ $startup->id }})" class="text-[var(--ig-accent)] hover:underline font-bold text-xs flex items-center space-x-1 focus:outline-none">
                                            <svg id="logs-arrow-{{ $startup->id }}" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            <span>View Audit Trail / History ({{ $startup->verificationLogs->count() }})</span>
                                        </button>
                                        
                                        <div id="logs-content-{{ $startup->id }}" class="hidden mt-3 space-y-2.5 pl-4 border-l-2 border-[var(--ig-line-2)]">
                                            @foreach($startup->verificationLogs->sortByDesc('created_at') as $log)
                                                <div class="text-[11px] bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                                    <div class="flex items-center justify-between text-[9px] text-[var(--ig-muted)] font-bold mb-1 uppercase tracking-wider">
                                                        <span>Action: {{ str_replace('_', ' ', $log->action) }}</span>
                                                        <span>{{ $log->created_at->format('M d, Y H:i') }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between mt-1">
                                                        <p class="text-[var(--ig-ink-2)]"><span class="font-bold">By:</span> <span class="capitalize text-[var(--ig-accent)] font-semibold">{{ $log->performed_by }}</span></p>
                                                        <p class="text-[var(--ig-muted)] font-mono text-[9px]"><span class="font-sans font-bold">Status:</span> {{ $log->old_status ?? 'none' }} ➔ {{ $log->new_status }}</p>
                                                    </div>
                                                    @if($log->reason)
                                                        <p class="text-[var(--ig-ink-2)] italic mt-1.5 bg-[var(--ig-bg)] p-2 rounded-lg border border-[var(--ig-line-2)] leading-relaxed">
                                                            "{{ $log->reason }}"
                                                        </p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <div class="p-16 text-center">
                                <div class="w-16 h-16 bg-[var(--ig-lime-soft)] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-[var(--ig-lime)]/20">
                                    <svg class="h-8 w-8 text-[var(--ig-lime-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="ig-display text-2xl font-bold">Queue is Clear 🎉</h3>
                                <p class="text-xs text-[var(--ig-muted)] mt-1">No startups are currently awaiting manual verification.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recently Reviewed Panel (Col span 4) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="ig-card p-0 overflow-hidden">
                    <div class="p-6 border-b border-[var(--ig-line)] bg-[var(--ig-bg-2)]">
                        <h2 class="ig-display text-lg font-bold">Recent Decisions</h2>
                    </div>

                    <div class="divide-y divide-[var(--ig-line)]">
                        @forelse($recentlyReviewed as $startup)
                            <div class="p-5 hover:bg-[var(--ig-bg-2)]/30 transition duration-200">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <h4 class="font-bold text-[var(--ig-ink)] text-sm truncate" title="{{ $startup->company_name }}">{{ $startup->company_name }}</h4>
                                    
                                    @if($startup->verification_status === 'approved')
                                        <span class="ig-chip ig-chip-lime" style="font-size: 8.5px; padding: 1.5px 6px;">Approved</span>
                                    @else
                                        <span class="ig-chip ig-chip-accent" style="font-size: 8.5px; padding: 1.5px 6px;">Rejected</span>
                                    @endif
                                </div>
                                <p class="text-xs text-[var(--ig-muted)] truncate">{{ $startup->user->email }}</p>
                                
                                @if($startup->verification_reviewed_at)
                                    <p class="text-[9px] text-[var(--ig-muted)] mt-2 font-semibold">Reviewed: {{ $startup->verification_reviewed_at->format('M d @ H:i') }}</p>
                                @endif

                                @if($startup->verification_status === 'approved' && $startup->startup_trust_score !== null)
                                    <div class="mt-2 bg-[var(--ig-lime-soft)]/20 border border-[var(--ig-lime)]/20 p-2 rounded-xl text-xs flex justify-between items-center">
                                        <span class="text-[var(--ig-lime-deep)] font-bold">Trust Score:</span>
                                        <span class="bg-[var(--ig-lime-deep)] text-white font-extrabold px-2 py-0.5 rounded-lg text-[10px]">{{ $startup->startup_trust_score }}</span>
                                    </div>
                                @endif

                                @if($startup->verification_notes)
                                    <div class="mt-3 p-3 bg-[var(--ig-accent-soft)]/10 rounded-xl border border-[var(--ig-accent-soft)]">
                                        <p class="text-[9px] font-bold text-[var(--ig-accent)] uppercase tracking-wider">Feedback Notes:</p>
                                        <p class="text-xs text-[var(--ig-ink-2)] mt-1 italic leading-relaxed">"{{ Str::limit($startup->verification_notes, 80) }}"</p>
                                    </div>
                                @endif

                                <!-- Suspicious Toggler -->
                                <div class="mt-3">
                                    <form method="POST" action="{{ route('admin.verifications.toggle-suspicious', $startup->id) }}" class="block w-full">
                                        @csrf
                                        @if($startup->is_suspicious)
                                            <button type="submit" class="w-full ig-btn ig-btn-ghost justify-center text-center text-amber-700 border-amber-300 hover:bg-amber-50" style="padding: 6px 12px; font-size: 11px;">
                                                Clear Suspicious
                                            </button>
                                        @else
                                            <button type="submit" class="w-full ig-btn ig-btn-ghost justify-center text-center border-[var(--ig-line-2)] hover:border-red-500 hover:text-red-600" style="padding: 6px 12px; font-size: 11px;">
                                                Flag Suspicious
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-[var(--ig-muted)] text-sm">
                                No recently reviewed verifications
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle scripts -->
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

        function toggleLogs(startupId) {
            const content = document.getElementById(`logs-content-${startupId}`);
            const arrow = document.getElementById(`logs-arrow-${startupId}`);
            if (content) {
                content.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
        }
    </script>
</x-app-layout>
