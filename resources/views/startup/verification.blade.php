<x-app-layout>
    <div class="ig-container py-12">
        <div class="ig-card p-6 sm:p-10 md:p-12 relative overflow-hidden transition-all duration-300">
            <!-- Glow Accents -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-purple-300/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-300/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-8 ig-anim-fade-up">
                <!-- Header -->
                <div>
                    <p class="ig-eyebrow mb-2">— Verification Desk</p>
                    <h1 class="ig-display text-4xl sm:text-5xl text-[var(--ig-ink)]">
                        Startup <span class="ig-serif text-[var(--ig-accent)]">Verification.</span>
                    </h1>
                    <p class="text-sm text-[var(--ig-muted)] mt-2">
                        Verify your corporate identity to activate direct placements, internships, candidate discovery, and task postings.
                    </p>
                </div>

                <!-- Status Banners -->
                @if($profile->is_suspicious)
                    <div class="ig-banner p-6 bg-red-50 border-red-200 shadow-md">
                        <div>
                            <h3 class="text-lg font-bold text-red-950">Security Lock Active (Suspicious Activity)</h3>
                            <p class="text-red-900 text-xs mt-1">
                                An administrator has flagged your account. Post creations and offer extenders are locked. Please contact helpdesk support.
                            </p>
                        </div>
                    </div>
                @elseif($profile->verification_status === 'pending' && $profile->verification_submitted_at)
                    <div class="ig-banner ig-banner-warn p-6 shadow-md">
                        <div>
                            <h3 class="text-lg font-bold text-amber-955">Under Administrative Review</h3>
                            <p class="text-amber-900 text-xs mt-1">
                                We've received your company documents and are checking them. Submitted on <strong>{{ $profile->verification_submitted_at->format('M d, Y H:i') }}</strong>.
                            </p>
                        </div>
                    </div>
                @elseif($profile->verification_status === 'rejected')
                    <div class="ig-banner p-6 bg-red-50 border-red-200 shadow-md">
                        <div class="w-full">
                            <h3 class="text-lg font-bold text-red-950">Verification Rejected</h3>
                            @if($profile->verification_notes)
                                <div class="mt-2 p-3 bg-red-100/50 rounded-xl border border-red-200">
                                    <p class="text-red-700 text-xs font-semibold">Feedback reason:</p>
                                    <p class="text-red-800 text-xs italic mt-1">"{{ $profile->verification_notes }}"</p>
                                </div>
                            @endif
                            <p class="text-red-900 text-xs mt-3 font-medium">
                                Please revise details or upload higher quality scans, then submit verification request again.
                            </p>
                        </div>
                    </div>
                @elseif($profile->is_verified)
                    <div class="ig-banner ig-banner-success p-6 shadow-md">
                        <div>
                            <h3 class="text-lg font-bold text-emerald-950">Verified Corporate Account</h3>
                            <p class="text-emerald-900 text-xs mt-1">
                                Your startup status is active and verified! You have full rights to list tasks, search candidates, and send job offers.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- AI Insights Widget -->
                @if($profile->ai_confidence_score !== null || $profile->ai_verification_result)
                    @php
                        $aiResult = $profile->ai_verification_result ?? [];
                        $matchDetails = $aiResult['match_details'] ?? [];
                        $level = $profile->verification_level ?? 'C';
                        $score = $profile->ai_confidence_score ?? 0;
                        $fraudScore = $aiResult['fraud_score'] ?? 0;
                        $fraudRisk = $aiResult['fraud_risk'] ?? 'medium';
                        $fraudFlags = $aiResult['fraud_flags'] ?? [];
                        $reason = $aiResult['reason'] ?? '';
                    @endphp

                    <div class="ig-card-dark p-6 md:p-8 relative overflow-hidden shadow-xl">
                        <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-20" style="background:var(--ig-accent)"></div>
                        
                        <div class="relative border-b border-white/10 pb-4 mb-6 flex justify-between items-center">
                            <h2 class="ig-display text-xl text-white">AI Engine Analytics</h2>
                            <span class="ig-chip ig-chip-lime text-[10px] font-bold">AUTOMATED SYSTEM</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Confidence Level -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
                                <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider mb-2">Confidence Match Level</p>
                                @if($level === 'A')
                                    <span class="ig-chip ig-chip-success font-bold text-sm">LEVEL A</span>
                                    <p class="text-[9px] text-emerald-400 mt-2">Auto-Approved (Score 95+)</p>
                                @elseif($level === 'B')
                                    <span class="ig-chip ig-chip-lime font-bold text-sm">LEVEL B</span>
                                    <p class="text-[9px] text-cyan-400 mt-2">High Trust Match (Score 85-94)</p>
                                @elseif($level === 'C')
                                    <span class="ig-chip ig-chip-warn font-bold text-sm">LEVEL C</span>
                                    <p class="text-[9px] text-amber-400 mt-2">Medium Match Review (Score 70-84)</p>
                                @else
                                    <span class="ig-chip ig-chip-danger font-bold text-sm">LEVEL D</span>
                                    <p class="text-[9px] text-rose-455 mt-2">Flagged / Low Match (&lt;70)</p>
                                @endif
                            </div>

                            <!-- Match Score -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                                <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider mb-2">Confidence match score</p>
                                <p class="text-3xl font-black text-white">{{ $score }}<span class="text-xs text-white/40">/100</span></p>
                                <div class="w-full bg-white/10 rounded-full h-1 mt-2.5">
                                    <div class="bg-[var(--ig-lime)] h-1 rounded-full" style="width: {{ $score }}%"></div>
                                </div>
                            </div>

                            <!-- Fraud Score -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                                <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider mb-2">Fraud Index Rating</p>
                                <p class="text-3xl font-black {{ $fraudScore >= 65 ? 'text-rose-400' : ($fraudScore >= 25 ? 'text-amber-400' : 'text-emerald-400') }}">{{ $fraudScore }}<span class="text-xs text-white/40">/100</span></p>
                                <p class="text-[9px] mt-1 font-bold {{ $fraudRisk === 'high' ? 'text-rose-400' : ($fraudRisk === 'medium' ? 'text-amber-400' : 'text-emerald-400') }}">{{ strtoupper($fraudRisk) }} RISK</p>
                            </div>
                        </div>

                        <!-- Match Details -->
                        <div class="mt-6 border-t border-white/10 pt-6">
                            <h3 class="text-xs font-bold text-[var(--ig-lime)] mb-3 uppercase tracking-wider">Document Scans Breakdown</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                                <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                                    <p class="text-white/40 font-semibold mb-1">Company Title</p>
                                    <p class="text-white font-bold truncate">{{ $matchDetails['extracted_name'] ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-[var(--ig-lime)] mt-1 font-bold">Match: {{ $matchDetails['name_match_score'] ?? 0 }}/20</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                                    <p class="text-white/40 font-semibold mb-1">Registration CIN</p>
                                    <p class="text-white font-bold truncate">{{ $matchDetails['extracted_cin'] ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-[var(--ig-lime)] mt-1 font-bold">Match: {{ $matchDetails['cin_match_score'] ?? 0 }}/30</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                                    <p class="text-white/40 font-semibold mb-1">GSTIN Number</p>
                                    <p class="text-white font-bold truncate">{{ $matchDetails['extracted_gst'] ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-[var(--ig-lime)] mt-1 font-bold">Match: {{ $matchDetails['gst_match_score'] ?? 0 }}/30</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                                    <p class="text-white/40 font-semibold mb-1">Doc Quality Index</p>
                                    <p class="text-white font-bold">Passed</p>
                                    <p class="text-[10px] text-[var(--ig-lime)] mt-1 font-bold">Score: {{ $matchDetails['quality_score'] ?? 0 }}/20</p>
                                </div>
                            </div>
                        </div>

                        <!-- Flags warnings -->
                        @if(!empty($fraudFlags))
                            <div class="mt-4 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                                <h4 class="text-xs font-bold text-red-300">⚠️ Flagged Indicators:</h4>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($fraudFlags as $flag)
                                        <span class="text-[9px] bg-red-500/25 text-red-200 border border-red-500/40 px-2 py-0.5 rounded-full uppercase tracking-wider font-mono">
                                            {{ str_replace('_', ' ', $flag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($reason)
                            <div class="mt-4 p-4 bg-white/5 rounded-2xl text-xs border border-white/5">
                                <p class="text-white/50 font-bold uppercase tracking-wider">AI Reasoning log:</p>
                                <p class="text-white/95 mt-1 italic leading-relaxed">"{{ $reason }}"</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Verified Trust Score Breakdown Widget -->
                @if($profile->is_verified && $profile->startup_trust_score !== null)
                    @php
                        $breakdown = $profile->trust_score_breakdown ?? [
                            'verification' => 50,
                            'completed_tasks' => 0,
                            'successful_internships' => 0,
                            'successful_hires' => 0,
                            'student_reviews' => 0,
                        ];
                    @endphp
                    <div class="ig-card-dark p-6 md:p-8 relative overflow-hidden shadow-xl" style="background: linear-gradient(135deg, #0e1d16 0%, #06110c 100%); border-color: #16462c;">
                        <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl opacity-20" style="background:var(--ig-lime)"></div>
                        <h2 class="ig-display text-xl text-white mb-1">
                            Trust Score: {{ $profile->startup_trust_score }} / 100
                        </h2>
                        <p class="text-xs text-white/50 mb-6">Composite score calculated to build trust in student directories.</p>

                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                                <p class="text-[9px] text-[var(--ig-lime)] font-bold uppercase tracking-wider mb-1">Verification</p>
                                <p class="text-xl font-black text-white">+{{ $breakdown['verification'] ?? 0 }}</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                                <p class="text-[9px] text-[var(--ig-lime)] font-bold uppercase tracking-wider mb-1">Tasks Done</p>
                                <p class="text-xl font-black text-white">+{{ $breakdown['completed_tasks'] ?? 0 }}</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                                <p class="text-[9px] text-[var(--ig-lime)] font-bold uppercase tracking-wider mb-1">Internships</p>
                                <p class="text-xl font-black text-white">+{{ $breakdown['successful_internships'] ?? 0 }}</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                                <p class="text-[9px] text-[var(--ig-lime)] font-bold uppercase tracking-wider mb-1">Hires</p>
                                <p class="text-xl font-black text-white">+{{ $breakdown['successful_hires'] ?? 0 }}</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                                <p class="text-[9px] text-[var(--ig-lime)] font-bold uppercase tracking-wider mb-1">Reviews</p>
                                <p class="text-xl font-black text-white">{{ ($breakdown['student_reviews'] ?? 0) >= 0 ? '+' : '' }}{{ $breakdown['student_reviews'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Document Form -->
                <form method="POST" action="{{ route('startup.verification.submit') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                            Company Name <span class="text-[var(--ig-rose)]">*</span>
                        </label>
                        <input type="text" name="company_name" 
                               value="{{ old('company_name', $profile->company_name) }}" 
                               required
                               class="ig-input"
                               placeholder="e.g. InternGrowth Technologies Private Limited">
                        @error('company_name')
                            <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Registration Number -->
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                Company Registration / Incorporation Number (CIN) <span class="text-[var(--ig-rose)]">*</span>
                            </label>
                            <input type="text" name="company_registration_number" 
                                   value="{{ old('company_registration_number', $profile->company_registration_number) }}" 
                                   required
                                   class="ig-input"
                                   placeholder="e.g. U72900GJ2025PTC123456">
                            @error('company_registration_number')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GST Number -->
                        <div>
                            <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                                GSTIN <span class="text-[var(--ig-rose)]">*</span>
                            </label>
                            <input type="text" name="gst_number" 
                                   value="{{ old('gst_number', $profile->gst_number) }}"
                                   required
                                   class="ig-input"
                                   placeholder="e.g. 22AAAAA0000A1Z5">
                            @error('gst_number')
                                <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                            Official Contact Phone Number <span class="text-[var(--ig-rose)]">*</span>
                        </label>
                        <input type="tel" name="contact_phone" 
                               value="{{ old('contact_phone', $profile->contact_phone) }}" 
                               required
                               class="ig-input"
                               placeholder="e.g. +91 98765 43210">
                        @error('contact_phone')
                            <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Address -->
                    <div>
                        <label class="block text-sm font-semibold text-[var(--ig-ink)] mb-2">
                            Registered Office Address <span class="text-[var(--ig-rose)]">*</span>
                        </label>
                        <textarea name="company_address" rows="3" required
                                  class="ig-input resize-none"
                                  placeholder="Enter the full address as registered in official documents">{{ old('company_address', $profile->company_address) }}</textarea>
                        @error('company_address')
                            <p class="text-[var(--ig-rose)] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Upload Zone -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-[var(--ig-ink)] border-b border-[var(--ig-line)] pb-2 uppercase tracking-wider">Verification Documents</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Registration/CIN Document -->
                            <div class="flex flex-col">
                                <label class="block text-xs font-bold text-[var(--ig-muted)] mb-2">
                                    Registration / CIN Certificate <span class="text-[var(--ig-rose)]">*</span>
                                </label>
                                <div class="relative border-2 border-dashed border-[var(--ig-line-2)] rounded-2xl p-4 text-center hover:border-[var(--ig-accent)] hover:bg-[var(--ig-bg-2)] transition-all duration-300 group flex-1 flex flex-col justify-center items-center min-h-[150px]">
                                    <input type="file" name="registration_document" accept=".pdf,.jpg,.jpeg,.png" id="reg_doc"
                                           @if(!$profile->verification_documents || !isset($profile->verification_documents['registration_document'])) required @endif
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                        <div class="bg-[var(--ig-bg-2)] p-2 rounded-xl text-sm">📁</div>
                                        <span class="text-xs font-semibold text-gray-700">Choose CIN File</span>
                                        <span class="text-[9px] text-gray-400 font-medium" id="reg-file-name">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                </div>
                                @error('registration_document')
                                    <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- GST Certificate -->
                            <div class="flex flex-col">
                                <label class="block text-xs font-bold text-[var(--ig-muted)] mb-2">
                                    GST Certificate <span class="text-[var(--ig-rose)]">*</span>
                                </label>
                                <div class="relative border-2 border-dashed border-[var(--ig-line-2)] rounded-2xl p-4 text-center hover:border-[var(--ig-accent)] hover:bg-[var(--ig-bg-2)] transition-all duration-300 group flex-1 flex flex-col justify-center items-center min-h-[150px]">
                                    <input type="file" name="gst_certificate" accept=".pdf,.jpg,.jpeg,.png" id="gst_doc"
                                           @if(!$profile->verification_documents || !isset($profile->verification_documents['gst_certificate'])) required @endif
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                        <div class="bg-[var(--ig-bg-2)] p-2 rounded-xl text-sm">📁</div>
                                        <span class="text-xs font-semibold text-gray-700">Choose GST File</span>
                                        <span class="text-[9px] text-gray-400 font-medium" id="gst-file-name">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                </div>
                                @error('gst_certificate')
                                    <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supporting Document (Director's ID Proof) -->
                            <div class="flex flex-col">
                                <label class="block text-xs font-bold text-[var(--ig-muted)] mb-2">
                                    Director's ID Proof <span class="text-[var(--ig-rose)]">*</span>
                                </label>
                                <div class="relative border-2 border-dashed border-[var(--ig-line-2)] rounded-2xl p-4 text-center hover:border-[var(--ig-accent)] hover:bg-[var(--ig-bg-2)] transition-all duration-300 group flex-1 flex flex-col justify-center items-center min-h-[150px]">
                                    <input type="file" name="supporting_document" accept=".pdf,.jpg,.jpeg,.png" id="sup_doc"
                                           @if(!$profile->verification_documents || !isset($profile->verification_documents['supporting_document'])) required @endif
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                        <div class="bg-[var(--ig-bg-2)] p-2 rounded-xl text-sm">📁</div>
                                        <span class="text-xs font-semibold text-gray-700">Choose ID File</span>
                                        <span class="text-[9px] text-gray-400 font-medium" id="sup-file-name">PDF, JPG, PNG (Max 5MB)</span>
                                    </div>
                                </div>
                                @error('supporting_document')
                                    <p class="text-[var(--ig-rose)] text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Previously Uploaded -->
                    @if($profile->verification_documents && count($profile->verification_documents) > 0)
                        <div class="p-6 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-2xl">
                            <h3 class="font-bold text-[var(--ig-ink)] text-sm mb-3">Currently Registered Verification Files:</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach($profile->verification_documents as $key => $doc)
                                    <div class="flex items-center justify-between p-3 bg-white border border-[var(--ig-line)] rounded-xl text-xs">
                                        <div class="flex flex-col truncate pr-2">
                                            <span class="text-[9px] text-[var(--ig-accent)] font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                            <span class="text-[var(--ig-ink-2)] truncate font-semibold mt-0.5" title="{{ $doc['name'] }}">{{ $doc['name'] }}</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="text-[var(--ig-azure)] hover:text-indigo-850 font-bold flex-shrink-0">
                                            View ↗
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4 items-center border-t border-[var(--ig-line)]">
                        <button type="submit" class="ig-btn ig-btn-primary w-full sm:w-auto">
                            Submit Verification Request
                        </button>
                        <a href="{{ route('startup.dashboard') }}" class="ig-btn ig-btn-ghost w-full sm:w-auto justify-center">
                            Back to Dashboard
                        </a>

                        @php
                            $recentAttemptsCount = \App\Models\StartupVerificationLog::where('startup_profile_id', $profile->id)
                                ->where('action', 'verification_submitted')
                                ->where('created_at', '>=', now()->subHours(24))
                                ->count();
                        @endphp
                        <div class="text-[10px] text-[var(--ig-muted)] font-mono flex items-center gap-1 sm:ml-auto">
                            ⏳ Submissions in last 24h: {{ $recentAttemptsCount }} / 3
                        </div>
                    </div>
                </form>
            </div>

            <!-- Scripts -->
            <script>
                document.getElementById('reg_doc').addEventListener('change', function(e) {
                    const fileName = this.files.length > 0 ? this.files[0].name : "PDF, JPG, PNG (Max 5MB)";
                    document.getElementById('reg-file-name').textContent = fileName;
                });
                document.getElementById('gst_doc').addEventListener('change', function(e) {
                    const fileName = this.files.length > 0 ? this.files[0].name : "PDF, JPG, PNG (Max 5MB)";
                    document.getElementById('gst-file-name').textContent = fileName;
                });
                document.getElementById('sup_doc').addEventListener('change', function(e) {
                    const fileName = this.files.length > 0 ? this.files[0].name : "PDF, JPG, PNG (Max 5MB)";
                    document.getElementById('sup-file-name').textContent = fileName;
                });
            </script>
        </div>
    </div>
</x-app-layout>
