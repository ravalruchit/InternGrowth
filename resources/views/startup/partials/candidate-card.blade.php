<div class="bg-white rounded-3xl border border-gray-200 p-6 flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-300 relative">
    
    <!-- Star Bookmark Button -->
    <div class="absolute top-6 right-6 z-10">
        <form action="{{ route('startup.candidates.save', $student->id) }}" method="POST">
            @csrf
            <button type="submit" class="p-2 rounded-full border {{ $student->is_saved ? 'border-yellow-200 bg-yellow-50 text-yellow-500 hover:bg-yellow-100' : 'border-gray-200 hover:bg-gray-50 text-gray-400 hover:text-gray-600' }} transition">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 .587l3.668 7.431 8.2 1.191-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.133 9.409l8.2-1.191L12 .587z"/>
                </svg>
            </button>
        </form>
    </div>

    <div>
        <!-- Student Profile Info Header -->
        <div class="flex items-start space-x-4 mb-4 pr-10">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-700 font-extrabold text-lg flex items-center justify-center rounded-2xl flex-shrink-0">
                {{ strtoupper(substr($student->user->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center space-x-1.5">
                    <h3 class="font-bold text-gray-900 text-base leading-tight">{{ $student->user->name }}</h3>
                    @if($student->is_verified)
                        <span class="text-blue-500 text-sm" title="Academic Verified Profile">✔️</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1.5 font-medium flex items-center">
                    🏫 {{ $student->college_name ?? 'Not Specified' }}
                </p>
            </div>
        </div>

        <!-- Availability Badge -->
        <div class="mb-4">
            @if($student->availability === 'open_to_work')
                <span class="inline-flex items-center text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                    🟢 Open to Work
                </span>
            @elseif($student->availability === 'looking_for_internship')
                <span class="inline-flex items-center text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-full">
                    💼 Looking for Internship
                </span>
            @elseif($student->availability === 'looking_for_job')
                <span class="inline-flex items-center text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full">
                    🚀 Looking for Full-Time
                </span>
            @elseif($student->availability === 'freelance_available')
                <span class="inline-flex items-center text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-full">
                    ⚡ Freelance Available
                </span>
            @endif
        </div>

        <!-- AI Match Percentage & Skill Metrics (Premium Redesign) -->
        <div class="bg-gray-50 rounded-2xl p-4 mb-4 border border-gray-100">
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">AI Score Match</span>
                <span class="text-sm font-black text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                    {{ $student->ai_match['percentage'] ?? 50 }}% Match
                </span>
            </div>
            
            <!-- Matching skills breakdown -->
            <div class="space-y-1.5">
                @if(isset($student->ai_match['breakdown']))
                    @foreach($student->ai_match['breakdown'] as $skillName => $score)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-600 font-medium">{{ $skillName }}</span>
                            <span class="font-bold {{ $score > 0 ? 'text-gray-900' : 'text-gray-400' }}">
                                {{ $score > 0 ? $score.'%' : 'Not Matched' }}
                            </span>
                        </div>
                    @endforeach
                @endif
                <div class="flex justify-between items-center text-xs pt-1 border-t border-dashed border-gray-200">
                    <span class="text-gray-600 font-medium">Communication</span>
                    <span class="font-bold text-gray-900">{{ $student->ai_match['communication'] ?? 90 }}%</span>
                </div>
            </div>
        </div>

        <!-- Success Metrics Stats -->
        <div class="grid grid-cols-3 gap-2 py-3 border-t border-b border-gray-100 text-center mb-6">
            <div>
                <span class="block text-lg font-black text-gray-900">{{ $student->projects_count }}</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Projects</span>
            </div>
            <div>
                <span class="block text-lg font-black text-gray-900">{{ $student->internships_count }}</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Internships</span>
            </div>
            <div>
                <span class="block text-lg font-black text-gray-900">{{ $student->offers_count }}</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Job Offers</span>
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-3">
        <!-- View Public Profile Link -->
        <a href="{{ route('students.public-profile', $student->id) }}" target="_blank" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-xl text-xs transition">
            Full Profile ↗
        </a>
        
        <!-- Slide Preview Drawer Trigger -->
        <button type="button" 
                onclick="openStudentDrawer(this)" 
                data-student="{{ json_encode([
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'initials' => strtoupper(substr($student->user->name, 0, 2)),
                    'college_name' => $student->college_name ?? 'Not Specified',
                    'is_verified' => $student->is_verified,
                    'bio' => $student->bio ?? 'No bio provided.',
                    'availability' => $student->availability,
                    'availability_text' => $student->availability === 'open_to_work' ? 'Open to Work' : 
                                        ($student->availability === 'looking_for_internship' ? 'Looking for Internship' : 
                                        ($student->availability === 'looking_for_job' ? 'Looking for Full-Time' : 'Freelance Available')),
                    'overall_score' => $student->reputationScore->overall_score ?? 50.00,
                    'trust_score' => $student->reputationScore->trust_score ?? 50.00,
                    'completion_rate' => $student->reputationScore->completion_rate ?? 100.00,
                    'on_time_rate' => $student->reputationScore->on_time_rate ?? 100.00,
                    'satisfaction_rating' => $student->reputationScore->satisfaction_rating ?? 0.00,
                    'communication_rating' => $student->reputationScore->communication_rating ?? 0.00,
                    'interview_performance_score' => $student->reputationScore->interview_performance_score ?? 100.00,
                    'interviews_attended' => $student->reputationScore->interviews_attended ?? 0,
                    'interview_success_rate' => $student->reputationScore->interview_success_rate ?? 100.00,
                    'strong_candidate_outcomes' => $student->reputationScore->strong_candidate_outcomes ?? 0,
                    'no_shows' => $student->reputationScore->no_shows ?? 0,
                    'projects' => $student->portfolio ? $student->portfolio->items->map(fn($item) => [
                        'project_title' => $item->project_title,
                        'startup_name' => $item->startup_name,
                        'date' => $item->created_at->format('M Y'),
                        'rating' => $item->rating_received,
                        'skills' => $item->skills_demonstrated ?? []
                    ])->toArray() : [],
                    'skills' => $student->skills->pluck('name')->toArray()
                ]) }}"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm shadow-indigo-100">
            Preview Card
        </button>
    </div>
</div>
