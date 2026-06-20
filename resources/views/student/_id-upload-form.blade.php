{{-- Reusable upload form partial ─ included in id-verification.blade.php --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

    {{-- Left: Upload Form ── --}}
    <div class="lg:col-span-3">
        <div class="ig-card p-6 sm:p-8">
            <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-6">
                Upload Student ID
            </h2>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    @foreach($errors->all() as $error)
                        <p class="text-red-900 text-xs font-semibold">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('student.verify-id.submit') }}" enctype="multipart/form-data" id="id-upload-form" class="space-y-6">
                @csrf

                {{-- Drop Zone --}}
                <div class="relative border-2 border-dashed border-[var(--ig-line-2)] rounded-2xl p-8 text-center bg-white hover:border-[var(--ig-accent)] hover:bg-[var(--ig-bg-2)] transition-all duration-350 cursor-pointer" id="upload-zone">
                    <input
                        type="file"
                        name="id_card_image"
                        id="id_card_image"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        onchange="previewImage(this)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                    >

                    <div id="upload-placeholder">
                        <div class="w-14 h-14 bg-[var(--ig-accent-soft)] rounded-2xl flex items-center justify-center mx-auto mb-4 text-[var(--ig-accent)] text-xl font-bold">
                            📤
                        </div>
                        <p class="text-[var(--ig-accent)] font-bold text-base">Drag & Drop your ID card here</p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1">or <span class="font-semibold text-[var(--ig-ink)] underline">browse local files</span></p>
                        <p class="text-[10px] text-[var(--ig-faint)] mt-4">JPG · PNG · WebP · Max 5 MB</p>
                    </div>

                    {{-- Image preview --}}
                    <div id="image-preview-container" class="hidden mt-2">
                        <img id="image-preview" src="" alt="Preview" class="mx-auto max-h-48 rounded-xl border border-[var(--ig-line-2)] object-contain bg-[var(--ig-bg)]">
                        <p class="text-xs text-[var(--ig-accent)] font-semibold mt-2" id="selected-filename"></p>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-1">Click or drag new file to change</p>
                    </div>
                </div>

                {{-- Graduation Year --}}
                <div>
                    <label for="graduation_year" class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                        Expected or Actual Graduation Year
                    </label>
                    <select 
                        name="graduation_year" 
                        id="graduation_year" 
                        required 
                        class="ig-input"
                    >
                        <option value="" disabled selected>Select Graduation Year</option>
                        @for ($year = date('Y') + 5; $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ old('graduation_year', $profile->graduation_year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    <p class="text-[11px] text-[var(--ig-muted)] mt-2">
                        Note: To qualify for student opportunities, you must be currently studying or have graduated within the last 2 years ({{ date('Y') - 2 }} or later).
                    </p>
                </div>

                {{-- Processing Overlay --}}
                <div id="processing-overlay" class="hidden p-6 rounded-2xl text-center bg-[var(--ig-bg-2)] border border-[var(--ig-line)]">
                    <div class="w-10 h-10 border-4 border-[var(--ig-line-2)] border-t-[var(--ig-accent)] rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-[var(--ig-ink)] font-bold text-sm">🤖 AI is scanning your ID card...</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Extracting college details, credentials and running validation checks</p>
                </div>

                <button
                    type="submit"
                    id="submit-btn"
                    class="ig-btn ig-btn-primary w-full justify-center"
                >
                    <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span>Verify with AI Agent</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Right: Tips & Details --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- How it Works --}}
        <div class="ig-card p-6">
            <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Verification Flow</h3>
            <div class="space-y-4">
                @php
                    $steps = [
                        ['icon' => '📤', 'title' => 'Submit Photo', 'desc' => 'Provide a clear image of your student ID card.'],
                        ['icon' => '🤖', 'title' => 'AI Extraction', 'desc' => 'AI agent analyzes the document for authenticity.'],
                        ['icon' => '📊', 'title' => 'Auto-Scoring', 'desc' => 'Confidence index is generated by our scan system.'],
                        ['icon' => '✅', 'title' => 'Instant Result', 'desc' => '90%+ = Approved instantly, 70-89% = Admin review.'],
                    ];
                @endphp
                @foreach($steps as $i => $step)
                    <div class="flex items-start gap-3">
                        <span class="inline-flex w-6 h-6 items-center justify-center rounded-full bg-[var(--ig-bg-2)] text-xs font-mono font-bold text-[var(--ig-ink)]">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <p class="text-xs font-bold text-[var(--ig-ink)]">{{ $step['icon'] }} {{ $step['title'] }}</p>
                            <p class="text-[11px] text-[var(--ig-muted)] mt-0.5">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tips --}}
        <div class="ig-card p-6">
            <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-3">📸 Image Quality Tips</h3>
            <ul class="space-y-2">
                @foreach([
                    'Ensure card is flat on a solid background.',
                    'Text (name, roll no, university) must be legible.',
                    'Card borders should be fully in frame.',
                    'Avoid overhead reflections, glare or blur.',
                    'Provide actual photos over digital file exports.'
                ] as $tip)
                    <li class="flex items-start gap-2 text-[11px] text-[var(--ig-ink-2)]">
                        <span class="text-[var(--ig-accent)]">•</span>
                        <span>{{ $tip }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Security Note --}}
        <div class="ig-banner ig-banner-success text-xs">
            <div>
                <p class="font-semibold text-emerald-950 mb-1">🔒 Safe & Secure</p>
                <p class="text-emerald-900 leading-normal">Your document is protected and used solely to run verification. It remains hidden from other members or startups.</p>
            </div>
        </div>

    </div>
</div>
