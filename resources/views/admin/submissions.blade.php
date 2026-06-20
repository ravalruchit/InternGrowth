<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Operations / Integrity Audit</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Submission <span class="ig-serif text-[var(--ig-accent)]">Audit.</span><br>
                    Plagiarism & fraud reviews.
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
                <h2 class="ig-display text-xl">Integrity Log</h2>
                <span class="ig-chip ig-chip-accent">{{ count($submissions) }} flags</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ig-line)]">
                    <thead class="bg-[var(--ig-bg-2)]/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Task</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[var(--ig-muted)] uppercase tracking-wider font-mono">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)] bg-white">
                        @forelse($submissions as $submission)
                            <tr class="hover:bg-[var(--ig-bg-2)]/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[var(--ig-ink)]">
                                    {{ $submission->application->student->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--ig-muted)] font-mono">
                                    {{ $submission->application->task->title ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="ig-chip ig-chip-danger">
                                        Plagiarized
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-[var(--ig-muted)] text-sm font-mono">
                                    No plagiarized submissions in queue.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
