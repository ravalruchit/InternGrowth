<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Database</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Student <span class="ig-serif text-[var(--ig-accent)]">Management.</span><br>
                    Review registered talent.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right animate-pulse">
                <a href="{{ route('admin.students.create') }}" class="ig-btn ig-btn-primary">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create Student</span>
                </a>
            </div>
        </div>


        <!-- Students Table -->
        <div class="ig-card p-0 overflow-hidden ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] flex justify-between items-center bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">All Registered Students</h2>
                <span class="ig-chip ig-chip-ink">{{ $students->total() }} total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ig-line)]">
                    <thead class="bg-[var(--ig-bg-2)]/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Education</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)] bg-white">
                        @forelse($students as $student)
                            <tr class="hover:bg-[var(--ig-bg-2)]/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-[var(--ig-muted)]">#{{ $student->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-[var(--ig-ink)]">{{ $student->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--ig-muted)] font-mono">{{ $student->email }}</td>
                                <td class="px-6 py-4 text-sm text-[var(--ig-ink-2)]">
                                    {{ $student->studentProfile->education ?? 'Not provided' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--ig-muted)] font-mono">
                                    {{ $student->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.students.edit', $student->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-stone-900 hover:bg-[var(--ig-accent)] text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-stone-850">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.students.delete', $student->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition duration-200 shadow-sm border border-red-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-[var(--ig-muted)] text-sm font-mono">
                                    <svg class="mx-auto h-12 w-12 text-[var(--ig-faint)] mb-3 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="48" height="48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="font-bold">No students found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="px-6 py-4 border-t border-[var(--ig-line)] bg-[var(--ig-bg-2)]/30">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
