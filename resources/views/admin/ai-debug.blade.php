<x-app-layout>
    <style>
        /* Cream-themed drag-and-drop zone */
        .drag-zone {
            border: 2px dashed var(--ig-line-2);
            background: var(--ig-bg-2);
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .drag-zone:hover, .drag-zone.dragover {
            border-color: var(--ig-ink);
            background: rgba(236, 231, 220, 0.8);
            transform: scale(1.005);
        }

        /* Custom scrollbar for developer code logs */
        .pretty-scroll::-webkit-scrollbar {
            width: 8px; height: 8px;
        }
        .pretty-scroll::-webkit-scrollbar-track {
            background: #0F1217;
        }
        .pretty-scroll::-webkit-scrollbar-thumb {
            background: #2B3038;
            border-radius: 4px;
        }
    </style>

    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Diagnostics</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    AI <span class="ig-serif text-[var(--ig-accent)]">Debugger.</span><br>
                    Real-time Gemini diagnostics.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- ── Left Sidebar: Configuration ── --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="ig-card-dark p-6">
                    <h3 class="ig-display text-xl mb-5 flex items-center gap-2 text-[#F2EEE5]">
                        <svg class="w-5 h-5 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>System Config</span>
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-xxs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Active model</span>
                            <div class="mt-1.5 flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5">
                                <span class="w-2 h-2 rounded-full bg-[var(--ig-accent)]"></span>
                                <span class="font-mono text-xs text-white">{{ $config['gemini_model'] }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xxs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">API Key status</span>
                            <div class="mt-1.5">
                                @if($config['gemini_key_configured'])
                                    <div class="flex items-center justify-between bg-white/5 border border-emerald-500/30 rounded-xl px-4 py-2.5">
                                        <span class="text-xs font-bold text-emerald-400">Key Configured</span>
                                        <span class="font-mono text-[10px] text-emerald-300 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">{{ $config['gemini_key_preview'] }}</span>
                                    </div>
                                @else
                                    <div class="bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-2.5">
                                        <span class="text-xs font-bold text-red-400">Key Missing (.env)</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <span class="text-xxs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">GD Extension</span>
                            <div class="mt-1.5">
                                @if($config['gd_loaded'])
                                    <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-[#F2EEE5] flex items-center justify-between">
                                        <span class="font-bold text-emerald-400">GD Extension Loaded</span>
                                        <span class="text-[10px] font-mono text-[var(--ig-muted)]">Active</span>
                                    </div>
                                @else
                                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl px-4 py-2.5 text-xs text-amber-400 font-bold">
                                        ⚠️ GD Missing (Raw images used)
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                                <span class="text-[9px] font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Approval Thresh</span>
                                <p class="text-lg font-bold text-white mt-0.5">{{ $config['confidence_threshold'] }}%</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                                <span class="text-[9px] font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Review Thresh</span>
                                <p class="text-lg font-bold text-white mt-0.5">{{ $config['manual_threshold'] }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Diagnostic Tips --}}
                <div class="ig-card-dark p-6 bg-gradient-to-br from-stone-900 to-slate-900 border-none text-[#F2EEE5]">
                    <h4 class="font-bold text-sm mb-3">🛠️ Common Issue Checks</h4>
                    <ul class="text-xs text-stone-300 space-y-2.5 font-mono">
                        <li class="flex gap-2">
                            <span class="text-[var(--ig-accent)] font-bold">•</span>
                            <span><strong>HTTP 429:</strong> API rate limit reached.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-[var(--ig-accent)] font-bold">•</span>
                            <span><strong>HTTP 503:</strong> Gemini service overloaded.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-[var(--ig-accent)] font-bold">•</span>
                            <span><strong>JSON error:</strong> Increase <code>maxOutputTokens</code> payload.</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- ── Right Area: Test Zone & Diagnostics Output ── --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Test Form --}}
                <div class="ig-card p-6 bg-white">
                    <h3 class="ig-display text-xl mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Diagnostic Playground</span>
                    </h3>

                    <form id="debug-form" enctype="multipart/form-data">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="student_name" class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Test Student Name</label>
                                <input type="text" id="student_name" name="student_name" value="Ruchit Raval" class="ig-input font-bold">
                            </div>
                            <div>
                                <label for="graduation_year" class="block text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider mb-2 font-mono">Test Graduation Year</label>
                                <select id="graduation_year" name="graduation_year" class="ig-input font-mono font-bold text-xs bg-white">
                                    <option value="">No Graduation Year</option>
                                    @for ($year = date('Y') + 5; $year >= date('Y') - 5; $year--)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Drag Drop Card --}}
                        <div class="drag-zone p-8 rounded-2xl text-center border-2 border-dashed relative mb-6" id="drop-zone">
                            <input type="file" id="test_image" name="test_image" accept="image/jpeg,image/jpg,image/png,image/webp" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                            <div id="upload-prompt">
                                <div class="w-12 h-12 rounded-2xl bg-stone-900 text-white flex items-center justify-center mx-auto mb-3 border border-stone-850">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-[var(--ig-ink)] font-bold text-sm">Drag & Drop test ID image here</p>
                                <p class="text-xs text-[var(--ig-muted)] mt-1 font-mono">JPEG, PNG, WebP · Max 10MB</p>
                            </div>
                            <div id="upload-preview" class="hidden">
                                <img src="" id="img-preview-tag" class="max-h-40 rounded-xl mx-auto border border-[var(--ig-line)] shadow-sm bg-white p-1">
                                <p class="text-xs font-bold text-[var(--ig-accent)] mt-3" id="file-details"></p>
                                <p class="text-[10px] text-[var(--ig-muted)] font-mono">Click or drag over to replace image</p>
                            </div>
                        </div>

                        <button type="submit" id="run-btn" class="ig-btn ig-btn-primary w-full justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>Execute Real-time AI Analysis</span>
                        </button>
                    </form>
                </div>

                {{-- Loading Overlay --}}
                <div id="loader-panel" class="hidden ig-card p-10 text-center animate-pulse border-[var(--ig-line-2)] bg-[var(--ig-bg-2)]/60">
                    <div class="w-10 h-10 border-4 border-[var(--ig-line-2)] border-t-[var(--ig-accent)] rounded-full animate-spin mx-auto mb-4"></div>
                    <h4 class="font-bold text-[var(--ig-ink)] text-lg">Communicating with Google Gemini...</h4>
                    <p class="text-xs text-[var(--ig-muted)] mt-1.5 font-mono">Uploading, scaling, executing OCR text scanner, and compiling payload decision.</p>
                </div>

                {{-- Diagnostics Output Panel --}}
                <div id="results-panel" class="hidden space-y-6">
                    <div class="ig-card p-6 bg-white">
                        <div class="flex items-center justify-between border-b border-[var(--ig-line)] pb-4 mb-4">
                            <div>
                                <h3 class="ig-display text-xl">Diagnostic Summary</h3>
                                <p class="text-xs text-[var(--ig-muted)] mt-0.5">API statistics & decision payload</p>
                            </div>
                            <span id="badge-outcome" class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide"></span>
                        </div>

                        {{-- Performance Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                            <div class="bg-[var(--ig-bg-2)]/50 rounded-xl p-3 border border-[var(--ig-line)]">
                                <span class="text-[10px] font-semibold text-[var(--ig-muted)] uppercase font-mono">Time Elapsed</span>
                                <p class="text-sm font-bold text-[var(--ig-ink)] mt-0.5" id="val-time"></p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)]/50 rounded-xl p-3 border border-[var(--ig-line)]">
                                <span class="text-[10px] font-semibold text-[var(--ig-muted)] uppercase font-mono">HTTP Status</span>
                                <p class="text-sm font-bold text-[var(--ig-ink)] mt-0.5 font-mono" id="val-status"></p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)]/50 rounded-xl p-3 border border-[var(--ig-line)]">
                                <span class="text-[10px] font-semibold text-[var(--ig-muted)] uppercase font-mono">GD Compression</span>
                                <p class="text-sm font-bold text-emerald-700 mt-0.5" id="val-ratio"></p>
                            </div>
                            <div class="bg-[var(--ig-bg-2)]/50 rounded-xl p-3 border border-[var(--ig-line)]">
                                <span class="text-[10px] font-semibold text-[var(--ig-muted)] uppercase font-mono">Tokens Used</span>
                                <p class="text-sm font-bold text-[var(--ig-ink)] mt-0.5 font-mono" id="val-tokens"></p>
                            </div>
                        </div>

                        {{-- Extracted Fields --}}
                        <div class="mb-6">
                            <h4 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-3 font-mono">Extracted Fields</h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="bg-[var(--ig-bg-2)]/40 rounded-xl p-3.5 border border-[var(--ig-line)] flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] font-semibold text-[var(--ig-muted)] block uppercase font-mono">Name Matching</span>
                                        <span class="text-sm font-bold text-[var(--ig-ink)]" id="field-name"></span>
                                    </div>
                                    <span id="badge-name-match" class="text-[9px] px-2 py-0.5 rounded font-extrabold uppercase font-mono"></span>
                                </div>
                                <div class="bg-[var(--ig-bg-2)]/40 rounded-xl p-3.5 border border-[var(--ig-line)]">
                                    <span class="text-[9px] font-semibold text-[var(--ig-muted)] block uppercase font-mono">College name</span>
                                    <span class="text-sm font-bold text-[var(--ig-ink)]" id="field-college"></span>
                                </div>
                                <div class="bg-[var(--ig-bg-2)]/40 rounded-xl p-3.5 border border-[var(--ig-line)]">
                                    <span class="text-[9px] font-semibold text-[var(--ig-muted)] block uppercase font-mono">Roll / Enroll number</span>
                                    <span class="text-sm font-bold text-[var(--ig-ink)] font-mono" id="field-roll"></span>
                                </div>
                                <div class="bg-[var(--ig-bg-2)]/40 rounded-xl p-3.5 border border-[var(--ig-line)]">
                                    <span class="text-[9px] font-semibold text-[var(--ig-muted)] block uppercase font-mono">Validity/Academic Year</span>
                                    <span class="text-sm font-bold text-[var(--ig-ink)]" id="field-validity"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Confidence & Flags --}}
                        <div class="grid sm:grid-cols-3 gap-4 border-t border-[var(--ig-line)] pt-6">
                            <div class="sm:col-span-1 text-center flex flex-col items-center justify-center bg-[var(--ig-bg-2)]/30 border border-[var(--ig-line)] p-4 rounded-2xl">
                                <span class="text-xxs font-semibold text-[var(--ig-muted)] uppercase block mb-2 font-mono">Confidence</span>
                                <div class="w-16 h-16 rounded-full flex items-center justify-center text-lg font-black" id="score-meter"></div>
                            </div>
                            <div class="sm:col-span-2 space-y-3">
                                <div>
                                    <span class="text-xxs font-semibold text-[var(--ig-muted)] uppercase block font-mono">Security Warnings / flags</span>
                                    <div class="mt-1.5 flex flex-wrap gap-2" id="val-flags"></div>
                                </div>
                                <div>
                                    <span class="text-xxs font-semibold text-[var(--ig-muted)] uppercase block font-mono">Decision logic / Reason</span>
                                    <p class="text-xs text-[var(--ig-ink-2)] font-medium mt-1 leading-relaxed" id="val-reason"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Raw JSON Block --}}
                    <div class="ig-card-dark overflow-hidden">
                        <div class="bg-stone-900 px-6 py-4 flex items-center justify-between text-white border-b border-stone-850">
                            <h4 class="font-mono text-xs font-bold text-stone-400">🖥️ Raw API Response payload</h4>
                            <button onclick="copyRawJson()" class="text-xxs font-bold bg-white/10 hover:bg-white/20 border border-white/10 rounded px-2.5 py-1 transition flex items-center gap-1">
                                📋 Copy JSON
                            </button>
                        </div>
                        <pre class="pretty-scroll bg-[#0F1217] text-emerald-400 p-6 overflow-auto max-h-96 font-mono text-xs" id="raw-json-output"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('test_image');
        const uploadPrompt = document.getElementById('upload-prompt');
        const uploadPreview = document.getElementById('upload-preview');
        const imgPreviewTag = document.getElementById('img-preview-tag');
        const fileDetails = document.getElementById('file-details');
 
        // Drag events
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });
            dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        }

        // Preview image
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    imgPreviewTag.src = e.target.result;
                    uploadPrompt.classList.add('hidden');
                    uploadPreview.classList.remove('hidden');

                    const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                    fileDetails.textContent = `${file.name} (${sizeMb} MB)`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Form Submit
        const debugForm = document.getElementById('debug-form');
        const loaderPanel = document.getElementById('loader-panel');
        const resultsPanel = document.getElementById('results-panel');
        const runBtn = document.getElementById('run-btn');

        debugForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!fileInput.files.length) {
                alert('Please select or drop a test image first.');
                return;
            }

            // UI loading states
            runBtn.disabled = true;
            loaderPanel.classList.remove('hidden');
            resultsPanel.classList.add('hidden');

            const formData = new FormData(this);

            fetch('{{ route('admin.ai-debug.test') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    if (data.raw_api_response) {
                        document.getElementById('raw-json-output').textContent = JSON.stringify(data.raw_api_response, null, 2);
                        document.getElementById('results-panel').classList.remove('hidden');
                    }
                    throw new Error(data.error || 'Server error occurred');
                }
                return data;
            })
            .then(data => {
                // Populate summary statistics
                document.getElementById('val-time').textContent = `${data.elapsed_seconds}s`;
                document.getElementById('val-status').textContent = data.status_code;
                document.getElementById('val-ratio').textContent = data.compression_ratio;

                const origKb = (data.original_size / 1024).toFixed(1);
                const compKb = (data.compressed_size / 1024).toFixed(1);
                document.getElementById('val-ratio').title = `Original: ${origKb}KB -> Compressed: ${compKb}KB | ${data.dimensions}`;

                const tokenText = data.usage_metadata.totalTokenCount
                    ? `${data.usage_metadata.totalTokenCount} tokens`
                    : 'N/A';
                document.getElementById('val-tokens').textContent = tokenText;

                // Extract Fields
                const parsed = data.parsed_response;
                if (parsed.parse_success) {
                    document.getElementById('field-name').textContent = parsed.student_name || 'N/A';
                    document.getElementById('field-college').textContent = parsed.college_name || 'N/A';
                    document.getElementById('field-roll').textContent = parsed.roll_number || 'N/A';
                    document.getElementById('field-validity').textContent = parsed.validity || 'N/A';

                    // Match badge
                    const matchBadge = document.getElementById('badge-name-match');
                    if (parsed.name_match) {
                        matchBadge.textContent = 'Match';
                        matchBadge.className = 'text-[9px] px-2 py-0.5 rounded font-extrabold uppercase bg-emerald-100 text-emerald-800 font-mono';
                    } else {
                        matchBadge.textContent = 'Mismatch';
                        matchBadge.className = 'text-[9px] px-2 py-0.5 rounded font-extrabold uppercase bg-red-100 text-red-800 font-mono';
                    }

                    // Confidence Meter
                    const confidence = parseInt(parsed.confidence) || 0;
                    const scoreMeter = document.getElementById('score-meter');
                    scoreMeter.textContent = `${confidence}%`;
                    if (confidence >= 90) {
                        scoreMeter.className = 'w-16 h-16 rounded-full flex items-center justify-center text-sm font-black bg-emerald-100 text-emerald-800 border-2 border-emerald-300 font-mono';
                    } else if (confidence >= 70) {
                        scoreMeter.className = 'w-16 h-16 rounded-full flex items-center justify-center text-sm font-black bg-amber-100 text-amber-800 border-2 border-amber-300 font-mono';
                    } else {
                        scoreMeter.className = 'w-16 h-16 rounded-full flex items-center justify-center text-sm font-black bg-red-100 text-red-800 border-2 border-red-300 font-mono';
                    }

                    // Flags
                    const flagsDiv = document.getElementById('val-flags');
                    flagsDiv.innerHTML = '';
                    if (parsed.security_flags && parsed.security_flags.length > 0) {
                        parsed.security_flags.forEach(flag => {
                            const badge = document.createElement('span');
                            badge.className = 'text-[10px] font-bold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-lg border border-amber-200 font-mono';
                            badge.textContent = flag;
                            flagsDiv.appendChild(badge);
                        });
                    } else {
                        flagsDiv.innerHTML = '<span class="text-xs font-semibold text-gray-400 font-mono">None detected</span>';
                    }

                    // Reason
                    document.getElementById('val-reason').textContent = parsed.reason || 'No detailed reason provided.';

                    // Outcome Recommendation
                    const outcomeBadge = document.getElementById('badge-outcome');
                    const recommendation = parsed.recommendation || 'manual_review';
                    outcomeBadge.textContent = recommendation.replace('_', ' ');
                    if (recommendation === 'approve') {
                        outcomeBadge.className = 'px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide bg-emerald-100 text-emerald-800 font-mono';
                    } else if (recommendation === 'manual_review') {
                        outcomeBadge.className = 'px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide bg-amber-100 text-amber-800 font-mono';
                    } else {
                        outcomeBadge.className = 'px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide bg-red-100 text-red-800 font-mono';
                    }
                } else {
                    alert('Gemini API answered but the JSON parsing failed! Inspect the raw output at the bottom of the page.');
                }

                // Raw JSON
                document.getElementById('raw-json-output').textContent = JSON.stringify(data.raw_api_response, null, 2);

                // Show Panel
                resultsPanel.classList.remove('hidden');
            })
            .catch(error => {
                alert(`Error: ${error.message}`);
                console.error(error);
            })
            .finally(() => {
                runBtn.disabled = false;
                loaderPanel.classList.add('hidden');
            });
        });

        function copyRawJson() {
            const codeText = document.getElementById('raw-json-output').textContent;
            navigator.clipboard.writeText(codeText).then(() => {
                alert('Raw API response JSON copied to clipboard!');
            });
        }
    </script>
</x-app-layout>
