<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            @if($notifications->total() > 0)
                <form method="POST" action="{{ route('notifications.destroy-all') }}"
                      onsubmit="return confirm('Clear all notifications?')">
                    @csrf @method('DELETE')
                    <button class="text-sm text-red-500 hover:text-red-700 font-medium">Clear all</button>
                </form>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->isEmpty())
            <div class="bg-white rounded-xl shadow p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">You're all caught up!</p>
                <p class="text-gray-400 text-sm mt-1">No notifications yet.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    @php
                        $iconBg = match($notification->type) {
                            'success' => 'bg-green-100',
                            'warning' => 'bg-yellow-100',
                            'error'   => 'bg-red-100',
                            default   => 'bg-blue-100',
                        };
                        $iconColor = match($notification->type) {
                            'success' => 'text-green-600',
                            'warning' => 'text-yellow-600',
                            'error'   => 'text-red-600',
                            default   => 'text-blue-600',
                        };
                        $icon = match($notification->type) {
                            'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                            'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                            default   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        };
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border {{ $notification->is_read ? 'border-gray-100 opacity-75' : 'border-indigo-200 ring-1 ring-indigo-100' }} p-4 flex items-start gap-4 transition hover:shadow-md">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $icon !!}
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-semibold text-gray-900 text-sm {{ $notification->is_read ? '' : 'text-indigo-900' }}">
                                    {{ $notification->title }}
                                    @if(!$notification->is_read)
                                        <span class="ml-2 inline-block w-2 h-2 bg-indigo-500 rounded-full align-middle"></span>
                                    @endif
                                </p>
                                <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $notification->message }}</p>
                        </div>

                        <!-- Delete -->
                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-400 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            @if($notifications->hasPages())
                <div class="mt-6">{{ $notifications->links() }}</div>
            @endif
        @endif
    </div>
</x-app-layout>
