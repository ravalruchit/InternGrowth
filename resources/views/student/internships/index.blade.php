<x-app-layout>
    <div class="ig-container py-10">
        <!-- Header -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end mb-12 ig-anim-fade-up">
            <div class="md:col-span-8">
                <p class="ig-eyebrow mb-3">— Career Dashboard</p>
                <h1 class="ig-display text-5xl md:text-7xl leading-[0.95] text-[var(--ig-ink)]">
                    My <span class="ig-serif text-[var(--ig-accent)]">Internships.</span>
                </h1>
                <p class="text-xs text-[var(--ig-muted)] mt-2 uppercase tracking-wider">
                    Track offers, active workspaces, streaks, and completed experiences.
                </p>
            </div>
            <div class="md:col-span-4 md:text-right">
                <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-ghost">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Row 1: Team Analytics Widgets -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6 mb-12 ig-reveal is-in">
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500 text-[10px]">Offers Received</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $analytics['total_offers'] }}</p>
                </div>
            </div>
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500 text-[10px]">Active Placements</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins text-emerald-600">{{ $analytics['active'] }}</p>
                </div>
            </div>
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500 text-[10px]">Completed Hires</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins text-indigo-600">{{ $analytics['completed'] }}</p>
                </div>
            </div>
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                <div>
                    <p class="ig-eyebrow text-slate-500 text-[10px]">Total Earnings</p>
                    <p class="text-2xl font-black text-gray-900 mt-2 font-poppins text-[var(--ig-accent)]">₹{{ number_format($analytics['earnings'], 0) }}</p>
                </div>
            </div>
            <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300 col-span-2 lg:col-span-1">
                <div>
                    <p class="ig-eyebrow text-slate-500 text-[10px]">Avg Startup Rating</p>
                    <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">⭐ {{ $analytics['avg_rating'] }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div x-data="{ activeTab: 'active' }" class="space-y-6">
            <div class="flex space-x-2 border-b border-[var(--ig-line)] pb-1 overflow-x-auto scrollbar-none whitespace-nowrap">
                <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'border-[var(--ig-accent)] text-[var(--ig-accent)] font-bold' : 'border-transparent text-gray-500 hover:text-gray-900'" 
                        class="px-4 py-2 border-b-2 text-sm transition font-medium">
                    Pending Offers ({{ $pending->count() }})
                </button>
                <button @click="activeTab = 'active'" 
                        :class="activeTab === 'active' ? 'border-[var(--ig-accent)] text-[var(--ig-accent)] font-bold' : 'border-transparent text-gray-500 hover:text-gray-900'" 
                        class="px-4 py-2 border-b-2 text-sm transition font-medium">
                    Active Internships ({{ $active->count() }})
                </button>
                <button @click="activeTab = 'completed'" 
                        :class="activeTab === 'completed' ? 'border-[var(--ig-accent)] text-[var(--ig-accent)] font-bold' : 'border-transparent text-gray-500 hover:text-gray-900'" 
                        class="px-4 py-2 border-b-2 text-sm transition font-medium">
                    Completed Hires ({{ $completed->count() }})
                </button>
                <button @click="activeTab = 'archive'" 
                        :class="activeTab === 'archive' ? 'border-[var(--ig-accent)] text-[var(--ig-accent)] font-bold' : 'border-transparent text-gray-500 hover:text-gray-900'" 
                        class="px-4 py-2 border-b-2 text-sm transition font-medium">
                    Archive ({{ $archive->count() }})
                </button>
            </div>

            <!-- Tab 1: Pending Offers -->
            <div x-show="activeTab === 'pending'" class="space-y-6" style="display: none;">
                @if($pending->count() > 0)
                    <div class="ig-banner ig-banner-warn text-sm shadow-sm flex items-center gap-2">
                        <span class="text-lg">🚨</span>
                        <div>
                            <p class="font-bold text-amber-955">New internship offer received.</p>
                            <p class="text-[11px] text-amber-900">Please respond within 48 hours to secure your contract details.</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($pending as $offer)
                        <div class="ig-card p-6 flex flex-col justify-between hover:shadow-md transition duration-300">
                            <div>
                                <div class="flex items-center gap-3.5 mb-4">
                                    <div class="w-11 h-11 rounded-full bg-[var(--ig-accent-soft)] flex items-center justify-center font-bold text-[var(--ig-accent)] text-lg uppercase shadow-inner border border-[var(--ig-accent)]/10">
                                        {{ substr($offer->startup->company_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-lg text-[var(--ig-ink)]">{{ $offer->title }}</h3>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->startup->company_name }}</p>
                                    </div>
                                </div>

                                <div class="space-y-2 text-xs font-semibold text-gray-600 bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4">
                                    <div class="flex justify-between">
                                        <span>Stipend:</span>
                                        <span class="text-gray-900">₹{{ number_format($offer->compensation, 0) }} / {{ $offer->compensation_period }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Duration:</span>
                                        <span class="text-gray-900">
                                            @if($offer->start_date && $offer->end_date)
                                                {{ max(1, $offer->start_date->diffInMonths($offer->end_date)) }} months
                                            @else
                                                Flexible
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Offer Date:</span>
                                        <span class="text-gray-900">{{ $offer->created_at->format('d M, Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('student.offers.accept', $offer->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2 rounded-xl text-xs shadow-sm transition">
                                        Accept
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('student.offers.reject', $offer->id) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 font-extrabold py-2 rounded-xl text-xs transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                            <p class="font-bold text-base text-[var(--ig-ink)]">No pending offers.</p>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Hiring pitches sent by startups will render here for your review.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab 2: Active Internships -->
            <div x-show="activeTab === 'active'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($active as $offer)
                        <div class="ig-card p-6 flex flex-col justify-between hover:shadow-md transition duration-300">
                            <div>
                                <div class="flex items-center gap-3.5 mb-4">
                                    <div class="w-11 h-11 rounded-full bg-[var(--ig-accent-soft)] flex items-center justify-center font-bold text-[var(--ig-accent)] text-lg uppercase shadow-inner">
                                        {{ substr($offer->startup->company_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-lg text-[var(--ig-ink)]">{{ $offer->title }}</h3>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->startup->company_name }}</p>
                                    </div>
                                    <span class="ml-auto text-xs font-black text-amber-600 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-full">
                                        🔥 {{ $offer->current_streak }} days
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="space-y-1.5 pt-2 mb-4">
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-gray-500">Placement Progress</span>
                                        <span class="text-[var(--ig-accent)]">{{ $offer->progress_pct }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-[var(--ig-accent-soft)] to-[var(--ig-accent)] h-2 rounded-full transition-all duration-500" style="width: {{ $offer->progress_pct }}%"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-[11px] font-semibold text-gray-600 bg-slate-50 border border-slate-100 rounded-xl p-3 mb-4">
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Start/End Date</p>
                                        <p class="text-gray-900 font-extrabold mt-0.5">{{ $offer->start_date->format('d M') }} — {{ $offer->end_date ? $offer->end_date->format('d M, Y') : 'Ongoing' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Stipend Rate</p>
                                        <p class="text-gray-900 font-extrabold mt-0.5">₹{{ number_format($offer->compensation, 0) }} / mo</p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('student.internships.workspace', $offer->id) }}" class="w-full bg-[var(--ig-accent)] hover:bg-violet-700 text-white font-extrabold py-2.5 rounded-xl text-xs shadow-sm transition text-center">
                                📂 Open Workspace
                            </a>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                            <p class="font-bold text-base text-[var(--ig-ink)]">No active placements.</p>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Confirmed internships will show here once you start working.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab 3: Completed Hires -->
            <div x-show="activeTab === 'completed'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($completed as $offer)
                        <div class="ig-card p-6 flex flex-col justify-between hover:shadow-md transition duration-300">
                            <div>
                                <div class="flex items-center gap-3.5 mb-4">
                                    <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-lg uppercase border border-slate-200">
                                        {{ substr($offer->startup->company_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-lg text-[var(--ig-ink)]">{{ $offer->title }}</h3>
                                        <p class="text-xs text-[var(--ig-muted)]">{{ $offer->startup->company_name }}</p>
                                    </div>
                                    <span class="ml-auto text-xs font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                                        Certified
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-[11px] font-semibold text-gray-600 bg-slate-50 border border-slate-100 rounded-xl p-3 mb-4">
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Performance Score</p>
                                        <p class="text-gray-900 font-extrabold mt-0.5">{{ $offer->internship_score }}/100</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Startup Grade</p>
                                        <p class="text-gray-900 font-extrabold mt-0.5 capitalize">{{ $offer->hiring_success_rating ?: 'N/A' }}</p>
                                    </div>
                                    <div class="col-span-2 border-t border-gray-200/50 pt-2 mt-1">
                                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Conversion Outcome</p>
                                        <p class="font-extrabold mt-0.5 {{ $offer->converted_to_full_time ? 'text-indigo-600' : 'text-gray-650' }}">
                                            {{ $offer->converted_to_full_time ? '🎉 Converted to Full-Time' : 'Completed (Internship Only)' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                @php
                                    $cert = \App\Models\Certificate::where('hiring_offer_id', $offer->id)->first();
                                @endphp
                                @if($cert)
                                    <a href="{{ route('certificates.verify', $cert->certificate_number) }}" target="_blank" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-2 rounded-xl text-xs shadow-sm transition text-center">
                                        🎖️ View Cert
                                    </a>
                                @endif
                                <a href="{{ route('student.profile') }}" class="flex-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-extrabold py-2 rounded-xl text-xs transition text-center">
                                    📁 View Portfolio
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                            <p class="font-bold text-base text-[var(--ig-ink)]">No completed experiences.</p>
                            <p class="text-xs text-[var(--ig-muted)] mt-1">Certified placements will show here once finished.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab 4: Archive -->
            <div x-show="activeTab === 'archive'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($archive as $offer)
                        <div class="ig-card p-6 opacity-60 hover:opacity-100 transition duration-300">
                            <div class="flex items-center gap-3.5 mb-2">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-400 text-sm uppercase">
                                    {{ substr($offer->startup->company_name, 0, 2) }}
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-sm text-[var(--ig-ink)]">{{ $offer->title }}</h3>
                                    <p class="text-[10px] text-[var(--ig-muted)]">{{ $offer->startup->company_name }}</p>
                                </div>
                                <span class="ml-auto text-[9px] font-black text-gray-500 bg-gray-50 border border-gray-150 px-2 py-0.5 rounded-full capitalize">
                                    {{ $offer->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-16 text-[var(--ig-muted)] bg-white border border-[var(--ig-line)] rounded-3xl">
                            <p class="font-bold text-base text-[var(--ig-ink)]">Archive is empty.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
