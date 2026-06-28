<x-app-layout>
    <div class="ig-container py-10 max-w-4xl">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 border-b border-[var(--ig-line)] pb-6 ig-anim-fade-up">
            <div>
                <p class="ig-eyebrow mb-2">— Work Monitoring Hub</p>
                <h1 class="ig-display text-4xl text-[var(--ig-ink)] font-bold">
                    Updates Timeline for <span class="ig-serif text-[var(--ig-accent)]">{{ $offer->student->user->name }}</span>
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-1 uppercase tracking-wider">
                    Role: {{ $offer->role }} | Domain: {{ $offer->domain }}
                </p>
            </div>
            <div>
                <a href="{{ route('startup.team.index') }}" class="ig-btn ig-btn-ghost text-xs py-2">
                    <span>← Back to Team</span>
                </a>
            </div>
        </div>

        <!-- Timeline of Updates -->
        <div class="space-y-8 relative before:absolute before:inset-0 before:left-6 before:w-0.5 before:bg-[var(--ig-line)]">
            
            <!-- Default Joining Event -->
            <div class="relative pl-12">
                <!-- Dot -->
                <div class="absolute left-4 top-1 w-4.5 h-4.5 rounded-full bg-emerald-500 border-4 border-white flex items-center justify-center"></div>
                <div class="bg-white border border-[var(--ig-line)] rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-widest">— Start Event</p>
                    <h3 class="font-bold text-lg text-[var(--ig-ink)] mt-1">Joined Internship Contract</h3>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Confirmed start of placement on {{ $offer->start_date->format('d M, Y') }}.</p>
                </div>
            </div>

            @forelse($offer->updates as $update)
                <div class="relative pl-12">
                    <!-- Dot -->
                    <div class="absolute left-4 top-1.5 w-4.5 h-4.5 rounded-full bg-[var(--ig-accent)] border-4 border-white"></div>
                    <div class="bg-white border border-[var(--ig-line)] rounded-2xl p-5 hover:shadow-md transition-shadow duration-300">
                        <div class="flex justify-between items-start flex-wrap gap-2">
                            <div>
                                <h3 class="font-bold text-lg text-[var(--ig-ink)]">{{ $update->title }}</h3>
                                <p class="text-[10px] text-[var(--ig-muted)] font-semibold">{{ $update->created_at->format('d M, Y @ H:i') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-[var(--ig-ink-2)] mt-3 whitespace-pre-line leading-relaxed">{{ $update->description }}</p>
                        
                        <!-- Attachments / Links -->
                        <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-[var(--ig-line-2)]">
                            @if($update->github_url)
                                <a href="{{ $update->github_url }}" target="_blank" class="ig-btn text-[10px] py-1.5 px-3 bg-slate-900 border-none hover:bg-slate-950 text-white font-bold rounded-xl flex items-center gap-1">
                                    🐙 GitHub Repository
                                </a>
                            @endif
                            @if($update->demo_url)
                                <a href="{{ $update->demo_url }}" target="_blank" class="ig-btn text-[10px] py-1.5 px-3 bg-[var(--ig-accent)] border-none hover:bg-violet-700 text-white font-bold rounded-xl flex items-center gap-1">
                                    🔗 Live Demo Link
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl pl-0 relative z-10">
                    <p class="font-bold text-base text-[var(--ig-ink)]">No progress updates submitted yet.</p>
                    <p class="text-xs text-[var(--ig-muted)] mt-1">Student updates will appear in this timeline once they begin shipping code.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
