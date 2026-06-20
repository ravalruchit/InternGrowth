<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Security</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Task <span class="ig-serif text-[var(--ig-accent)]">Moderation.</span><br>
                    Audit stipends & listings.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('admin.dashboard') }}" class="ig-btn ig-btn-ghost">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>


        <div class="ig-card p-0 overflow-hidden ig-reveal is-in">
            <div class="p-6 border-b border-[var(--ig-line)] flex justify-between items-center bg-[var(--ig-bg-2)]">
                <h2 class="ig-display text-xl">Moderation Queue</h2>
                <span class="ig-chip ig-chip-ink">{{ count($tasks) }} tasks</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ig-line)]">
                    <thead class="bg-[var(--ig-bg-2)]/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Startup</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)] bg-white">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-[var(--ig-bg-2)]/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[var(--ig-ink)]">
                                    {{ $task->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--ig-muted)] font-mono">
                                    {{ $task->startup->company_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->status === 'posted')
                                        <span class="ig-chip ig-chip-success">Posted</span>
                                    @elseif($task->status === 'moderated')
                                        <span class="ig-chip ig-chip-warn">Moderated</span>
                                    @elseif($task->status === 'closed')
                                        <span class="ig-chip ig-chip-danger">Closed</span>
                                    @else
                                        <span class="ig-chip">{{ ucfirst($task->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form method="POST" action="{{ route('admin.tasks.moderate', $task->id) }}" class="inline">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="text-xs bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-[var(--ig-ink)] font-mono font-bold text-[var(--ig-ink)]">
                                            <option value="posted" {{ $task->status === 'posted' ? 'selected' : '' }}>Posted</option>
                                            <option value="moderated" {{ $task->status === 'moderated' ? 'selected' : '' }}>Moderated</option>
                                            <option value="closed" {{ $task->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[var(--ig-muted)] text-sm font-mono">
                                    No tasks registered in the system.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
