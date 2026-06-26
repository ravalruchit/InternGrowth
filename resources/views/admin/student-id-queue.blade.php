<x-app-layout>
    <div class="ig-container py-10">

        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Manual Validation</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Student <span class="ig-serif text-[var(--ig-accent)]">ID Queue.</span><br>
                    AI flagged validation queue.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <div class="flex flex-wrap gap-3 justify-start md:justify-end">
                    <a href="{{ route('admin.verifications') }}" class="ig-btn ig-btn-ghost">
                        <span>Startup Verifications</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost">
                        <span>← Dashboard</span>
                    </a>
                </div>
            </div>
        </div>


        {{-- Stats Overview --}}
        @php
            $pending  = $pendingQueue->count();
            $approved = $recentlyReviewed->whereIn('id_card_verification_status', ['admin_approved','ai_approved'])->count();
            $rejected = $recentlyReviewed->where('id_card_verification_status','ai_rejected')->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-12 ig-anim-fade-up">
            <div class="ig-card p-6 border-l-4 border-amber-500">
                <p class="ig-eyebrow mb-1 text-amber-700">Pending Review</p>
                <p class="ig-stat-num text-4xl mt-3 text-[var(--ig-ink)]">{{ $pending }}</p>
            </div>
            <div class="ig-card p-6 border-l-4 border-[var(--ig-lime)]">
                <p class="ig-eyebrow mb-1 text-[var(--ig-lime-deep)]">Recently Approved</p>
                <p class="ig-stat-num text-4xl mt-3 text-[var(--ig-ink)]">{{ $approved }}</p>
            </div>
            <div class="ig-card p-6 border-l-4 border-red-500">
                <p class="ig-eyebrow mb-1 text-red-700">Rejected</p>
                <p class="ig-stat-num text-4xl mt-3 text-[var(--ig-ink)]">{{ $rejected }}</p>
            </div>
        </div>

        {{-- Pending Queue List --}}
        <h2 class="ig-display text-2xl mb-6 pb-4 border-b border-[var(--ig-line)] flex items-center gap-2 ig-reveal">
            <span class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-pulse inline-block"></span>
            Awaiting Manual Review
            <span class="ml-2 ig-chip ig-chip-accent">{{ $pending }}</span>
        </h2>

        @forelse($pendingQueue as $profile)
            @php
                $aiResult   = $profile->id_card_ai_result ?? [];
                $confidence = $aiResult['confidence'] ?? 0;
                $confColor  = $confidence >= 80 ? 'var(--ig-lime)' : ($confidence >= 70 ? '#F59E0B' : '#EF4444');
                $flags      = $aiResult['security_flags'] ?? [];
            @endphp
            <div class="ig-card p-6 md:p-8 mb-6 relative overflow-hidden group ig-reveal">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- Left Col: ID Card Image --}}
                    <div class="lg:col-span-3">
                        @if($profile->id_card_path)
                            <div class="relative overflow-hidden rounded-2xl border border-[var(--ig-line-2)] hover:border-[var(--ig-ink)] transition-colors duration-300">
                                <img
                                    src="{{ route('admin.student-id-queue.id-card', $profile->id) }}"
                                    alt="College ID"
                                    class="w-full h-48 object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                                    onclick="openImageModal('{{ route('admin.student-id-queue.id-card', $profile->id) }}')"
                                >
                            </div>
                            <button onclick="openImageModal('{{ route('admin.student-id-queue.id-card', $profile->id) }}')" class="w-full text-center text-xs font-bold text-[var(--ig-accent)] mt-3 hover:underline flex items-center justify-center gap-1">
                                Preview Full Size
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </button>
                        @else
                            <div class="w-full h-48 bg-[var(--ig-bg-2)] border border-dashed border-[var(--ig-line)] rounded-2xl flex items-center justify-center text-[var(--ig-muted)] text-xs">
                                No document file uploaded
                            </div>
                        @endif
                    </div>

                    {{-- Mid Col: Extracted Data --}}
                    <div class="lg:col-span-6 space-y-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h3 class="ig-display text-2xl font-bold">{{ $profile->user->name }}</h3>
                                <p class="ig-mono text-xs text-[var(--ig-muted)] mt-0.5">{{ $profile->user->email }}</p>
                            </div>
                            <span class="ig-chip inline-flex items-center gap-1.5" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border-color: rgba(245, 158, 11, 0.2)">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Manual Review Required
                            </span>
                        </div>

                        {{-- Metadata Cards --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">AI: College Name</p>
                                <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1">{{ $aiResult['college_name'] ?: '—' }}</p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">AI: Student Name</p>
                                <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1">{{ $aiResult['student_name'] ?: '—' }}</p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">AI: Roll Number</p>
                                <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1 font-mono">{{ $aiResult['roll_number'] ?: '—' }}</p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Declared Grad Year</p>
                                <p class="text-sm font-semibold text-[var(--ig-accent)] mt-1">{{ $profile->graduation_year ?: '—' }}</p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Validity Period</p>
                                <p class="text-sm font-semibold text-[var(--ig-ink)] mt-1">{{ $aiResult['validity'] ?: '—' }}</p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-xl p-3">
                                <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Authenticity Status</p>
                                @php $authVal = strtolower($aiResult['authenticity'] ?? ''); @endphp
                                <p class="text-sm font-bold mt-1 uppercase tracking-wide
                                    @if($authVal === 'high') text-emerald-650
                                    @elseif($authVal === 'medium') text-amber-600
                                    @else text-red-600 @endif">
                                    {{ $aiResult['authenticity'] ?? '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- AI analysis card --}}
                        @if(!empty($aiResult['reason']))
                            <div class="bg-[var(--ig-accent-soft)]/20 border border-[var(--ig-accent-soft)] rounded-xl p-4">
                                <p class="text-[10px] text-[var(--ig-accent)] font-bold uppercase tracking-wider mb-1">🤖 AI Assistant Analysis</p>
                                <p class="text-xs text-[var(--ig-ink-2)] leading-relaxed">{{ $aiResult['reason'] }}</p>
                            </div>
                        @endif

                        {{-- Flags list --}}
                        @if(!empty($flags))
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[10px] text-[var(--ig-muted)] font-bold uppercase tracking-wider mr-1">Security Flags:</span>
                                @foreach($flags as $flag)
                                    <span class="ig-chip ig-chip-accent" style="font-size: 10px; padding: 2px 8px;">{{ $flag }}</span>
                                @endforeach
                            </div>
                        @endif

                        <p class="text-[10px] text-[var(--ig-muted)] font-semibold">
                            Submitted on: {{ $profile->id_card_submitted_at?->format('M d, Y @ H:i') ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Right Col: Confidence bar + Actions --}}
                    <div class="lg:col-span-3 lg:border-l lg:border-[var(--ig-line)] lg:pl-8 flex flex-col justify-between h-full space-y-6">
                        <div class="text-center">
                            <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider mb-2">Confidence Match</p>
                            <p class="ig-display text-5xl font-bold" style="color: {{ $confColor }}">{{ $confidence }}%</p>
                            
                            {{-- progress bar --}}
                            <div class="w-full bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-full h-2 overflow-hidden mt-3">
                                <div class="h-full rounded-full transition-all duration-500" style="background: {{ $confColor }}; width: {{ $confidence }}%"></div>
                            </div>
                            <p class="text-[9px] text-[var(--ig-muted)] font-semibold mt-1">Threshold: 90% Confidence Auto-Approve</p>
                        </div>

                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.student-id-queue.approve', $profile->id) }}">
                                @csrf
                                <button type="submit" class="ig-btn ig-btn-lime w-full justify-center text-center">
                                    <span>Approve Talent</span>
                                </button>
                            </form>
                            <button
                                type="button"
                                onclick="openRejectModal({{ $profile->id }})"
                                class="ig-btn ig-btn-ghost w-full justify-center text-center text-red-600 border-red-200/50 hover:bg-red-50"
                            >
                                <span>Reject Document</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="ig-card p-16 text-center ig-reveal">
                <div class="w-16 h-16 bg-[var(--ig-lime-soft)] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-[var(--ig-lime)]/20">
                    <svg class="w-8 h-8 text-[var(--ig-lime-deep)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="ig-display text-2xl font-bold">Queue is Clear 🎉</h3>
                <p class="text-xs text-[var(--ig-muted)] mt-1">No student IDs are currently awaiting manual verification.</p>
            </div>
        @endforelse

        {{-- Decision History --}}
        @if($recentlyReviewed->count() > 0)
            <h2 class="ig-display text-2xl mt-12 mb-6 pb-4 border-b border-[var(--ig-line)] ig-reveal">Decision History</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 ig-reveal">
                @foreach($recentlyReviewed as $profile)
                    @php
                        $status = $profile->id_card_verification_status;
                        $aiRes  = $profile->id_card_ai_result ?? [];
                        $conf   = $aiRes['confidence'] ?? 0;
                        $confBarColor = $conf >= 90 ? 'var(--ig-lime)' : ($conf >= 70 ? '#F59E0B' : '#EF4444');
                    @endphp
                    <div class="ig-card p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div>
                                    <h4 class="font-bold text-sm text-[var(--ig-ink)] line-clamp-1">{{ $profile->user->name }}</h4>
                                    <p class="ig-mono text-[10px] text-[var(--ig-muted)] mt-0.5">{{ $profile->user->email }}</p>
                                </div>
                                @if($status === 'ai_approved')
                                    <span class="ig-chip ig-chip-lime" style="font-size: 8px; padding: 1px 6px;">🤖 AI ✓</span>
                                @elseif($status === 'admin_approved')
                                    <span class="ig-chip ig-chip-success" style="font-size: 8px; padding: 1px 6px;">👨‍💼 ADMIN ✓</span>
                                @else
                                    <span class="ig-chip ig-chip-accent" style="font-size: 8px; padding: 1px 6px;">REJECTED</span>
                                @endif
                            </div>
                            @if(!empty($aiRes['college_name']))
                                <p class="text-xs text-[var(--ig-muted)] font-semibold line-clamp-1">{{ $aiRes['college_name'] }}</p>
                            @endif
                        </div>

                        <div class="mt-4 pt-4 border-t border-[var(--ig-line)]">
                            <div class="flex items-center justify-between text-[10px] font-bold text-[var(--ig-muted)] mb-1.5">
                                <span>CONFIDENCE</span>
                                <span>{{ $conf }}%</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] border border-[var(--ig-line)] rounded-full h-1 overflow-hidden">
                                <div class="h-full rounded-full" style="background: {{ $confBarColor }}; width: {{ $conf }}%"></div>
                            </div>
                            <p class="text-[9px] text-[var(--ig-muted)] mt-2 font-mono">{{ $profile->id_card_verified_at?->format('M d, H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- Glassmorphic Image Preview Modal --}}
    <div id="image-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0f1217]/60 backdrop-blur-md hidden" onclick="closeImageModal()">
        <div class="ig-card max-w-lg w-full p-6 relative overflow-hidden" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-[var(--ig-line)]">
                <h3 class="ig-display text-lg font-bold">Document Preview</h3>
                <button onclick="closeImageModal()" class="text-[var(--ig-muted)] hover:text-[var(--ig-ink)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <img id="modal-img" src="" alt="College ID Card Preview" class="w-full h-auto max-h-[70vh] object-contain rounded-xl border border-[var(--ig-line-2)] bg-[var(--ig-bg)]">
        </div>
    </div>

    {{-- Glassmorphic Rejection Reason Modal --}}
    <div id="reject-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0f1217]/60 backdrop-blur-md hidden" onclick="closeRejectModal()">
        <div class="ig-card max-w-md w-full p-6 relative overflow-hidden" onclick="event.stopPropagation()">
            <h3 class="ig-display text-xl font-bold mb-1 text-[var(--ig-ink)]">Reject Document Credentials</h3>
            <p class="text-xs text-[var(--ig-muted)] mb-4">Provide a clear description of the rejection reasons. This will assist the student to fix the credentials and re-submit.</p>
            
            <form id="reject-form" method="POST" action="">
                @csrf
                <textarea
                    name="notes"
                    rows="4"
                    required
                    placeholder="e.g. Document image is blurry or details do not match profile fields. Please capture a high-quality photo under good lighting."
                    class="ig-input w-full text-xs p-3 mb-4"
                ></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="ig-btn ig-btn-accent flex-1 justify-center">Confirm Rejection</button>
                    <button type="button" onclick="closeRejectModal()" class="ig-btn ig-btn-ghost flex-1 justify-center">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modal-img').src = src;
            document.getElementById('image-modal').classList.remove('hidden');
        }
        function closeImageModal() {
            document.getElementById('image-modal').classList.add('hidden');
        }

        function openRejectModal(profileId) {
            document.getElementById('reject-form').action = `/admin/student-id-queue/${profileId}/reject`;
            document.getElementById('reject-modal').classList.remove('hidden');
        }
        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
