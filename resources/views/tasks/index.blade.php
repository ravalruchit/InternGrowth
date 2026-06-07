<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Available Tasks</h1>

        @if(isset($isLimited) && $isLimited)
            <div class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-yellow-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-900">Limited Access - Only 5 Tasks Shown</h3>
                        <p class="text-yellow-800 mt-1">Verify your college email to unlock all available tasks and opportunities!</p>
                        <a href="{{ route('student.verification') }}" class="inline-block mt-3 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 font-semibold text-sm">
                            Verify College Email →
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tasks as $task)
                @php
                    $approvedApp = $task->applications->where('status', 'approved')->first();
                    $completedApp = $task->applications->filter(function($app) {
                        return $app->submission && $app->submission->status === 'accepted';
                    })->first();
                    $isCompleted = $task->status === 'completed' || $completedApp;
                    $isInProgress = !$isCompleted && $approvedApp;
                @endphp
                <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition p-6 {{ $isCompleted ? 'border-l-4 border-green-500' : ($isInProgress ? 'border-l-4 border-yellow-500' : 'border-l-4 border-blue-500') }}">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-xl font-semibold text-gray-900 flex-1">{{ $task->title }}</h3>
                        @if($isCompleted)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-2">
                                ✓ Completed
                            </span>
                        @elseif($isInProgress)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-2">
                                ⏳ In Progress
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 ml-2">
                                📢 Open
                            </span>
                        @endif
                    </div>
                    
                    @if($isCompleted && $completedApp)
                        <p class="text-sm text-green-700 mb-2">
                            <strong>Completed by:</strong> {{ $completedApp->student->user->name }}
                        </p>
                    @elseif($isInProgress && $approvedApp)
                        <p class="text-sm text-yellow-700 mb-2">
                            <strong>Working on it:</strong> {{ $approvedApp->student->user->name }}
                        </p>
                    @endif
                    
                    <p class="text-sm text-gray-500 mb-2">
                        <a href="{{ route('startups.public-profile', $task->startup->id) }}" class="font-bold text-indigo-650 hover:text-indigo-855 hover:underline transition">
                            {{ $task->startup->company_name }}
                        </a>
                    </p>
                    <p class="text-gray-600 mb-4">{{ Str::limit($task->description, 100) }}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($task->skills as $skill)
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-indigo-600">{{ $task->reward_points }} pts</span>
                        <a href="{{ route('tasks.show', $task->id) }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:shadow-lg transition font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No tasks available at the moment.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
