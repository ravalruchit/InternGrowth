<div class="space-y-6">
    <!-- Header with AI Ranking Toggle -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-150">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 font-poppins">Applications & Submissions ({{ $applications->count() }})</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and rank candidates in your hiring funnel</p>
        </div>
        @if($applications->count() > 0)
            <div class="flex items-center space-x-3 bg-[var(--ig-accent-soft)] border border-[var(--ig-accent)]/20 px-4 py-2.5 rounded-xl self-start sm:self-auto shadow-sm">
                <span class="text-xs font-bold text-[var(--ig-accent)] tracking-wider uppercase font-poppins flex items-center gap-1.5">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--ig-accent)] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[var(--ig-accent)]"></span>
                    </span>
                    AI Candidate Ranking
                </span>
                <button type="button" 
                        id="ai-ranking-toggle" 
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" 
                        role="switch" 
                        aria-checked="false" 
                        onclick="toggleAIRanking()">
                    <span id="ai-ranking-toggle-bg" class="pointer-events-none absolute inset-0 rounded-full bg-gray-300 transition-colors duration-200 ease-in-out"></span>
                    <span id="ai-ranking-toggle-handle" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0"></span>
                </button>
            </div>
        @endif
    </div>

    @if($applications->count() === 0)
        <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-500 mt-4 font-semibold">No applications yet. Students will see your task and can apply.</p>
        </div>
    @else
        <!-- Applications Container -->
        <div id="applications-container" class="space-y-6">
            @foreach($applications as $application)
                @php
                    $student = $application->student;
                    $iprs = $student->reputationScore?->overall_score ?? 50.00;
                    $reliability = $student->reliability_score ?? 0;
                    $matchScore = $application->match_score ?? 0;
                    $rankingDetails = $application->ranking_details ?? [];
                @endphp
                <div class="application-card border border-gray-200 rounded-xl p-6 transition-all duration-350 shadow-sm bg-white hover:shadow-md" 
                     data-match-score="{{ $matchScore }}" 
                     data-id="{{ $application->id }}">
                    
                    <!-- Top Candidate Header (AI-only) -->
                    <div class="top-candidate-header hidden mb-4 bg-gradient-to-r from-amber-500 to-yellow-600 text-white px-4 py-2 rounded-lg text-xs font-bold tracking-wider flex items-center justify-between shadow-sm">
                        <span class="flex items-center gap-1.5">🥇 AI RANK #1 MATCH (TOP RECOMMENDED CANDIDATE)</span>
                        <span class="bg-white text-amber-700 px-2.5 py-0.5 rounded-full font-black text-[10px]">{{ $matchScore }}% Match</span>
                    </div>

                    <!-- Main Candidate Row -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h3 class="font-bold text-lg text-gray-850 font-poppins">{{ $student->user->name }}</h3>
                                
                                <!-- Match Score Badge (AI-only) -->
                                <span class="ai-info hidden bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] border border-[var(--ig-accent)]/20 px-2.5 py-0.5 rounded-full text-xs font-extrabold font-poppins shadow-sm">
                                    Match Score: {{ $matchScore }}%
                                </span>
                                
                                <!-- Recommended Candidate Badge (AI-only) -->
                                @if($matchScore >= 85)
                                    <span class="ai-info hidden bg-emerald-100 text-emerald-800 border border-emerald-200 px-3 py-0.5 rounded-full text-xs font-extrabold font-poppins flex items-center gap-1 shadow-sm">
                                        🥇 Recommended Candidate
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs font-medium text-gray-455 mt-0.5">{{ $student->user->email }}</p>

                            <!-- Reputation/Indicators Row -->
                            <div class="flex flex-wrap items-center gap-3 mt-3">
                                <span class="inline-flex items-center gap-1 bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] text-xs font-bold px-3 py-1 rounded-full border border-[var(--ig-accent)]/20">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.952 2.878c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.064 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.285-3.957z"/>
                                    </svg>
                                    {{ number_format($iprs, 1) }} IPRS
                                </span>
                                <span class="inline-flex items-center gap-1 {{ $reliability >= 0.7 ? 'bg-green-50 text-green-700 border-green-200' : ($reliability >= 0.4 ? 'bg-yellow-50 text-yellow-700 border-yellow-250' : 'bg-gray-50 text-gray-600 border-gray-200') }} text-xs font-bold px-3 py-1 rounded-full border">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    {{ number_format($reliability * 100, 0) }}% reliability
                                </span>
                                @if($student->is_verified)
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-150">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified Talent
                                    </span>
                                @endif
                                <a href="{{ route('students.public-profile', $student->id) }}" target="_blank"
                                   class="text-xs text-[var(--ig-accent)] hover:text-[#E03E0B] underline font-semibold flex items-center gap-0.5">
                                    View Full Profile
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Status Label -->
                        <span class="px-3.5 py-1 text-xs font-bold rounded-full self-start md:self-auto font-poppins shadow-sm
                            @if($application->status === 'approved' || $application->status === 'internship_accepted' || $application->status === 'hired') 
                                bg-green-100 text-green-800 border border-green-200
                            @elseif($application->status === 'rejected') 
                                bg-red-100 text-red-800 border border-red-200
                            @elseif($application->status === 'shortlisted' || $application->status === 'interview') 
                                bg-blue-100 text-blue-800 border border-blue-250
                            @elseif($application->status === 'internship_offered')
                                bg-[var(--ig-accent-soft)] text-[var(--ig-accent)] border border-[var(--ig-accent)]/20le-200
                            @else 
                                bg-yellow-100 text-yellow-800 border border-yellow-250
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>

                    <!-- AI Explanation / "Why Recommended" (AI-only) -->
                    @if(isset($rankingDetails['explanations']) && count($rankingDetails['explanations']) > 0)
                        <div class="ai-info hidden mt-4 p-4 bg-[var(--ig-accent-soft)]/20 border border-[var(--ig-accent)]/15 rounded-xl">
                            <h4 class="text-xs font-bold text-[var(--ig-accent)] tracking-wider uppercase mb-2 font-poppins flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Why Recommended
                            </h4>
                            <ul class="space-y-1.5 text-xs text-slate-805 font-medium">
                                @foreach($rankingDetails['explanations'] as $expl)
                                    <li class="flex items-start gap-2">
                                        <span class="text-emerald-500 font-bold">✓</span>
                                        <span>{{ $expl }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- AI "Best Evidence" Section (AI-only) -->
                    @if(isset($rankingDetails['best_evidence']) && $rankingDetails['best_evidence'] !== null)
                        <div class="ai-info hidden mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5 font-poppins flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Best Evidence Project
                            </h4>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-sm font-bold text-slate-800 font-poppins">{{ $rankingDetails['best_evidence']['project_title'] }}</h5>
                                    @if(!empty($rankingDetails['best_evidence']['skills_demonstrated']))
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            @foreach($rankingDetails['best_evidence']['skills_demonstrated'] as $sk)
                                                <span class="bg-[var(--ig-accent-soft)] border border-[var(--ig-accent)]/20 text-[var(--ig-accent)] px-2 py-0.5 rounded text-[10px] font-bold font-poppins">{{ $sk }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @if($rankingDetails['best_evidence']['rating_received'])
                                    <div class="text-right whitespace-nowrap bg-amber-50 border border-amber-100 px-2.5 py-1.5 rounded-lg shadow-sm">
                                        <span class="text-amber-600 font-black text-sm font-poppins">⭐ {{ $rankingDetails['best_evidence']['rating_received'] }}/5</span>
                                        <p class="text-[8px] text-amber-550 font-bold uppercase tracking-wider mt-0.5">Startup Rated</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- AI Insights Drawer (AI-only) -->
                    @if(isset($rankingDetails['insights']))
                        <div class="ai-info hidden mt-4 border border-[var(--ig-accent)]/20 rounded-xl overflow-hidden shadow-sm">
                            <button type="button" 
                                    class="w-full text-left px-4 py-3 bg-[var(--ig-accent-soft)] hover:bg-[var(--ig-accent-soft)]/80 transition flex items-center justify-between text-xs font-bold text-[var(--ig-accent)] uppercase tracking-wider font-poppins" 
                                    onclick="toggleInsightsDrawer('insights-drawer-{{ $application->id }}')">
                                <span class="flex items-center gap-1.5">
                                    🔍 Candidate Insights (Strengths, Risks & Interview Questions)
                                </span>
                                <span class="arrow transition-transform duration-200 select-none">▼</span>
                            </button>
                            <div id="insights-drawer-{{ $application->id }}" class="hidden p-5 bg-white border-t border-[var(--ig-accent)]/20 space-y-4">
                                
                                <!-- Strengths -->
                                <div>
                                    <h5 class="text-xs font-bold text-green-700 tracking-wide uppercase mb-1.5 font-poppins flex items-center gap-1">💪 Strengths</h5>
                                    <ul class="list-disc pl-5 text-xs text-gray-700 space-y-1 font-medium">
                                        @foreach($rankingDetails['insights']['strengths'] as $st)
                                            <li>{{ $st }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Risks -->
                                @if(count($rankingDetails['insights']['risks']) > 0)
                                    <div>
                                        <h5 class="text-xs font-bold text-red-700 tracking-wide uppercase mb-1.5 font-poppins flex items-center gap-1">⚠️ Potential Risks</h5>
                                        <ul class="list-disc pl-5 text-xs text-gray-750 space-y-1 font-medium">
                                            @foreach($rankingDetails['insights']['risks'] as $rk)
                                                <li>{{ $rk }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Interview Questions -->
                                @if(count($rankingDetails['insights']['interview_questions']) > 0)
                                    <div class="pt-3 border-t border-gray-150">
                                        <h5 class="text-xs font-bold text-[var(--ig-accent)] tracking-wide uppercase mb-1.5 font-poppins flex items-center gap-1">💬 Suggested Interview Questions</h5>
                                        <ul class="list-decimal pl-5 text-xs text-gray-750 space-y-1.5">
                                            @foreach($rankingDetails['insights']['interview_questions'] as $qs)
                                                <li class="italic font-medium text-gray-800">"{{ $qs }}"</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Cover Letter (Standard Section) -->
                    @if($application->cover_letter)
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-150">
                            <p class="text-sm text-gray-700 font-medium font-poppins mb-1 text-xs text-gray-400 uppercase tracking-wider">Cover Letter</p>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">"{{ $application->cover_letter }}"</p>
                        </div>
                    @endif

                    <!-- ATS Funnel Timeline & Action Center -->
                    <div class="bg-slate-50 border border-gray-200 rounded-xl p-5 mt-4 shadow-sm space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider font-poppins">ATS Funnel Pipeline</h4>
                        
                        <!-- Visual Stepper -->
                        <div class="flex items-center justify-between overflow-x-auto py-2">
                            @php
                                $currentStatus = $application->status;
                                $outcome = $application->startup_hiring_outcome;
                                
                                $stepActive = 1;
                                if ($currentStatus === 'applied') {
                                    $stepActive = 1;
                                } elseif ($currentStatus === 'approved' && (!$application->submission || $application->submission->status !== 'accepted')) {
                                    $stepActive = 2;
                                } elseif ($application->submission && $application->submission->status === 'accepted' && !$outcome) {
                                    $stepActive = 3;
                                } elseif (in_array($outcome, ['interview_scheduled', 'interview_passed', 'interview_failed'])) {
                                    $stepActive = 4;
                                } elseif (in_array($outcome, ['hired_intern', 'hired_job']) || in_array($currentStatus, ['internship_offered', 'internship_accepted', 'hired'])) {
                                    $stepActive = 5;
                                }
                            @endphp
                            
                            <!-- Step 1: Applied -->
                            <div class="flex items-center flex-1 last:flex-none">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $stepActive >= 1 ? 'bg-[var(--ig-accent)] text-white' : 'bg-gray-205 text-gray-500' }}">
                                        @if($stepActive > 1) ✓ @else 1 @endif
                                    </div>
                                    <span class="text-[9px] font-bold mt-1 text-gray-600">Applied</span>
                                </div>
                                <div class="h-0.5 flex-1 mx-2 {{ $stepActive > 1 ? 'bg-[var(--ig-accent)]' : 'bg-gray-200' }}"></div>
                            </div>

                            <!-- Step 2: Task Assigned -->
                            <div class="flex items-center flex-1 last:flex-none">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $stepActive >= 2 ? 'bg-[var(--ig-accent)] text-white' : 'bg-gray-205 text-gray-500' }}">
                                        @if($stepActive > 2) ✓ @else 2 @endif
                                    </div>
                                    <span class="text-[9px] font-bold mt-1 text-gray-600">Task Started</span>
                                </div>
                                <div class="h-0.5 flex-1 mx-2 {{ $stepActive > 2 ? 'bg-[var(--ig-accent)]' : 'bg-gray-200' }}"></div>
                            </div>

                            <!-- Step 3: Task Completed -->
                            <div class="flex items-center flex-1 last:flex-none">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $stepActive >= 3 ? 'bg-[var(--ig-accent)] text-white' : 'bg-gray-205 text-gray-500' }}">
                                        @if($stepActive > 3) ✓ @else 3 @endif
                                    </div>
                                    <span class="text-[9px] font-bold mt-1 text-gray-600">Task Completed</span>
                                </div>
                                <div class="h-0.5 flex-1 mx-2 {{ $stepActive > 3 ? 'bg-[var(--ig-accent)]' : 'bg-gray-200' }}"></div>
                            </div>

                            <!-- Step 4: Interview -->
                            <div class="flex items-center flex-1 last:flex-none">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $stepActive >= 4 ? 'bg-[var(--ig-accent)] text-white' : 'bg-gray-205 text-gray-500' }}">
                                        @if($stepActive > 4) ✓ @else 4 @endif
                                    </div>
                                    <span class="text-[9px] font-bold mt-1 text-gray-600">Interview</span>
                                </div>
                                <div class="h-0.5 flex-1 mx-2 {{ $stepActive > 4 ? 'bg-[var(--ig-accent)]' : 'bg-gray-200' }}"></div>
                            </div>

                            <!-- Step 5: Hired -->
                            <div class="flex items-center last:flex-none">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $stepActive >= 5 ? 'bg-emerald-600 text-white' : 'bg-gray-205 text-gray-500' }}">
                                        🎉
                                    </div>
                                    <span class="text-[9px] font-bold mt-1 text-gray-600">Hired</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons based on status -->
                        <div class="pt-2 border-t border-gray-200">
                            @if($currentStatus === 'applied')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('startup.applications.approve', $application->id) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold py-2 rounded-xl text-xs transition shadow-sm">
                                            ✓ Approve Candidate to Start Task
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('startup.applications.reject', $application->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded-xl text-xs transition border border-gray-250">
                                            Reject Candidate
                                        </button>
                                    </form>
                                </div>
                            @elseif($currentStatus === 'rejected')
                                <div class="text-xs text-red-700 bg-red-50 border border-red-200 rounded-xl p-3 font-semibold">
                                    ✗ Application Rejected.
                                </div>
                            @elseif($currentStatus === 'approved' && (!$application->submission || $application->submission->status !== 'accepted'))
                                <div class="text-xs text-amber-800 bg-amber-50 border border-amber-250 rounded-xl p-3 font-semibold flex items-center gap-2">
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-600"></span>
                                    </span>
                                    <span>Task Assigned. Candidate is currently working on the assignment.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Submission Section (Standard Section) -->
                    @if($application->submission)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-900 font-poppins">Work Submission</h4>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $application->submission->status === 'accepted' ? 'bg-green-100 text-green-800' : ($application->submission->status === 'rejected' ? 'bg-red-100 text-red-800' : ($application->submission->status === 'revision_requested' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $application->submission->status)) }}
                                </span>
                            </div>
                            
                            <div class="p-4 bg-gray-50 border border-gray-150 rounded-xl mb-3">
                                <div class="mb-3">
                                    <h5 class="font-bold text-gray-900 text-xs uppercase tracking-wider mb-2 font-poppins">Submission Description</h5>
                                    <p class="text-gray-700 whitespace-pre-wrap text-sm leading-relaxed">{{ $application->submission->content }}</p>
                                </div>
                                
                                @if($application->submission->files && is_array($application->submission->files) && count($application->submission->files) > 0)
                                    @php
                                        $hasValidFiles = false;
                                        foreach ($application->submission->files as $file) {
                                            if (is_array($file) && !empty($file) && isset($file['path']) && isset($file['name'])) {
                                                $hasValidFiles = true;
                                                break;
                                            }
                                        }
                                    @endphp
                                    
                                    @if($hasValidFiles)
                                        <div class="mt-4 pt-4 border-t border-gray-250">
                                            <h5 class="font-bold text-gray-900 text-xs uppercase tracking-wider mb-2 font-poppins">Attached Files</h5>
                                            <div class="space-y-2">
                                                @foreach($application->submission->files as $file)
                                                    @if(is_array($file) && !empty($file) && isset($file['path']) && isset($file['name']))
                                                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                                            <div class="flex items-center space-x-3 flex-1">
                                                                <svg class="w-5 h-5 text-[var(--ig-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                </svg>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center space-x-2">
                                                                        <p class="text-sm font-semibold text-gray-900">{{ $file['name'] }}</p>
                                                                        @if(isset($file['version']))
                                                                            @if($file['version'] === 'original')
                                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Original</span>
                                                                            @elseif($file['version'] === 'revision')
                                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-850">Revised</span>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                                        {{ isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : '' }}
                                                                        @if(isset($file['uploaded_at']))
                                                                            • Uploaded: {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y H:i') }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <a href="{{ route('startup.submissions.review', $application->submission->id) }}" class="text-[var(--ig-accent)] hover:text-[#E03E0B] text-xs font-semibold ml-3 px-3 py-1.5 bg-[var(--ig-accent-soft)] rounded-lg border border-[var(--ig-accent)]/20 shadow-sm transition">
                                                                👁️ View Preview
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            
                            @if($application->submission->feedback)
                                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl mb-3 shadow-sm">
                                    <p class="text-sm text-yellow-800"><strong>Your Feedback:</strong> {{ $application->submission->feedback }}</p>
                                </div>
                            @endif
                            
                            <!-- Submission Actions -->
                            @if(in_array($application->submission->status, ['pending', 'submitted', 'revision_requested']))
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('startup.submissions.accept', $application->submission->id) }}">
                                        @csrf
                                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-semibold shadow-sm transition">✓ Accept Work</button>
                                    </form>
                                    
                                    <button onclick="document.getElementById('revision-form-{{ $application->submission->id }}').classList.toggle('hidden')" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-semibold shadow-sm transition">
                                        ↻ Request Revision
                                    </button>
                                    <button onclick="document.getElementById('reject-form-{{ $application->submission->id }}').classList.toggle('hidden')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-semibold shadow-sm transition">
                                        ✗ Reject Work
                                    </button>
                                </div>
                                
                                <!-- Revision Request Form -->
                                <div id="revision-form-{{ $application->submission->id }}" class="hidden mt-3 p-4 bg-yellow-50/50 border border-yellow-200 rounded-xl">
                                    <form method="POST" action="{{ route('startup.submissions.revision', $application->submission->id) }}">
                                        @csrf
                                        <textarea name="feedback" rows="3" required class="w-full border-gray-300 rounded-lg mb-3 shadow-sm" placeholder="Explain what needs to be revised..."></textarea>
                                        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-semibold shadow-sm transition">Send Revision Request</button>
                                    </form>
                                </div>
                                
                                <!-- Reject Form -->
                                <div id="reject-form-{{ $application->submission->id }}" class="hidden mt-3 p-4 bg-red-50/50 border border-red-200 rounded-xl">
                                    <form method="POST" action="{{ route('startup.submissions.reject', $application->submission->id) }}">
                                        @csrf
                                        <textarea name="feedback" rows="3" required class="w-full border-gray-300 rounded-lg mb-3 shadow-sm" placeholder="Explain why the work is rejected..."></textarea>
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-semibold shadow-sm transition">Confirm Rejection</button>
                                    </form>
                                </div>
                            @elseif($application->submission->status === 'accepted')
                                <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-xl shadow-sm space-y-4">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-bold text-green-800 font-poppins">✓ Task Completed Successfully</p>
                                            <p class="text-xs text-green-700 mt-1">Student's IPRS reputation score has been updated.</p>
                                        </div>
                                    </div>

                                    <!-- Decision Card -->
                                    <div class="border-t border-green-200 pt-4 space-y-4">
                                        @if(!$application->startup_hiring_outcome)
                                            <h4 class="text-xs font-bold text-[var(--ig-accent)] uppercase tracking-wider font-poppins">Choose Next Action</h4>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- Option 1: Close Task -->
                                                <div class="bg-white border border-gray-250 p-4 rounded-xl flex flex-col justify-between space-y-3 shadow-sm">
                                                    <div>
                                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Option 1</span>
                                                        <h5 class="font-extrabold text-xs text-gray-900">Close Task</h5>
                                                        <p class="text-[11px] text-gray-500 leading-normal mt-1">For startups that only wanted project work. Marks candidate relationship as completed.</p>
                                                    </div>
                                                    <form method="POST" action="{{ route('startup.applications.close-task', $application->id) }}">
                                                        @csrf
                                                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-extrabold py-2 px-3 rounded-lg text-[11px] transition shadow-sm">
                                                            Approve Work & Close Task
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Option 2: Explore Hiring -->
                                                <div class="bg-white border border-[var(--ig-accent)]/25 p-4 rounded-xl flex flex-col justify-between space-y-3 shadow-sm">
                                                    <div>
                                                        <span class="text-[10px] font-bold text-[var(--ig-accent)] uppercase tracking-wider block mb-1 font-poppins">Option 2</span>
                                                        <h5 class="font-extrabold text-xs text-slate-900 font-poppins">Explore Hiring</h5>
                                                        <p class="text-[11px] text-gray-500 leading-normal mt-1">For startups that liked the student and want to recruit them.</p>
                                                        <div class="space-y-1.5 mt-3">
                                                            <div class="flex gap-1.5">
                                                                @php
                                                                    $conversation = \App\Models\Conversation::where('student_profile_id', $application->student_profile_id)
                                                                        ->where('startup_profile_id', auth()->user()->startupProfile->id)
                                                                        ->where('task_id', $application->task_id)
                                                                        ->first();
                                                                @endphp
                                                                <button type="button" onclick="openOfferModalFromIndex('{{ $application->student->id }}', '{{ $application->id }}', 'internship', '{{ $application->task_id }}')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-2.5 rounded-lg text-[11px] text-center transition">
                                                                    💼 Intern Offer
                                                                </button>
                                                                <button type="button" onclick="openJobPathModal('{{ addslashes($application->student->user->name) }}', '{{ $conversation ? $conversation->id : '' }}', '{{ $application->student->id }}', '{{ $application->id }}', '{{ $application->task_id }}', '{{ $application->student_profile_id }}', '{{ auth()->user()->startupProfile->id }}')" class="flex-1 bg-[var(--ig-ink)] hover:opacity-90 text-white font-bold py-1.5 px-2.5 rounded-lg text-[11px] text-center transition">
                                                                    🚀 Job Offer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($application->startup_hiring_outcome === 'task_only')
                                            <div class="bg-white border border-gray-200 rounded-xl p-3.5 text-xs text-gray-700 font-semibold flex items-center gap-1.5 shadow-sm">
                                                <span>🤝</span>
                                                <span>Relationship Resolved. Task completed & closed (No placement).</span>
                                            </div>
                                        @elseif($application->startup_hiring_outcome === 'task_completed_rejected')
                                            <div class="bg-white border border-red-200 rounded-xl p-3.5 text-xs text-red-850 font-semibold flex items-center gap-1.5 shadow-sm">
                                                <span>❌</span>
                                                <span>Task Completed. Candidate not selected for placement hiring.</span>
                                            </div>
                                        @elseif(in_array($application->startup_hiring_outcome, ['hired_intern', 'hired_job']))
                                            <div class="bg-white border border-emerald-250 rounded-xl p-4 space-y-3.5 shadow-sm">
                                                <div class="flex items-center justify-between flex-wrap gap-2">
                                                    <div class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                                                        <span>🎉</span>
                                                        <span>Candidate hired as {{ $application->startup_hiring_outcome === 'hired_intern' ? 'Intern' : 'Full-time Employee' }}!</span>
                                                    </div>
                                                    <a href="{{ route('messages.create', [$application->student_profile_id, auth()->user()->startupProfile->id, $application->task_id]) }}" class="bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-bold py-1.5 px-3 rounded-lg text-[10px] text-center transition flex items-center gap-1 shadow-sm">
                                                        💬 Message Candidate
                                                    </a>
                                                </div>
                                                
                                                @if(!$application->hiring_success_rating)
                                                    <div class="bg-slate-50 border border-emerald-100 rounded-lg p-3 space-y-2">
                                                        <h5 class="text-xs font-bold text-gray-800 font-poppins">Rate Placement Performance (30 Days)</h5>
                                                        <p class="text-[10px] text-gray-500 leading-normal">Submit a placement rating to adjust their reliability rating.</p>
                                                        
                                                        <form method="POST" action="{{ route('startup.applications.rate-hiring-success', $application->id) }}" class="flex items-center gap-2 pt-1">
                                                            @csrf
                                                            <select name="rating" required class="bg-white border border-gray-300 rounded-lg text-xs py-1 px-2.5 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-medium">
                                                                <option value="excellent">⭐⭐⭐⭐⭐ Excellent</option>
                                                                <option value="good" selected>⭐⭐⭐⭐ Good</option>
                                                                <option value="average">⭐⭐⭐ Average</option>
                                                                <option value="poor">⭐⭐ Poor</option>
                                                                <option value="terminated">❌ Terminated</option>
                                                            </select>
                                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-1 px-3 rounded-lg text-[10px] transition shadow-sm">
                                                                Submit
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="text-[10px] text-emerald-700 italic font-semibold">
                                                        Performance rating: <strong>{{ ucfirst($application->hiring_success_rating) }}</strong>. Score updated.
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($application->startup_hiring_outcome === 'interview_scheduled')
                                            <div class="bg-white border border-blue-200 rounded-xl p-3.5 text-xs text-blue-800 font-semibold flex items-center justify-between shadow-sm">
                                                <span class="flex items-center gap-1.5">
                                                    <span>📅</span>
                                                    <span>Interview scheduled in pipeline.</span>
                                                </span>
                                                <div class="flex gap-1.5">
                                                    <button type="button" onclick="openOfferModalFromIndex('{{ $application->student->id }}', '{{ $application->id }}', 'internship', '{{ $application->task_id }}')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-2.5 rounded-lg text-[10px] transition shadow-sm">
                                                        💼 Offer Intern
                                                    </button>
                                                    <button type="button" onclick="openOfferModalFromIndex('{{ $application->student->id }}', '{{ $application->id }}', 'job', '{{ $application->task_id }}')" class="bg-[var(--ig-ink)] hover:opacity-90 text-white font-bold py-1 px-2.5 rounded-lg text-[10px] transition shadow-sm">
                                                        🚀 Offer Job
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($application->startup_hiring_outcome === 'interview_passed')
                                            <div class="bg-white border border-green-200 rounded-xl p-3.5 text-xs text-green-800 font-semibold flex items-center justify-between shadow-sm">
                                                <span class="flex items-center gap-1.5">
                                                    <span>🏆</span>
                                                    <span>Interview Passed! Extended placement offers:</span>
                                                </span>
                                                <div class="flex gap-1.5">
                                                    <button type="button" onclick="openOfferModalFromIndex('{{ $application->student->id }}', '{{ $application->id }}', 'internship', '{{ $application->task_id }}')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-2.5 rounded-lg text-[10px] transition shadow-sm">
                                                        💼 Offer Intern
                                                    </button>
                                                    <button type="button" onclick="openOfferModalFromIndex('{{ $application->student->id }}', '{{ $application->id }}', 'job', '{{ $application->task_id }}')" class="bg-[var(--ig-ink)] hover:opacity-90 text-white font-bold py-1 px-2.5 rounded-lg text-[10px] transition shadow-sm">
                                                        🚀 Offer Job
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($application->startup_hiring_outcome === 'interview_failed')
                                            <div class="bg-white border border-rose-200 rounded-xl p-3.5 text-xs text-rose-800 font-semibold flex items-center gap-1.5 shadow-sm">
                                                <span>❌</span>
                                                <span>Interview Failed. Candidate not selected for placement hiring.</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif($application->status === 'approved')
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-400 italic">Waiting for student to submit their work...</p>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        <script>
            function toggleAIRanking() {
                const toggle = document.getElementById('ai-ranking-toggle');
                const bg = document.getElementById('ai-ranking-toggle-bg');
                const handle = document.getElementById('ai-ranking-toggle-handle');
                const container = document.getElementById('applications-container');
                const cards = Array.from(container.getElementsByClassName('application-card'));
                
                const isActive = toggle.getAttribute('aria-checked') === 'true';
                const newState = !isActive;
                toggle.setAttribute('aria-checked', newState ? 'true' : 'false');
                
                if (newState) {
                    // AI Ranking Enabled
                    bg.classList.remove('bg-gray-300');
                    bg.classList.add('bg-[var(--ig-accent)]');
                    handle.classList.remove('translate-x-0');
                    handle.classList.add('translate-x-5');
                    
                    document.querySelectorAll('.ai-info').forEach(el => {
                        el.classList.remove('hidden');
                        el.style.opacity = 0;
                        setTimeout(() => {
                            el.style.transition = 'opacity 0.35s ease-in-out';
                            el.style.opacity = 1;
                        }, 50);
                    });
                    
                    // Sort cards by match score descending
                    cards.sort((a, b) => {
                        return parseInt(b.getAttribute('data-match-score')) - parseInt(a.getAttribute('data-match-score'));
                    });
                    
                    cards.forEach((card, index) => {
                        container.appendChild(card);
                        if (index === 0) {
                            card.classList.add('border-amber-400', 'bg-gradient-to-br', 'from-amber-50/40', 'to-white');
                            card.querySelector('.top-candidate-header').classList.remove('hidden');
                        } else {
                            card.classList.remove('border-amber-400', 'bg-gradient-to-br', 'from-amber-50/40', 'to-white');
                            card.querySelector('.top-candidate-header').classList.add('hidden');
                        }
                    });
                } else {
                    // Normal View
                    bg.classList.remove('bg-[var(--ig-accent)]');
                    bg.classList.add('bg-gray-300');
                    handle.classList.remove('translate-x-5');
                    handle.classList.add('translate-x-0');
                    
                    document.querySelectorAll('.ai-info').forEach(el => {
                        el.classList.add('hidden');
                        el.style.opacity = 0;
                    });
                    
                    // Sort cards by original ID ascending
                    cards.sort((a, b) => {
                        return parseInt(a.getAttribute('data-id')) - parseInt(b.getAttribute('data-id'));
                    });
                    
                    cards.forEach((card) => {
                        container.appendChild(card);
                        card.classList.remove('border-amber-400', 'bg-gradient-to-br', 'from-amber-50/40', 'to-white');
                        card.querySelector('.top-candidate-header').classList.add('hidden');
                    });
                }
            }

            function toggleInsightsDrawer(id) {
                const drawer = document.getElementById(id);
                const button = drawer.previousElementSibling;
                const arrow = button.querySelector('.arrow');
                
                drawer.classList.toggle('hidden');
                
                if (drawer.classList.contains('hidden')) {
                    arrow.style.transform = 'rotate(0deg)';
                    button.classList.remove('bg-[var(--ig-accent-soft)]');
                } else {
                    arrow.style.transform = 'rotate(180deg)';
                    button.classList.add('bg-[var(--ig-accent-soft)]');
                }
            }

            function openScheduleModalFromIndex(studentName, conversationId) {
                const modal = document.getElementById('index-schedule-modal');
                const form = document.getElementById('index-schedule-form');
                const namePlaceholder = document.getElementById('index-student-name-placeholder');
                if (modal && form) {
                    namePlaceholder.textContent = studentName;
                    form.action = `/startup/interviews/schedule/${conversationId}`;
                    modal.classList.remove('hidden');
                }
            }
            function closeScheduleModalFromIndex() {
                const modal = document.getElementById('index-schedule-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
            function updateLocationPlaceholderFromIndex(val) {
                const input = document.getElementById('index-location-input');
                if (!input) return;
                if (val === 'online') {
                    input.placeholder = 'Google Meet link or URL';
                } else if (val === 'phone') {
                    input.placeholder = 'e.g. +91 98765 43210';
                } else {
                    input.placeholder = 'e.g. Office Address Suite 4B';
                }
            }
            function openOfferModalFromIndex(studentId, applicationId, offerType, taskId) {
                const modal = document.getElementById('index-offer-modal');
                if (!modal) return;
                
                document.getElementById('index-offer-student-id').value = studentId;
                document.getElementById('index-offer-type-val').value = offerType;
                document.getElementById('index-offer-source-task-id').value = taskId;
                
                const titleEmoji = document.getElementById('index-offer-title-emoji');
                const titleText = document.getElementById('index-offer-type-title');
                const compLabel = document.getElementById('index-compensation-label');
                const compPeriod = document.getElementById('index-compensation-period');
                
                if (offerType === 'internship') {
                    titleEmoji.textContent = '💼';
                    titleText.textContent = 'Pitch Internship Offer';
                    compLabel.textContent = 'Monthly Stipend (₹)';
                    compPeriod.value = 'monthly';
                    compPeriod.style.pointerEvents = 'none';
                } else {
                    titleEmoji.textContent = '🚀';
                    titleText.textContent = 'Pitch Full-Time Job Offer';
                    compLabel.textContent = 'Compensation (₹)';
                    compPeriod.value = 'annual';
                    compPeriod.style.pointerEvents = 'auto';
                }
                
                modal.classList.remove('hidden');
            }
            function closeOfferModalFromIndex() {
                const modal = document.getElementById('index-offer-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }

            let jobModalParams = {};
            function openJobPathModal(studentName, conversationId, studentId, applicationId, taskId, studentProfileId, startupProfileId) {
                jobModalParams = { studentName, conversationId, studentId, applicationId, taskId, studentProfileId, startupProfileId };
                const modal = document.getElementById('index-job-path-modal');
                if (!modal) return;

                document.getElementById('index-job-student-name').textContent = studentName;
                
                const actionContainer = document.getElementById('index-job-interview-action-container');
                if (conversationId) {
                    actionContainer.innerHTML = `
                        <button type="button" onclick="triggerJobSchedule()" class="w-full bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-sm text-center">
                            Schedule Interview
                        </button>
                    `;
                } else {
                    const chatUrl = `/messages/start/${studentProfileId}/${startupProfileId}/${taskId}`;
                    actionContainer.innerHTML = `
                        <a href="${chatUrl}" class="w-full block bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-sm text-center">
                            💬 Start Chat to Interview
                        </a>
                    `;
                }

                document.getElementById('index-job-direct-btn').onclick = function() {
                    closeJobPathModal();
                    openOfferModalFromIndex(studentId, applicationId, 'job', taskId);
                };

                modal.classList.remove('hidden');
            }
            function closeJobPathModal() {
                const modal = document.getElementById('index-job-path-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
            function triggerJobSchedule() {
                closeJobPathModal();
                openScheduleModalFromIndex(jobModalParams.studentName, jobModalParams.conversationId);
            }
        </script>
    @endif
</div>

<!-- Job Recruitment Path Selection Modal -->
<div id="index-job-path-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
    <div class="bg-white border border-[var(--ig-accent)]/20 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
        <button onclick="closeJobPathModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
            <span>🚀 Full-Time Job Offer Path</span>
        </h3>
        <p class="text-xs text-gray-500 mb-6 font-medium">Select how you want to proceed with full-time recruitment for <span id="index-job-student-name" class="font-bold text-[var(--ig-accent)]"></span>.</p>
        
        <div class="grid grid-cols-1 gap-4">
            <!-- Path 1: Interview First -->
            <div class="border border-[var(--ig-accent)]/20 p-5 rounded-2xl bg-[var(--ig-accent-soft)]/20 flex flex-col justify-between space-y-3">
                <div>
                    <h4 class="font-bold text-sm text-[var(--ig-accent)] flex items-center gap-1.5 font-poppins">
                        <span>📅</span> Schedule Interview
                    </h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-normal">
                        Recommended to conduct an interview round to align on expectations and confirm technical fit before placing a formal job offer.
                    </p>
                </div>
                <div id="index-job-interview-action-container">
                    <!-- Rendered by JS -->
                </div>
            </div>

            <!-- Path 2: Direct Hire -->
            <div class="border border-emerald-100 p-5 rounded-2xl bg-emerald-50/30 flex flex-col justify-between space-y-3">
                <div>
                    <h4 class="font-bold text-sm text-emerald-950 flex items-center gap-1.5 font-poppins">
                        <span>🚀</span> Direct Job Offer
                    </h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-normal">
                        Skip scheduling an interview and send a direct job offer immediately with your custom CTC, start date, and agreement parameters.
                    </p>
                </div>
                <button type="button" id="index-job-direct-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2.5 px-4 rounded-xl text-xs transition shadow-sm text-center">
                    Send Direct Job Offer
                </button>
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeJobPathModal()" class="bg-gray-100 text-gray-700 font-bold px-6 py-2.5 rounded-xl transition text-xs">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div id="index-schedule-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
    <div class="bg-white border border-[var(--ig-accent)]/25 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900">
        <button onclick="closeScheduleModalFromIndex()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
            <span>📅 Schedule Interview</span>
        </h3>
        <p class="text-xs text-gray-500 mb-6">Send an interview invitation to <span id="index-student-name-placeholder" class="font-bold text-[var(--ig-accent)]"></span>.</p>
        
        <form id="index-schedule-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Interview Title</label>
                <input type="text" name="title" required placeholder="e.g. Technical Coding Round"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-255 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Duration (min)</label>
                    <select name="duration_minutes" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-255 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        <option value="15">15 Minutes</option>
                        <option value="30" selected>30 Minutes</option>
                        <option value="45">45 Minutes</option>
                        <option value="60">60 Minutes</option>
                        <option value="90">90 Minutes</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Interview Type</label>
                    <select name="type" required onchange="updateLocationPlaceholderFromIndex(this.value)"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-255 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                        <option value="online" selected>Google Meet / Zoom</option>
                        <option value="phone">Phone call</option>
                        <option value="in_person">In Person / Address</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Location / Contact</label>
                    <input type="text" name="location" id="index-location-input" required placeholder="Google Meet link or URL"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-255 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agenda & Prep Notes</label>
                <textarea name="agenda" rows="3" placeholder="Explain agenda, topics, coding workspace needed..."
                          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-255 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm"></textarea>
            </div>
            
            <div class="bg-[var(--ig-accent-soft)]/30 border border-[var(--ig-accent)]/20 rounded-xl p-3 flex items-start gap-2">
                <input type="checkbox" name="agreement" id="interview-agreement-check" required value="1" class="mt-0.5">
                <label for="interview-agreement-check" class="text-[11px] text-[var(--ig-accent)] leading-tight font-semibold">
                    I confirm that this hiring process will be completed through InternGrowth (Payment Agreement).
                </label>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[var(--ig-accent)] hover:bg-[#E03E0B] text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-sm">
                    Schedule & Send Invitation
                </button>
                <button type="button" onclick="closeScheduleModalFromIndex()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl transition text-xs">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Acquisition Offer Modal -->
<div id="index-offer-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-md transition-opacity duration-300">
    <div class="bg-white border border-emerald-100 rounded-3xl shadow-2xl p-8 max-w-lg w-full mx-4 transform scale-95 transition-transform duration-300 relative text-gray-900 overflow-y-auto max-h-[90vh]">
        <button onclick="closeOfferModalFromIndex()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-2xl font-black text-gray-900 mb-2 font-poppins flex items-center space-x-2">
            <span id="index-offer-title-emoji">💼</span>
            <span id="index-offer-type-title">Pitch Offer</span>
        </h3>
        <p class="text-xs text-gray-505 mb-6">Extend a placement proposal directly to candidate.</p>
        
        <form action="{{ route('startup.offers.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="student_profile_id" id="index-offer-student-id" value="">
            <input type="hidden" name="offer_type" id="index-offer-type-val" value="">
            <input type="hidden" name="source_task_id" id="index-offer-source-task-id" value="">

            <div>
                <label class="block text-xs font-bold text-gray-750 uppercase tracking-wider mb-2">Offer Title</label>
                <input type="text" name="title" required placeholder="e.g. Frontend Development Intern" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-750 uppercase tracking-wider mb-2">Role Description</label>
                <textarea name="description" required rows="3" placeholder="Outline job duties, expectations..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm resize-none"></textarea>
            </div>

            <input type="hidden" name="compensation_period" id="index-compensation-period" value="monthly">
            <div>
                <label id="index-compensation-label" class="block text-xs font-bold text-gray-755 uppercase tracking-wider mb-2">Stipend (₹)</label>
                <input type="number" name="compensation" required min="0" placeholder="e.g. 15000" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-750 uppercase tracking-wider mb-2">Start Date</label>
                    <input type="date" name="start_date" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-750 uppercase tracking-wider mb-2">End Date (Optional)</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-755 uppercase tracking-wider mb-2">Perks & Contract Terms</label>
                <textarea name="contract_terms" rows="2" placeholder="e.g. Certificate, Flexible Hours, Work From Home" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-250 rounded-xl focus:ring-2 focus:ring-[var(--ig-accent)] focus:border-transparent text-sm resize-none"></textarea>
            </div>

            <div class="bg-[var(--ig-accent-soft)]/30 border border-[var(--ig-accent)]/20 rounded-xl p-3 flex items-start gap-2">
                <input type="checkbox" name="agreement" id="offer-agreement-check" required value="1" class="mt-0.5">
                <label for="offer-agreement-check" class="text-[11px] text-[var(--ig-accent)] leading-tight font-semibold">
                    I confirm that this hiring process will be completed through InternGrowth (Payment Agreement).
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-sm">
                    Send Placement Offer
                </button>
                <button type="button" onclick="closeOfferModalFromIndex()" class="bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-xl transition text-xs">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
