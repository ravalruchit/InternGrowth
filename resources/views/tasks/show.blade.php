<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-8">
            <!-- Startup Info Badge -->
            <div class="mb-4 flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Posted by</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('startups.public-profile', $task->startup->id) }}" class="text-lg font-bold text-indigo-650 hover:text-indigo-855 hover:underline transition">
                            {{ $task->startup->company_name }}
                        </a>
                        @if($task->startup->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                ✓ Verified
                            </span>
                        @endif
                        @php
                            $trustScoreVal = $task->startup->trustScore ? $task->startup->trustScore->overall_score : ($task->startup->credibility_score * 100);
                            if ($trustScoreVal <= 0) $trustScoreVal = 100;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-150">
                            🛡️ {{ number_format($trustScoreVal, 0) }}/100 Trust Score
                        </span>
                    </div>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $task->title }}</h1>
            
            @if(auth()->check() && auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id)
                @php
                    $hasApprovedApp = $task->applications()->where('status', 'approved')->count() > 0;
                @endphp
                <div class="mb-4 flex gap-2">
                    @if(!$hasApprovedApp)
                        <a href="{{ route('tasks.edit', $task->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit Task</a>
                    @else
                        <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed" title="Cannot edit task after approving an application">
                            Edit Task (Locked)
                        </button>
                    @endif
                    @if($task->applications->count() === 0)
                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Delete Task</button>
                        </form>
                    @endif
                </div>
            @endif
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                <p class="text-gray-600">{{ $task->description }}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Required Skills</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($task->skills as $skill)
                        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Reward Points</h3>
                    <p class="text-2xl font-bold text-indigo-600">{{ $task->reward_points }}</p>
                </div>
                @if($task->stipend)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Stipend</h3>
                        <p class="text-2xl font-bold text-green-600">₹{{ $task->stipend }}</p>
                    </div>
                @endif
            </div>

            @auth
                @if(auth()->user()->isStudent())
                    @php
                        $existingApplication = $task->applications->where('student_profile_id', auth()->user()->studentProfile->id)->first();
                        $hasApprovedApplication = $task->applications->where('status', 'approved')->count() > 0;
                        $approvedApplication = $task->applications->where('status', 'approved')->first();
                        $hasAcceptedSubmission = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->count() > 0;
                        $acceptedApplication = $task->applications->filter(function($app) {
                            return $app->submission && $app->submission->status === 'accepted';
                        })->first();
                    @endphp
                    
                    @if($hasAcceptedSubmission || $task->status === 'completed')
                        <!-- Task completed -->
                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-900">This task has been completed</h3>
                                    <p class="text-green-700 mt-1">
                                        This task has been successfully completed by 
                                        <strong>{{ $acceptedApplication ? $acceptedApplication->student->user->name : 'a student' }}</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($hasApprovedApplication && !$existingApplication)
                        <!-- Task already accepted by someone else -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-900">This task has been accepted</h3>
                                    <p class="text-yellow-700 mt-1">
                                        <strong>{{ $approvedApplication ? $approvedApplication->student->user->name : 'A student' }}</strong> has already been approved for this task. Applications are no longer being accepted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($existingApplication)
                        <!-- Show application status -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Your Application Status</h3>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-4 py-2 text-sm font-medium rounded-full {{ $existingApplication->status === 'approved' ? 'bg-green-100 text-green-800' : ($existingApplication->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($existingApplication->status) }}
                                        </span>
                                        @if($existingApplication->status === 'approved')
                                            <span class="text-green-600 font-medium">✓ You've been accepted for this task!</span>
                                        @elseif($existingApplication->status === 'rejected')
                                            <span class="text-red-600 font-medium">✗ Your application was not accepted</span>
                                        @else
                                            <span class="text-yellow-600 font-medium">⏳ Waiting for startup review</span>
                                        @endif
                                    </div>
                                    @if($existingApplication->cover_letter)
                                        <p class="text-sm text-gray-600 mt-3">
                                            <strong>Your cover letter:</strong> {{ Str::limit($existingApplication->cover_letter, 100) }}
                                        </p>
                                    @endif
                                </div>
                                <a href="{{ route('messages.create', [auth()->user()->studentProfile->id, $task->startup_profile_id, $task->id]) }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:shadow-md transition font-medium">
                                    💬 Message
                                </a>
                            </div>
                            
                            @if($existingApplication->status === 'approved')
                                @php
                                    $submission = $existingApplication->submission;
                                @endphp
                                
                                <!-- Submit Work / Revision Section -->
                                <div class="mt-6 pt-6 border-t border-indigo-200">
                                    @if(!$submission)
                                        <!-- No submission yet - show submit button -->
                                        <div class="bg-white rounded-lg p-4 border-2 border-green-400">
                                            <h4 class="font-semibold text-gray-900 mb-2">📝 Ready to Submit Your Work?</h4>
                                            <p class="text-sm text-gray-600 mb-4">You've been approved! Now submit your completed work for review.</p>
                                            <a href="{{ route('submissions.create', $existingApplication->id) }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold">
                                                Submit Work →
                                            </a>
                                        </div>
                                    @elseif($submission->status === 'pending')
                                        <!-- Submission pending review -->
                                        <div class="bg-yellow-50 rounded-lg p-4 border-2 border-yellow-400">
                                            <h4 class="font-semibold text-gray-900 mb-2">⏳ Submission Under Review</h4>
                                            <p class="text-sm text-gray-600">Your work has been submitted and is waiting for startup review.</p>
                                            <p class="text-xs text-gray-500 mt-2">Submitted: {{ $submission->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    @elseif($submission->status === 'revision_requested')
                                        <!-- Revision requested - show revise button -->
                                        <div class="bg-orange-50 rounded-lg p-4 border-2 border-orange-400">
                                            <h4 class="font-semibold text-gray-900 mb-2">🔄 Revision Requested</h4>
                                            <p class="text-sm text-gray-600 mb-2">The startup has requested changes to your submission.</p>
                                            @if($submission->feedback)
                                                <div class="bg-white p-3 rounded border border-orange-200 mb-3">
                                                    <p class="text-xs font-medium text-gray-500 mb-1">Feedback:</p>
                                                    <p class="text-sm text-gray-800">{{ $submission->feedback }}</p>
                                                </div>
                                            @endif
                                            <a href="{{ route('submissions.revise', $submission->id) }}" class="inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 font-semibold">
                                                Revise Submission →
                                            </a>
                                        </div>
                                    @elseif($submission->status === 'accepted')
                                        <!-- Work accepted -->
                                        <div class="bg-green-50 rounded-lg p-4 border-2 border-green-400">
                                            <h4 class="font-semibold text-green-900 mb-2">✅ Work Accepted!</h4>
                                            <p class="text-sm text-green-700">Congratulations! Your submission has been accepted.</p>
                                        </div>
                                    @elseif($submission->status === 'rejected')
                                        <!-- Work rejected -->
                                        <div class="bg-red-50 rounded-lg p-4 border-2 border-red-400">
                                            <h4 class="font-semibold text-red-900 mb-2">❌ Submission Rejected</h4>
                                            @if($submission->feedback)
                                                <div class="bg-white p-3 rounded border border-red-200 mb-2">
                                                    <p class="text-xs font-medium text-gray-500 mb-1">Feedback:</p>
                                                    <p class="text-sm text-gray-800">{{ $submission->feedback }}</p>
                                                </div>
                                            @endif
                                            <p class="text-sm text-red-700">Unfortunately, your submission was not accepted.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Show application form -->
                        <form method="POST" action="{{ route('applications.store', $task->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter (Optional)</label>
                                <textarea name="cover_letter" rows="4" class="w-full border-gray-300 rounded-lg" placeholder="Tell the startup why you're a great fit for this task..."></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition font-medium">Apply Now</button>
                                <a href="{{ route('messages.create', [auth()->user()->studentProfile->id, $task->startup_profile_id, $task->id]) }}" class="bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg hover:shadow-md transition font-medium">
                                    💬 Message Startup
                                </a>
                            </div>
                        </form>
                    @endif
                @endif

                @if(auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id)
                    <!-- AI Recommended Students -->
                    @if($recommendedStudents && $recommendedStudents->count() > 0)
                    <div class="mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border-2 border-blue-200">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">🤖 AI Recommended Students</h2>
                                <p class="text-gray-600 text-sm mt-1">Top students matched to this task's requirements</p>
                            </div>
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">AI POWERED</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($recommendedStudents as $student)
                                <div class="bg-white border-2 border-blue-200 rounded-lg p-4 hover:shadow-xl transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="font-semibold text-lg text-gray-900">{{ $student->user->name }}</h3>
                                                <span class="text-2xl font-bold text-blue-600">{{ $student->match_score }}%</span>
                                                @if($student->match_score >= 80)
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                                        🔥 Perfect Match
                                                    </span>
                                                @elseif($student->match_score >= 60)
                                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">
                                                        ⭐ Good Match
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($student->bio ?? 'No bio available', 100) }}</p>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-medium">
                                                    Reliability: {{ number_format($student->reliability_score * 100, 0) }}%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('students.public-profile', $student->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                View Profile →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-4 text-center">
                            💡 Tip: Reach out to these students directly to invite them to apply!
                        </p>
                    </div>
                    @endif
                
                    <div class="mt-8 border-t pt-8">
                        @include('applications.index', ['applications' => $task->applications, 'task' => $task])
                    </div>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>
