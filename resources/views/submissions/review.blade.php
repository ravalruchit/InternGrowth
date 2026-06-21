<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Review Submission</h1>
            
            @if(session('success'))
                <div id="success-message" class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded" style="transition: opacity 0.5s ease-out;">
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div id="error-message" class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded" style="transition: opacity 0.5s ease-out;">
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Task</h3>
                <p class="text-gray-900">{{ $submission->application->task->title }}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Student</h3>
                <p class="text-gray-900">{{ $submission->application->student->user->name }}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Submission Content</h3>
                @if($submission->status !== 'rejected')
                    <div class="bg-gray-50 p-4 rounded select-none" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none;" oncontextmenu="return false;" oncopy="return false;" oncut="return false;">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $submission->content }}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">⚠️ Content is protected - copying and downloading disabled</p>
                @else
                    <div class="bg-red-50 p-4 rounded border-l-4 border-red-400">
                        <p class="text-red-800">Content hidden - Submission was rejected</p>
                    </div>
                @endif
            </div>

            @php
                $hasValidFiles = false;
                if ($submission->files && is_array($submission->files)) {
                    foreach ($submission->files as $file) {
                        if (is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])) {
                            $hasValidFiles = true;
                            break;
                        }
                    }
                }
            @endphp

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Attached Files:</h3>
                @if($submission->status === 'rejected')
                    <div class="bg-red-50 p-4 rounded border-l-4 border-red-400">
                        <p class="text-red-800">Files hidden - Submission was rejected</p>
                    </div>
                @elseif($hasValidFiles)
                    <div class="space-y-2">
                        @foreach($submission->files as $file)
                            @if(is_array($file) && !empty($file) && isset($file['path']) && isset($file['name']))
                                @php
                                    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isPdf = $fileExtension === 'pdf';
                                    $isText = in_array($fileExtension, ['txt', 'md', 'json', 'xml', 'csv', 'log', 'html', 'css', 'js', 'php', 'py', 'java', 'c', 'cpp', 'h']);
                                @endphp
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <svg class="w-6 h-6 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-medium text-gray-900">{{ $file['name'] }}</p>
                                                @if(isset($file['version']))
                                                    @if($file['version'] === 'original')
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Original</span>
                                                    @elseif($file['version'] === 'revision')
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Revised</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : '' }}
                                                @if(isset($file['uploaded_at']))
                                                    • Uploaded: {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Show Download Button ONLY when accepted, otherwise Preview -->
                                    @if($submission->status === 'accepted')
                                        <!-- Accepted: Show Download Button -->
                                        <a href="{{ asset('storage/' . $file['path']) }}" download="{{ $file['name'] }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                                            ⬇️ Download {{ $file['name'] }}
                                        </a>
                                        <p class="text-xs text-green-600 mt-2">✓ Download enabled - Submission accepted</p>
                                    @else
                                        <!-- Pending/Revision: Show Preview Only -->
                                        @if($isImage)
                                            <button onclick="document.getElementById('image-preview-{{ $loop->index }}').classList.toggle('hidden')" class="mb-2 bg-[var(--ig-ink)] hover:bg-[var(--ig-accent)] text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                                Preview
                                            </button>
                                            <div id="image-preview-{{ $loop->index }}" class="hidden select-none" style="user-select: none; -webkit-user-select: none;" oncontextmenu="return false;">
                                                <img src="{{ asset('storage/' . $file['path']) }}" alt="{{ $file['name'] }}" class="max-w-full h-auto rounded border border-gray-300" style="pointer-events: none;">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">🔒 Preview only - Right-click disabled</p>
                                        @elseif($isPdf)
                                            <button onclick="document.getElementById('pdf-preview-{{ $loop->index }}').classList.toggle('hidden')" class="mb-2 bg-[var(--ig-ink)] hover:bg-[var(--ig-accent)] text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                               Preview
                                            </button>
                                            <div id="pdf-preview-{{ $loop->index }}" class="hidden bg-white rounded border border-gray-300 overflow-auto" style="max-height: 500px;">
                                                <iframe src="{{ asset('storage/' . $file['path']) }}#toolbar=0&navpanes=0&scrollbar=1" class="w-full rounded" style="height: 500px; border: none;" oncontextmenu="return false;"></iframe>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">🔒 Preview only - Scroll to view all pages</p>
                                        @elseif($isText)
                                            <button onclick="document.getElementById('text-preview-{{ $loop->index }}').classList.toggle('hidden')" class="mb-2 bg-[var(--ig-ink)] hover:bg-[var(--ig-accent)] text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                               Preview
                                            </button>
                                            <div id="text-preview-{{ $loop->index }}" class="hidden bg-gray-900 text-green-400 p-4 rounded border border-gray-300 overflow-auto select-none" style="max-height: 500px; user-select: none; -webkit-user-select: none; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.5;" oncontextmenu="return false;" oncopy="return false;">
                                                @php
                                                    $filePath = storage_path('app/public/' . $file['path']);
                                                    if (file_exists($filePath)) {
                                                        $content = file_get_contents($filePath);
                                                        // Limit to first 10000 characters for preview
                                                        if (strlen($content) > 10000) {
                                                            $content = substr($content, 0, 10000) . "\n\n... (Content truncated for preview)";
                                                        }
                                                        echo htmlspecialchars($content);
                                                    } else {
                                                        echo "File not found";
                                                    }
                                                @endphp
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">🔒 Preview only - Copy disabled</p>
                                        @else
                                            <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                                                <p class="text-sm text-yellow-800">📄 {{ strtoupper($fileExtension) }} file - Preview not available</p>
                                                <p class="text-xs text-yellow-600 mt-1">File type: {{ $file['type'] ?? 'Unknown' }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if($submission->status !== 'accepted')
                        <div class="mt-4 p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                            <p class="text-sm text-blue-800">⚠️ <strong>Security Notice:</strong> Files are preview-only to protect student work. Download will be enabled after acceptance.</p>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-green-50 rounded border-l-4 border-green-400">
                            <p class="text-sm text-green-800">✓ <strong>Download Enabled:</strong> Submission accepted - You can now download all files.</p>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 text-sm italic">No files attached to this submission.</p>
                @endif
            </div>

            @if($submission->status === 'pending' || $submission->status === 'revision_requested')
                <div class="flex gap-4">
                    <form method="POST" action="{{ route('startup.submissions.accept', $submission->id) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">✓ Accept</button>
                    </form>

                    <button onclick="document.getElementById('revisionForm').classList.toggle('hidden')" class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700">🔄 Request Revision</button>

                    <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">✗ Reject</button>
                </div>
            @elseif($submission->status === 'accepted')
                <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                    <p class="text-green-800 font-semibold">✓ Submission Accepted</p>
                    <p class="text-green-700 text-sm mt-1">You can now download the files below.</p>
                </div>
            @elseif($submission->status === 'rejected')
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                    <p class="text-red-800 font-semibold">✗ Submission Rejected</p>
                </div>
            @endif

            @if($submission->status === 'accepted' && !$rating)
                <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">⭐ Rate Student Performance</h3>
                    <form method="POST" action="{{ route('startup.submissions.rate', $submission->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating (1-5 stars)</label>
                            <select name="rating" required class="border-gray-300 rounded-lg">
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Below Average</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                            <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-lg" placeholder="Share your feedback about the student's work..."></textarea>
                        </div>
                        <button class="bg-[var(--ig-ink)] hover:bg-[var(--ig-accent)] text-white px-6 py-3 rounded-lg font-semibold transition hover:shadow-lg">Submit Rating</button>
                    </form>
                </div>
            @elseif($rating)
            	<div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">✓ Rating Submitted</h3>
                    <p class="text-gray-600">Rating: <span class="font-bold text-[var(--ig-accent)]">{{ $rating->rating }}/5 ⭐</span></p>
                    @if($rating->comment)
                        <p class="text-gray-600 mt-2">Comment: "{{ $rating->comment }}"</p>
                    @endif
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════            @if($submission->status === 'accepted' && $taskSkills->isNotEmpty())
                <div class="mt-8 bg-gradient-to-br from-[var(--ig-accent-soft)] via-[var(--ig-bg-2)] to-[var(--ig-bg)] rounded-2xl border border-[var(--ig-line-2)] p-8 shadow-lg relative overflow-hidden">
                    {{-- Decorative background glow --}}
                    <div class="absolute -top-20 -right-20 w-60 h-60 bg-[var(--ig-accent)]/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-[var(--ig-lime)]/10 rounded-full blur-3xl"></div>
 
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-[var(--ig-accent)] to-[#E03E0B] rounded-2xl flex items-center justify-center shadow-lg shadow-[var(--ig-accent)]/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Verify Skills Demonstrated</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Select the skills this student demonstrated and rate their proficiency.</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-[var(--ig-accent)] bg-[var(--ig-accent-soft)] border border-[var(--ig-accent-soft)] px-3 py-1 rounded-full uppercase tracking-wider">
                                Work-Based Verification
                            </span>
                        </div>


                        @if($existingVerifications->isNotEmpty())
                            {{-- Already verified state --}}
                            <div class="bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center space-x-2 mb-4">
                                    <span class="text-lg">✅</span>
                                    <h4 class="text-sm font-bold text-emerald-800">Skills Already Verified for this Task</h4>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($existingVerifications as $skillId => $verification)
                                        @php $skillName = $taskSkills->firstWhere('id', $skillId)?->name ?? 'Unknown'; @endphp
                                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200/60 rounded-xl px-4 py-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                                <span class="font-bold text-sm text-gray-900">{{ $skillName }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= ($verification->rating ?? 0) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                                <span class="text-xs font-bold text-gray-600 ml-1">{{ $verification->rating ?? '—' }}/5</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($existingVerifications->first()?->notes)
                                    <div class="mt-3 bg-white border border-gray-100 rounded-xl p-3">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Notes</p>
                                        <p class="text-xs text-gray-600">{{ $existingVerifications->first()->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Allow re-verification --}}
                            <button onclick="document.getElementById('reverify-form').classList.toggle('hidden')" class="mt-4 text-xs font-bold text-[var(--ig-accent)] hover:text-[#E03E0B] transition flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Update Skill Ratings</span>
                            </button>
                        @endif

                        {{-- Verification Form --}}
                        <form method="POST" action="{{ route('startup.submissions.verify-skills', $submission->id) }}" 
                              id="{{ $existingVerifications->isNotEmpty() ? 'reverify-form' : 'verify-form' }}"
                              class="{{ $existingVerifications->isNotEmpty() ? 'hidden mt-4' : '' }}">
                            @csrf
                            <div class="space-y-4">
                                @foreach($taskSkills as $index => $skill)
                                    @php $existingV = $existingVerifications->get($skill->id); @endphp
                                    <div class="skill-verification-card bg-white/90 backdrop-blur-sm border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-[var(--ig-accent)]/60 transition-all duration-300 group" 
                                         id="skill-card-{{ $skill->id }}" data-skill-id="{{ $skill->id }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-3">
                                                <label class="relative flex items-center cursor-pointer">
                                                    <input type="checkbox" 
                                                           class="skill-checkbox w-5 h-5 text-[var(--ig-accent)] bg-gray-50 border-2 border-gray-300 rounded-lg focus:ring-[var(--ig-accent)] focus:ring-2 transition"
                                                           data-skill-index="{{ $index }}"
                                                           {{ $existingV ? 'checked' : '' }}
                                                           onchange="toggleSkillFields({{ $skill->id }}, this.checked, {{ $index }})">
                                                </label>
                                                <div>
                                                    <span class="font-bold text-gray-900 text-sm group-hover:text-[var(--ig-accent)] transition-colors">{{ $skill->name }}</span>
                                                    <p class="text-[10px] text-gray-400 mt-0.5">Click to verify this skill was demonstrated</p>
                                                </div>
                                            </div>
                                            <div class="skill-badge hidden items-center space-x-1 bg-[var(--ig-accent-soft)] border border-[var(--ig-accent-soft)] px-2.5 py-1 rounded-full" id="badge-{{ $skill->id }}">
                                                <svg class="w-3 h-3 text-[var(--ig-accent)]" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-[10px] font-bold text-[var(--ig-accent)] uppercase tracking-wider">Verified</span>
                                            </div>
                                        </div>

                                        {{-- Rating & Notes (hidden until checked) --}}
                                        <div class="skill-details mt-4 pt-4 border-t border-gray-100 {{ $existingV ? '' : 'hidden' }}" id="details-{{ $skill->id }}">
                                            <input type="hidden" name="skills[{{ $index }}][skill_id]" value="{{ $skill->id }}" {{ $existingV ? '' : 'disabled' }}>

                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Proficiency Rating</label>
                                                    <div class="flex items-center space-x-1" id="stars-{{ $skill->id }}">
                                                        @for($star = 1; $star <= 5; $star++)
                                                            <button type="button" 
                                                                    onclick="setRating({{ $skill->id }}, {{ $star }}, {{ $index }})"
                                                                    class="star-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 {{ ($existingV && $existingV->rating >= $star) ? 'bg-amber-100 text-amber-500 border border-amber-200' : 'bg-gray-50 text-gray-300 border border-gray-200 hover:bg-amber-50 hover:text-amber-400' }}">
                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            </button>
                                                        @endfor
                                                        <span class="text-xs font-bold text-gray-500 ml-2" id="rating-label-{{ $skill->id }}">
                                                            {{ $existingV ? $existingV->rating . '/5' : 'Select' }}
                                                        </span>
                                                    </div>
                                                    <input type="hidden" name="skills[{{ $index }}][rating]" id="rating-input-{{ $skill->id }}" value="{{ $existingV?->rating ?? '' }}" {{ $existingV ? '' : 'disabled' }}>
                                                </div>

                                                <div class="flex-1 max-w-xs">
                                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Notes (Optional)</label>
                                                    <input type="text" name="skills[{{ $index }}][notes]" 
                                                           value="{{ $existingV?->notes ?? '' }}"
                                                           placeholder="e.g. Strong API design skills"
                                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent transition"
                                                           {{ $existingV ? '' : 'disabled' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <p class="text-[10px] text-gray-400">
                                    <span class="font-bold text-gray-500" id="selected-count">{{ $existingVerifications->count() }}</span> skill(s) selected for verification
                                </p>
                                <button type="submit" 
                                        class="bg-[var(--ig-ink)] hover:bg-[var(--ig-accent)] text-white font-extrabold py-3 px-8 rounded-xl text-sm transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-[var(--ig-accent)]/20 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="verify-submit-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>{{ $existingVerifications->isNotEmpty() ? 'Update Verifications' : 'Verify Selected Skills' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div id="revisionForm" class="hidden mt-6">
                <form method="POST" action="{{ route('startup.submissions.revision', $submission->id) }}">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-2">Revision Feedback</label>
                    <textarea name="feedback" rows="4" required class="w-full border-gray-300 rounded-lg mb-2"></textarea>
                    <button class="bg-yellow-600 text-white px-4 py-2 rounded-lg">Submit Revision Request</button>
                </form>
            </div>

            <div id="rejectForm" class="hidden mt-6">
                <form method="POST" action="{{ route('startup.submissions.reject', $submission->id) }}">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="feedback" rows="4" required class="w-full border-gray-300 rounded-lg mb-2"></textarea>
                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg">Confirm Rejection</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSkillFields(skillId, checked, index) {
            const details = document.getElementById('details-' + skillId);
            const badge = document.getElementById('badge-' + skillId);
            if (details) {
                const inputs = details.querySelectorAll('input');
                if (checked) {
                    details.classList.remove('hidden');
                    if (badge) {
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    }
                    inputs.forEach(input => input.disabled = false);
                } else {
                    details.classList.add('hidden');
                    if (badge) {
                        badge.classList.add('hidden');
                        badge.classList.remove('flex');
                    }
                    inputs.forEach(input => input.disabled = true);
                }
            }
            updateSelectedCount();
        }

        function setRating(skillId, rating, index) {
            const starsContainer = document.getElementById('stars-' + skillId);
            const label = document.getElementById('rating-label-' + skillId);
            const ratingInput = document.getElementById('rating-input-' + skillId);
            
            if (ratingInput) ratingInput.value = rating;
            if (label) label.textContent = rating + '/5';
            
            if (starsContainer) {
                const buttons = starsContainer.querySelectorAll('.star-btn');
                buttons.forEach((btn, idx) => {
                    if (idx < rating) {
                        btn.className = 'star-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 bg-amber-100 text-amber-500 border border-amber-200';
                    } else {
                        btn.className = 'star-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110 bg-gray-50 text-gray-300 border border-gray-200 hover:bg-amber-50 hover:text-amber-400';
                    }
                });
            }
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.skill-checkbox:checked').length;
            const countLabel = document.getElementById('selected-count');
            if (countLabel) {
                countLabel.textContent = checkedCount;
            }
        }

        // Auto-hide success and error messages after 4 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            
            if (successMsg) {
                setTimeout(() => {
                    successMsg.style.opacity = '0';
                    setTimeout(() => successMsg.remove(), 500);
                }, 4000);
            }
            
            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.style.opacity = '0';
                    setTimeout(() => errorMsg.remove(), 500);
                }, 4000);
            }
            
            updateSelectedCount();
        });
    </script>
</x-app-layout>
