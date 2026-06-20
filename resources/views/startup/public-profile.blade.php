<x-app-layout>
    <div class="ig-container py-12 ig-anim-fade-up space-y-8">
        
        <!-- Top Profile Banner Block -->
        <div class="ig-card p-6 sm:p-8 bg-gradient-to-br from-white via-[var(--ig-bg-2)] to-white relative overflow-hidden border border-[var(--ig-line-2)] shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[var(--ig-surface-ink)] flex items-center justify-center text-2xl sm:text-3xl font-black text-white uppercase shadow-lg flex-shrink-0 font-poppins">
                        {{ substr($profile->company_name, 0, 2) }}
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="ig-display text-2xl sm:text-3xl text-[var(--ig-ink)] leading-tight">{{ $profile->company_name }}</h1>
                            @if($profile->is_verified)
                                <span class="ig-chip ig-chip-lime text-[10px] font-bold">✓ Verified Startup</span>
                            @else
                                <span class="ig-chip text-[10px] font-medium">Pending Verification</span>
                            @endif
                        </div>
                        <p class="text-xs text-[var(--ig-muted)] mt-1.5 flex items-center gap-1">
                            📍 {{ $profile->company_address ?? 'Address not updated' }}
                        </p>
                    </div>
                </div>
                
                @if($profile->website)
                    <div class="flex-shrink-0">
                        <a href="{{ $profile->website }}" target="_blank" rel="noopener noreferrer" 
                           class="ig-btn ig-btn-ghost text-xs py-2.5 px-5 flex items-center gap-2">
                            <span>Visit Website</span><span class="arrow">→</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Summary Reputation Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Overall Trust Shield -->
            @php
                $ts = $profile->trustScore;
                $overall = $ts ? $ts->overall_score : ($profile->credibility_score * 100);
                if ($overall <= 0) $overall = 70.00;
            @endphp
            <div class="ig-card-dark p-6 relative overflow-hidden shadow-md flex flex-col justify-between min-h-[120px]">
                <div class="absolute -top-16 -right-16 w-32 h-32 rounded-full blur-2xl opacity-20" style="background:var(--ig-accent)"></div>
                <div>
                    <p class="ig-eyebrow text-[10px] text-white/50 mb-1">Overall Trust Score</p>
                    <p class="ig-display text-3xl text-white">{{ number_format($overall, 0) }}/100</p>
                </div>
                <p class="text-[9px]" style="color: #9C9580"> Composite credibility rating</p>
            </div>
            
            <!-- Students Hired -->
            <div class="ig-card p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Students Hired</p>
                <p class="ig-display text-3xl text-[var(--ig-ink)] mt-2">{{ $studentsHired }}</p>
                <p class="text-[9px] text-[var(--ig-faint)]">Placements completed</p>
            </div>
            
            <!-- Average Rating -->
            <div class="ig-card p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Average Rating</p>
                <p class="ig-display text-3xl text-[var(--ig-ink)] mt-2">
                    {{ $averageRating > 0 ? number_format($averageRating, 1) : 'N/A' }}
                </p>
                <p class="text-[9px] font-bold text-amber-500">
                    @if($averageRating > 0)
                        ⭐ {{ number_format($averageRating, 1) }} / 5.0 Rating
                    @else
                        No student reviews yet
                    @endif
                </p>
            </div>
            
            <!-- Payment Reliability -->
            @php
                $paymentScoreVal = $ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? 50.00 : 100.00);
            @endphp
            <div class="ig-card p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Payment Reliability</p>
                <p class="ig-display text-3xl text-[var(--ig-ink)] mt-2">{{ number_format($paymentScoreVal, 0) }}%</p>
                <p class="text-[9px] text-emerald-700 font-bold">
                    {{ $paymentScoreVal >= 100 ? '✓ 100% Reliable' : '⚠️ Deficit alert' }}
                </p>
            </div>
        </div>
        
        <!-- Layout grid details -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: Profile Details, Active Tasks & Student Reviews -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- About Section -->
                <div class="ig-card p-6 sm:p-8">
                    <h2 class="ig-display text-2xl text-[var(--ig-ink)] mb-4">About Startup</h2>
                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed font-normal whitespace-pre-line">{{ $profile->description ?? 'No company description provided.' }}</p>
                </div>
                
                <!-- Open Tasks -->
                <div class="ig-card p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="ig-display text-2xl text-[var(--ig-ink)]">Active Open Tasks</h2>
                        <span class="ig-chip ig-chip-lime text-xs font-bold">{{ $activeTasks->count() }} Available</span>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($activeTasks as $task)
                            <div class="border border-[var(--ig-line)] hover:border-[var(--ig-ink)] rounded-2xl p-5 transition duration-200 group">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-955 font-poppins group-hover:text-[var(--ig-accent)] transition-colors">
                                            {{ $task->title }}
                                        </h3>
                                        <p class="text-xs text-[var(--ig-muted)] mt-1">Compensation: <span class="font-bold text-[var(--ig-accent)]">@if($task->stipend) ₹{{ number_format($task->stipend, 0) }} stipend @else Experience Task @endif</span></p>
                                        <div class="flex flex-wrap gap-1.5 mt-3">
                                            @foreach($task->skills as $skill)
                                                <span class="ig-tag">{{ $skill->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <a href="{{ route('tasks.show', $task->id) }}" 
                                           class="ig-btn ig-btn-primary text-xs py-2 px-4">
                                            Apply Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-[var(--ig-muted)] text-sm">
                                <span>This startup has no open tasks at the moment.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Recent Reviews -->
                <div class="ig-card p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="ig-display text-2xl text-[var(--ig-ink)]">Student Reviews</h2>
                        <span class="ig-chip ig-chip-warn text-xs font-bold">{{ $profile->reviews->count() }} Reviews</span>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse($profile->reviews as $review)
                            <div class="border-b border-[var(--ig-line)] pb-6 last:border-none last:pb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm font-poppins">{{ $review->student->user->name }}</h4>
                                        <p class="text-[10px] text-gray-500 font-semibold">Project: {{ $review->task->title }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-yellow-500 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating) ★ @else ☆ @endif
                                            @endfor
                                        </span>
                                        <p class="text-[10px] text-gray-400 font-semibold">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-650 italic leading-relaxed">"{{ $review->review }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-400 text-sm">
                                <span>No reviews has been posted yet.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Detailed Reputation Trust Score Breakdown -->
            <div class="lg:col-span-4 space-y-6">
                <div class="ig-card p-6 space-y-5">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider border-b border-[var(--ig-line)] pb-3">
                        🛡️ Trust breakdown: {{ number_format($overall, 0) }} / 100
                    </h3>
                    
                    @php
                        $verifyScore = $ts ? $ts->verification_score : ($profile->is_verified ? 100.00 : 0.00);
                        $paymentScore = $ts ? $ts->payment_score : ($profile->wallet_balance < 0 ? 50.00 : 100.00);
                        $ratingScore = $ts ? $ts->student_rating_score : 70.00;
                        
                        // Hiring Score
                        if ($ts) {
                            $hiringScore = $ts->hiring_score;
                        } else {
                            $totalRespondedOffers = $profile->hiringOffers()->whereIn('status', ['accepted', 'rejected'])->count();
                            $hiringScore = $totalRespondedOffers === 0 ? 100.00 : (($profile->hiringOffers()->where('status', 'accepted')->count() / $totalRespondedOffers) * 100);
                        }
                        
                        // Task completion score
                        $totalTasks = $profile->tasks()->count();
                        if ($totalTasks === 0) {
                            $taskScoreVal = 100.00;
                        } else {
                            $completedTasksCount = $profile->tasks()->where(function($q) {
                                $q->where('status', 'completed')
                                  ->orWhereHas('applications.submission', function($subQ) {
                                      $subQ->where('status', 'accepted');
                                  });
                            })->count();
                            $taskScoreVal = ($completedTasksCount / $totalTasks) * 100;
                        }
                    @endphp
                    
                    <div class="space-y-4 text-xs text-[var(--ig-ink-2)]">
                        <!-- Verification -->
                        <div>
                            <div class="flex justify-between font-semibold mb-1.5">
                                <span>Verification Status (25%)</span>
                                <span class="font-bold text-[var(--ig-ink)]">{{ number_format($verifyScore, 0) }}%</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $verifyScore }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Payment -->
                        <div>
                            <div class="flex justify-between font-semibold mb-1.5">
                                <span>Payment Reliability (25%)</span>
                                <span class="font-bold text-[var(--ig-ink)]">{{ number_format($paymentScore, 0) }}%</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $paymentScore }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Student Rating -->
                        <div>
                            <div class="flex justify-between font-semibold mb-1.5">
                                <span>Student Rating Score (20%)</span>
                                <span class="font-bold text-[var(--ig-ink)]">{{ number_format($ratingScore, 0) }}%</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ $ratingScore }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Hiring Success -->
                        <div>
                            <div class="flex justify-between font-semibold mb-1.5">
                                <span>Hiring Success (15%)</span>
                                <span class="font-bold text-[var(--ig-ink)]">{{ number_format($hiringScore, 0) }}%</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $hiringScore }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Task Success -->
                        <div>
                            <div class="flex justify-between font-semibold mb-1.5">
                                <span>Task Completion Success (15%)</span>
                                <span class="font-bold text-[var(--ig-ink)]">{{ number_format($taskScoreVal, 0) }}%</span>
                            </div>
                            <div class="w-full bg-[var(--ig-bg-2)] rounded-full h-1.5 overflow-hidden">
                                <div class="bg-pink-500 h-1.5 rounded-full" style="width: {{ $taskScoreVal }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</x-app-layout>
