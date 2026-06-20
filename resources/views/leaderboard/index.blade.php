<x-app-layout>
    <div class="ig-container">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Hall of Fame</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95]">
                    Student <span class="ig-serif text-[var(--ig-accent)]">Leaderboard.</span><br>
                    Top shippers in the ecosystem.
                </h1>
            </div>
            <div class="md:col-span-4 md:text-right">
                <p class="text-sm text-[var(--ig-muted)] max-w-xs md:ml-auto">
                    Rankings are updated live based on your overall IPRS reputation score and reliability metrics.
                </p>
            </div>
        </div>

        <!-- Leaderboard Table Container -->
        <div class="ig-card p-6 md:p-8 overflow-hidden ig-reveal">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[var(--ig-line)] pb-4 text-[var(--ig-muted)]">
                            <th class="ig-eyebrow pb-4 w-20">Rank</th>
                            <th class="ig-eyebrow pb-4">Student</th>
                            <th class="ig-eyebrow pb-4 text-right pr-6">IPRS Score</th>
                            <th class="ig-eyebrow pb-4 text-right">Reliability</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--ig-line)]">
                        @foreach($students as $index => $student)
                            @php
                                $rank = $index + 1;
                                $isTopThree = $rank <= 3;
                                $rankClass = $rank === 1 ? 'bg-[var(--ig-lime)] text-[var(--ig-ink)] font-bold' : ($rank === 2 ? 'bg-[var(--ig-accent)] text-white font-bold' : ($rank === 3 ? 'bg-amber-100 text-amber-900 font-bold' : 'bg-[var(--ig-bg-2)] text-[var(--ig-ink-2)]'));
                            @endphp
                            <tr class="hover:bg-[var(--ig-bg)] transition-colors group">
                                <td class="py-5 font-semibold text-sm">
                                    <span class="inline-flex w-8 h-8 rounded-xl items-center justify-center text-xs {{ $rankClass }}">
                                        {{ $rank }}
                                    </span>
                                </td>
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[var(--ig-ink)] flex items-center justify-center text-[var(--ig-bg)] ig-display text-sm font-semibold">
                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('students.public-profile', $student->id) }}" class="ig-display text-lg hover:text-[var(--ig-accent)] transition font-semibold">
                                                {{ $student->user->name }}
                                            </a>
                                            <p class="ig-mono text-[10px] text-[var(--ig-muted)]">Verified Talent</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 text-right font-semibold pr-6">
                                    <span class="ig-display text-xl text-[var(--ig-ink)]">
                                        {{ number_format($student->reputationScore->overall_score ?? 50.00, 0) }}%
                                    </span>
                                </td>
                                <td class="py-5 text-right">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        <span class="ig-mono text-sm font-semibold text-[var(--ig-ink-2)]">
                                            {{ number_format($student->reliability_score * 100, 0) }}%
                                        </span>
                                        <div class="w-16 h-1.5 bg-[var(--ig-line)] rounded-full overflow-hidden hidden sm:block">
                                            <div class="h-full bg-[var(--ig-accent)] rounded-full" style="width: {{ $student->reliability_score * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
