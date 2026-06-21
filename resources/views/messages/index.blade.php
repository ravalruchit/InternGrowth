<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <p class="text-gray-600 mt-1">Chat with {{ auth()->user()->isStudent() ? 'startups' : 'students' }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            @forelse($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation->id) }}" class="block hover:bg-gray-50 transition border-b border-gray-100 last:border-0">
                    <div class="p-6 flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[var(--ig-accent)] to-[#E03E0B] flex items-center justify-center text-white font-bold text-lg">
                                {{ substr(auth()->user()->isStudent() ? $conversation->startup->company_name : $conversation->student->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                             <div class="flex items-center justify-between">
                                 <h3 class="text-lg font-semibold text-gray-900 truncate">
                                     {{ auth()->user()->isStudent() ? $conversation->startup->company_name : $conversation->student->user->name }}
                                 </h3>
                                 <span class="text-sm text-gray-500">{{ $conversation->updated_at->diffForHumans() }}</span>
                             </div>
                             @if($conversation->task)
                                 <p class="text-sm text-[var(--ig-accent)] font-medium">Re: {{ $conversation->task->title }}</p>
                             @endif
                            @if($conversation->latestMessage)
                                <p class="text-sm text-gray-600 truncate mt-1">{{ $conversation->latestMessage->message }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start a conversation from a task page</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
