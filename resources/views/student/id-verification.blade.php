<x-app-layout>
    <div class="ig-container py-12">

        {{-- ── Page Header ────────────────────────────────────────────────────── --}}
        <div class="mb-8 ig-anim-fade-up">
            <p class="ig-eyebrow mb-3">— Academic Proof</p>
            <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                College ID <span class="ig-serif text-[var(--ig-accent)]">Verification.</span>
            </h1>
            <p class="text-sm text-[var(--ig-muted)] mt-2">
                Don't have a college email address? Upload your student ID card and our AI agent will analyze and verify you instantly.
            </p>
        </div>

        {{-- ── Already Verified ────────────────────────────────────────────────── --}}
        @if($profile->is_verified)
            <div class="ig-banner ig-banner-success p-8 text-center flex flex-col items-center justify-center space-y-4 ig-anim-scale-in">
                <div class="w-16 h-16 rounded-full bg-[var(--ig-lime)] flex items-center justify-center text-[var(--ig-ink)] text-2xl font-bold shadow-md">
                    ✓
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[var(--ig-ink)]">You're Verified! 🎉</h2>
                    <p class="text-sm text-[var(--ig-muted)] mt-1">You have full, unlimited access to all internship tasks on InternGrowth.</p>
                    @if($profile->college_name)
                        <p class="font-bold text-emerald-800 text-sm mt-2">{{ $profile->college_name }}</p>
                    @endif
                    <div class="mt-4 flex justify-center gap-2">
                        @if($profile->verification_method === 'college_id_ai')
                            <span class="ig-chip ig-chip-lime">🤖 AI Verified</span>
                        @elseif($profile->verification_method === 'admin_manual')
                            <span class="ig-chip ig-chip-ink">👨‍💼 Admin Verified</span>
                        @else
                            <span class="ig-chip ig-chip-success">📧 Email Verified</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('student.dashboard') }}" class="ig-btn ig-btn-primary">
                    <span>Go to Dashboard</span><span class="arrow">→</span>
                </a>
            </div>

        {{-- ── AI Approved just now ────────────────────────────────────────────── --}}
        @elseif(session('ai_approved'))
            @php $result = session('ai_result', []); @endphp
            <div class="ig-banner ig-banner-success p-8 mb-6 ig-anim-scale-in">
                <div class="flex flex-col sm:flex-row items-center gap-6 w-full">
                    <div class="w-16 h-16 rounded-full bg-[var(--ig-lime)] flex items-center justify-center text-[var(--ig-ink)] text-2xl font-bold shadow-md flex-shrink-0">
                        ✓
                    </div>
                    <div class="flex-1 text-left">
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-xl font-bold text-[var(--ig-ink)]">College ID Verified!</h2>
                            <span class="ig-chip ig-chip-lime">🤖 AI</span>
                        </div>
                        <p class="text-sm text-emerald-800 font-medium mb-3">You now have full access to all internship tasks on InternGrowth.</p>

                        {{-- AI extracted info --}}
                        @if(!empty($result['college_name']) || !empty($result['student_name']))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                                @if(!empty($result['college_name']))
                                    <div class="bg-white/50 rounded-xl p-3 border border-[var(--ig-line)]">
                                        <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">College</p>
                                        <p class="text-xs font-bold text-[var(--ig-ink)] mt-0.5">{{ $result['college_name'] }}</p>
                                    </div>
                                @endif
                                @if(!empty($result['roll_number']))
                                    <div class="bg-white/50 rounded-xl p-3 border border-[var(--ig-line)]">
                                        <p class="text-[9px] text-[var(--ig-muted)] font-bold uppercase tracking-wider">Roll No.</p>
                                        <p class="text-xs font-bold text-[var(--ig-ink)] font-mono mt-0.5">{{ $result['roll_number'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center gap-2 mt-4">
                            @php $conf = $result['confidence'] ?? 0; @endphp
                            <div class="text-[10px] font-bold text-emerald-800 whitespace-nowrap">Confidence: {{ $conf }}%</div>
                            <div class="flex-1 bg-emerald-200/50 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $conf }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('student.dashboard') }}" class="ig-btn ig-btn-primary">
                    <span>🚀 Access All Tasks</span><span class="arrow">→</span>
                </a>
            </div>

        {{-- ── Re-upload Form Override ─────────────────────────────────────────── --}}
        @elseif(request()->has('reupload'))
            @include('student._id-upload-form')

        {{-- ── Manual Review ───────────────────────────────────────────────────── --}}
        @elseif(session('manual_review') || $profile->id_card_verification_status === 'manual_review')
            @php $result = session('ai_result', []); @endphp
            <div class="ig-banner ig-banner-warn p-8 mb-6 ig-anim-scale-in">
                <div class="flex items-start gap-5">
                    <div class="w-12 h-12 bg-amber-200 rounded-full flex-shrink-0 flex items-center justify-center text-xl">
                        ⏳
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-amber-955 mb-1">Under Review</h2>
                        <p class="text-amber-900 text-sm leading-relaxed">Our administration is reviewing your submitted ID card. Verifications are normally processed <strong>within 24 hours</strong>.</p>
                        @if(isset($result['confidence']) && $result['confidence'] > 0)
                            <p class="text-xs text-amber-800 mt-2 font-medium">AI Match Score: <span class="font-bold">{{ $result['confidence'] }}%</span> — just below our auto-approval threshold.</p>
                        @endif
                        <p class="text-xs text-[var(--ig-muted)] mt-3">You will receive an email once the review is complete.</p>
                    </div>
                </div>
            </div>
            <p class="text-center text-sm text-[var(--ig-muted)]">
                Need to re-upload a clearer image?
                <a href="{{ route('student.verify-id', ['reupload' => 1]) }}" class="text-[var(--ig-accent)] font-semibold hover:underline">Upload again</a>
            </p>

        {{-- ── AI Rejected ─────────────────────────────────────────────────────── --}}
        @elseif(session('ai_rejected') || $profile->id_card_verification_status === 'ai_rejected')
            @php
                $result = session('ai_result', $profile->id_card_ai_result ?? []);
                $rejectionReason = $result['admin_rejection_reason'] ?? $result['reason'] ?? null;
            @endphp
            <div class="ig-banner p-6 bg-red-50 border-red-200 mb-6 ig-anim-scale-in">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 flex-shrink-0 text-lg">
                        ✗
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-red-950 mb-1">Verification Failed</h2>
                        @if($rejectionReason)
                            <p class="text-red-900 text-sm font-medium mb-3">{{ $rejectionReason }}</p>
                        @endif
                        <ul class="text-xs text-red-900 space-y-1">
                            <li>• Ensure the full ID card is visible and cropped correctly</li>
                            <li>• Remove any glare or blur from lighting</li>
                            <li>• Upload a real photo of the physical ID, not a screenshot</li>
                            <li>• Must be an official college or university student ID card</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Show upload form again so they can re-upload --}}
            @include('student._id-upload-form')

        {{-- ── Default Upload Form ─────────────────────────────────────────────── --}}
        @else
            @include('student._id-upload-form')
        @endif

        {{-- ── Or verify via email link ────────────────────────────────────────── --}}
        @if(!$profile->is_verified && !session('ai_approved'))
            <div class="mt-8 text-center border-t border-[var(--ig-line)] pt-6">
                <p class="text-xs text-[var(--ig-muted)] mb-2">Have a college email address instead?</p>
                <a href="{{ route('student.verification') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-[var(--ig-azure)] hover:underline">
                    Verify with College Email OTP
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        @endif

    </div>

    <script>
        // Drag and drop
        const zone = document.getElementById('upload-zone');
        const fileInput = document.getElementById('id_card_image');

        if (zone) {
            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('bg-[var(--ig-bg-2)]', 'border-[var(--ig-ink)]');
            });
            zone.addEventListener('dragleave', () => zone.classList.remove('bg-[var(--ig-bg-2)]', 'border-[var(--ig-ink)]'));
            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('bg-[var(--ig-bg-2)]', 'border-[var(--ig-ink)]');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        }

        // Preview selected image
        function previewImage(input) {
            const container = document.getElementById('image-preview-container');
            const img = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    img.src = e.target.result;
                    container.classList.remove('hidden');
                    if (placeholder) placeholder.style.display = 'none';

                    // Update filename display
                    const fname = document.getElementById('selected-filename');
                    if (fname) fname.textContent = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Handle form submit event to show loader and prevent double submission
        const form = document.getElementById('id-upload-form');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('submit-btn');
                if (btn) {
                    btn.innerHTML = `
                        <svg class="animate-spin w-4 h-4 text-white mr-2 inline-block" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        AI is analysing your ID...`;
                    setTimeout(() => { btn.disabled = true; }, 1);
                }

                // Show the processing overlay
                const overlay = document.getElementById('processing-overlay');
                if (overlay) overlay.classList.remove('hidden');
            });
        }
    </script>
</x-app-layout>
