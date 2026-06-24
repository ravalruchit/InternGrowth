<x-app-layout>
    <!-- Include Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="ig-container py-10 space-y-12">
        <!-- Dashboard Header & CSV Export Controls -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pb-6 border-b border-[var(--ig-line)] ig-anim-fade-up">
            <div class="lg:col-span-7">
                <p class="ig-eyebrow mb-2">— Business Intelligence Hub</p>
                <h1 class="ig-display text-4xl md:text-6xl leading-[0.95]">
                    Admin <span class="ig-serif text-[var(--ig-accent)]">Intelligence.</span>
                </h1>
                <p class="text-sm text-[var(--ig-muted)] mt-2">
                    Ecosystem growth trends, financial audits, hiring conversion metrics, and fraud checks.
                </p>
            </div>
            <div class="lg:col-span-5 flex flex-wrap gap-3 justify-start lg:justify-end">
                <a href="{{ route('admin.analytics.export.revenue') }}" class="ig-btn ig-btn-ghost text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    📥 Revenue CSV
                </a>
                <a href="{{ route('admin.analytics.export.hiring') }}" class="ig-btn ig-btn-ghost text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    📥 Hiring CSV
                </a>
                <a href="{{ route('admin.analytics.export.users') }}" class="ig-btn ig-btn-ghost text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    📥 Users CSV
                </a>
            </div>
        </div>

        <!-- Section 1: Platform Health Score (CEO Metric) -->
        <div class="ig-card bg-gradient-to-br from-[var(--ig-accent-soft)] via-[var(--ig-bg-2)] to-[var(--ig-bg)] p-8 border border-[var(--ig-accent)]/20 shadow-md relative overflow-hidden ig-reveal">
            <div class="absolute -top-20 -right-20 w-60 h-60 bg-[var(--ig-accent)]/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <p class="ig-eyebrow text-[var(--ig-accent)] font-bold tracking-widest uppercase">CEO Platform Health Metric</p>
                    <h2 class="ig-display text-2xl md:text-3xl text-[var(--ig-ink)]">Platform Health Score</h2>
                    <p class="text-xs text-[var(--ig-muted)] max-w-xl">
                        Calculated from equal weights of Student/Startup Verification Rates, Escrow Success Ratios, Hiring Placements, and Average Platform Trust Ratings.
                    </p>
                </div>
                <div class="flex items-center gap-6 flex-shrink-0">
                    <div class="text-right">
                        <span class="block text-4xl font-black text-[var(--ig-ink)] font-poppins">
                            {{ $data['health_score'] }} <span class="text-xl text-[var(--ig-muted)]">/ 100</span>
                        </span>
                        <span class="inline-flex items-center text-xs font-extrabold uppercase mt-1 px-2.5 py-0.5 rounded-full {{ $data['health_score'] >= 80 ? 'bg-emerald-50 text-emerald-700' : ($data['health_score'] >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                            {{ $data['health_score'] >= 80 ? 'Excellent' : ($data['health_score'] >= 60 ? 'Average' : 'Needs Review') }}
                            @if($data['health_score_change'] >= 0)
                                <span class="ml-1 text-[10px]">↑ +{{ $data['health_score_change'] }}</span>
                            @else
                                <span class="ml-1 text-[10px]">↓ {{ $data['health_score_change'] }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="relative w-20 h-20 flex items-center justify-center rounded-full border-4 border-[var(--ig-line)]">
                        <div class="absolute inset-0 rounded-full border-4 border-[var(--ig-accent)] transition-all" style="clip-path: polygon(0 0, 100% 0, 100% {{ $data['health_score'] }}%, 0 {{ $data['health_score'] }}%)"></div>
                        <span class="text-lg font-black text-[var(--ig-accent)]">{{ $data['health_score'] }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Executive KPIs -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Executive Overview</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 ig-reveal">
                <!-- Students KPI -->
                <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                    <div>
                        <p class="ig-eyebrow text-slate-500">Student Pool</p>
                        <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ number_format($data['kpis']['students_total']) }}</p>
                        <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold mt-1">
                            <span>✓ {{ $data['kpis']['students_verify_rate'] }}% Verified</span>
                            <span class="text-gray-400 font-normal">|</span>
                            <span>+{{ $data['kpis']['students_new_this_month'] }} this month</span>
                        </div>
                    </div>
                </div>
                <!-- Startups KPI -->
                <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                    <div>
                        <p class="ig-eyebrow text-slate-500">Startup Partners</p>
                        <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ number_format($data['kpis']['startups_total']) }}</p>
                        <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold mt-1">
                            <span>✓ {{ $data['kpis']['startups_verify_rate'] }}% Verified</span>
                            <span class="text-gray-400 font-normal">|</span>
                            <span>+{{ $data['kpis']['startups_new_this_month'] }} this month</span>
                        </div>
                    </div>
                </div>
                <!-- Activities KPI -->
                <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                    <div>
                        <p class="ig-eyebrow text-slate-500">Active Tasks & Apps</p>
                        <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $data['kpis']['active_tasks'] }} <span class="text-sm font-semibold text-gray-500">Tasks</span></p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1 font-semibold">
                            {{ $data['kpis']['active_applications'] }} active applications • {{ $data['kpis']['active_interviews'] }} active interviews
                        </p>
                    </div>
                </div>
                <!-- Placements KPI -->
                <div class="ig-card p-6 flex flex-col justify-between hover:translate-y-[-2px] transition duration-300">
                    <div>
                        <p class="ig-eyebrow text-slate-500">Total Placements</p>
                        <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $data['kpis']['total_hires'] }}</p>
                        <p class="text-xs text-[var(--ig-muted)] mt-1 font-semibold">
                            {{ $data['kpis']['internship_placements'] }} Internships • {{ $data['kpis']['job_placements'] }} Full-Time Jobs
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Revenue Analytics & Forecasting -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Revenue Analytics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Left panel: Revenue Breakdown & Forecast Cards -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="ig-card p-6 bg-gradient-to-br from-emerald-500/5 to-transparent border border-emerald-500/10">
                        <p class="ig-eyebrow text-emerald-800">Financial Metrics</p>
                        <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">₹{{ number_format($data['kpis']['total_revenue'], 2) }}</p>
                        <div class="space-y-2 mt-4 text-xs font-semibold text-gray-700">
                            <div class="flex justify-between">
                                <span class="text-[var(--ig-muted)]">Task Commissions:</span>
                                <span>₹{{ number_format($data['revenue']['task_commission'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--ig-muted)]">Hiring Placement Fees:</span>
                                <span>₹{{ number_format($data['revenue']['hiring_success'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="ig-card p-6 bg-gradient-to-br from-blue-500/5 to-transparent border border-blue-500/10">
                        <p class="ig-eyebrow text-blue-800">Revenue Forecasting</p>
                        <div class="space-y-3 mt-3">
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Projected Revenue (This Month)</span>
                                <span class="text-xl font-extrabold text-gray-900 font-poppins">₹{{ number_format($data['revenue']['forecast_this_month'], 2) }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Expected Revenue (Next Month)</span>
                                <span class="text-xl font-extrabold text-gray-900 font-poppins">₹{{ number_format($data['revenue']['forecast_next_month'], 2) }}</span>
                            </div>
                            <p class="text-[10px] text-[var(--ig-muted)] mt-1.5">
                                Forecasts are computed from active stipends, pending hiring offers, and the current month's {{ $data['revenue']['growth_rate'] }}% growth trajectory.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right panel: Revenue Charts & Top Generating Startups -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="ig-card p-6">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">12-Month Revenue Growth</h3>
                        <div class="h-64 relative">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Generating Startups Table -->
            <div class="ig-card p-6 ig-reveal">
                <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Top Revenue Generating Startups</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[var(--ig-line)] text-[var(--ig-muted)] font-bold uppercase">
                                <th class="pb-3">Startup Name</th>
                                <th class="pb-3 text-right">Commissions Generated (INR)</th>
                                <th class="pb-3 text-right">Tasks Posted</th>
                                <th class="pb-3 text-right">Completed Hires</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--ig-line-2)] font-medium text-gray-800">
                            @forelse($data['revenue']['top_startups'] as $row)
                                <tr>
                                    <td class="py-3 font-bold text-[var(--ig-ink)]">{{ $row['name'] }}</td>
                                    <td class="py-3 text-right text-emerald-700 font-extrabold font-poppins">₹{{ number_format($row['revenue'], 2) }}</td>
                                    <td class="py-3 text-right">{{ $row['tasks_posted'] }}</td>
                                    <td class="py-3 text-right">{{ $row['hires'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-3 text-center text-gray-400 italic">No revenue generated yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 4: Escrow Health & Risk Score -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Escrow Health & Security Auditing</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Escrow Risk Score Card -->
                <div class="lg:col-span-4 ig-card bg-gradient-to-br from-red-500/5 to-transparent border border-red-500/10 p-6 flex flex-col justify-between">
                    <div>
                        <p class="ig-eyebrow text-red-800">Escrow Security Health</p>
                        <p class="text-3xl font-black text-gray-900 mt-2 font-poppins">{{ $data['escrow']['risk_score'] }} <span class="text-sm font-semibold text-gray-500">/ 100</span></p>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase mt-1 px-2.5 py-0.5 rounded-full w-fit {{ $data['escrow']['risk_score'] >= 90 ? 'bg-emerald-50 text-emerald-700' : ($data['escrow']['risk_score'] >= 70 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                            {{ $data['escrow']['risk_score'] >= 90 ? 'Healthy' : ($data['escrow']['risk_score'] >= 70 ? 'Minor Warning' : 'Critical Integrity Risk') }}
                        </div>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-4">
                            Point deductions apply for: completed tasks with locked balances (-10pt), accepted submissions with locked escrows (-10pt), stipend mismatches (-15pt), or orphan escrows (-15pt).
                        </p>
                    </div>
                </div>

                <!-- Escrow Valuations Overview -->
                <div class="lg:col-span-8 ig-card p-6 space-y-4">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Escrow Financial Ledger</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                            <span class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Locked in Escrow</span>
                            <span class="text-xl font-extrabold text-[var(--ig-ink)] font-poppins">₹{{ number_format($data['escrow']['val_locked'], 2) }}</span>
                            <span class="block text-[10px] text-[var(--ig-muted)] mt-1 font-semibold">{{ $data['escrow']['locked_count'] }} active tasks</span>
                        </div>
                        <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                            <span class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Released to Students</span>
                            <span class="text-xl font-extrabold text-[var(--ig-ink)] font-poppins">₹{{ number_format($data['escrow']['val_released'], 2) }}</span>
                            <span class="block text-[10px] text-[var(--ig-muted)] mt-1 font-semibold">{{ $data['escrow']['released_count'] }} successful tasks</span>
                        </div>
                        <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                            <span class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Refunded to Startups</span>
                            <span class="text-xl font-extrabold text-[var(--ig-ink)] font-poppins">₹{{ number_format($data['escrow']['val_refunded'], 2) }}</span>
                            <span class="block text-[10px] text-[var(--ig-muted)] mt-1 font-semibold">{{ $data['escrow']['refunded_count'] }} refunded tasks</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                        <span>📊 Escrow Success Rate: <span class="text-[var(--ig-accent)] font-extrabold">{{ $data['escrow']['success_rate'] }}%</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5 & 6: Hiring Funnel & Conversion Loss Analytics -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Talent Conversion & Drop-off Analytics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Left Panel: Funnel Stepper & Drop percentages -->
                <div class="lg:col-span-8 ig-card p-6 space-y-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-2">Visual Placement Pipeline & Drop-offs</h3>
                    <div class="h-80 relative">
                        <canvas id="funnelChart"></canvas>
                    </div>
                </div>

                <!-- Right Panel: Conversion Rates Detail -->
                <div class="lg:col-span-4 ig-card p-6 space-y-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Funnel Phase Drops</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700">
                                <span>Applications → Task Started</span>
                                <span class="text-red-600 font-extrabold">{{ $data['funnel_drops']['app_to_task'] }}% Drop</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-2 overflow-hidden mt-1 shadow-inner">
                                <div class="bg-red-500 h-full rounded-full" style="width: {{ $data['funnel_drops']['app_to_task'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700">
                                <span>Task Started → Task Completed</span>
                                <span class="text-red-600 font-extrabold">{{ $data['funnel_drops']['task_to_complete'] }}% Drop</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-2 overflow-hidden mt-1 shadow-inner">
                                <div class="bg-red-500 h-full rounded-full" style="width: {{ $data['funnel_drops']['task_to_complete'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700">
                                <span>Task Completed → Interviewed</span>
                                <span class="text-red-600 font-extrabold">{{ $data['funnel_drops']['complete_to_interview'] }}% Drop</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-2 overflow-hidden mt-1 shadow-inner">
                                <div class="bg-red-500 h-full rounded-full" style="width: {{ $data['funnel_drops']['complete_to_interview'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700">
                                <span>Interviewed → Offer Sent</span>
                                <span class="text-red-600 font-extrabold">{{ $data['funnel_drops']['interview_to_offer'] }}% Drop</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-2 overflow-hidden mt-1 shadow-inner">
                                <div class="bg-red-500 h-full rounded-full" style="width: {{ $data['funnel_drops']['interview_to_offer'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-gray-700">
                                <span>Offers Sent → Placed (Joined)</span>
                                <span class="text-red-600 font-extrabold">{{ $data['funnel_drops']['offer_to_accept'] }}% Drop</span>
                            </div>
                            <div class="w-full bg-gray-150 rounded-full h-2 overflow-hidden mt-1 shadow-inner">
                                <div class="bg-red-500 h-full rounded-full" style="width: {{ $data['funnel_drops']['offer_to_accept'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 7: Student Analytics -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Student Ecosystem Metrics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- KPI Panel -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="ig-card p-6 bg-gradient-to-br from-indigo-500/5 to-transparent border border-indigo-500/10">
                        <p class="ig-eyebrow text-indigo-800">IPRS Quality Distribution</p>
                        <div class="space-y-3 mt-4 text-xs font-semibold text-gray-700">
                            <div class="flex justify-between">
                                <span>Average IPRS Score:</span>
                                <span>{{ $data['students']['avg_iprs'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Students above 80 IPRS:</span>
                                <span class="text-emerald-700">{{ $data['students']['above_80'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Students above 90 IPRS:</span>
                                <span class="text-emerald-700 font-black">👑 {{ $data['students']['above_90'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Colleges list -->
                    <div class="ig-card p-6">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Top Colleges</h3>
                        <div class="space-y-2.5">
                            @forelse($data['students']['top_colleges'] as $col)
                                <div class="flex justify-between items-center text-xs">
                                    <div>
                                        <p class="font-bold text-[var(--ig-ink)] truncate max-w-[180px]">{{ $col['college_name'] }}</p>
                                        <span class="text-[9px] text-[var(--ig-muted)] uppercase font-bold">{{ $col['student_count'] }} Students ({{ $col['verified_count'] }} Verified)</span>
                                    </div>
                                    <span class="font-black text-[var(--ig-accent)] font-poppins">⭐ {{ round($col['avg_iprs'], 1) }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic">No college registrations yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Student Performers Table -->
                <div class="lg:col-span-8 ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Top 10 Candidate Profiles (by IPRS)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-[var(--ig-line)] text-[var(--ig-muted)] font-bold uppercase">
                                    <th class="pb-3">Candidate</th>
                                    <th class="pb-3">Primary Domain</th>
                                    <th class="pb-3 text-right">IPRS Score</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--ig-line-2)] font-medium text-gray-800">
                                @forelse($data['students']['top_list'] as $sp)
                                    <tr>
                                        <td class="py-3 font-bold text-[var(--ig-ink)]">{{ $sp->user->name ?? 'Candidate #' . $sp->id }}</td>
                                        <td class="py-3 text-[var(--ig-muted)]">{{ $sp->primary_domain ?? 'Software Development' }}</td>
                                        <td class="py-3 text-right font-extrabold text-[var(--ig-accent)] font-poppins">{{ round($sp->overall_score ?? 50, 1) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-3 text-center text-gray-400 italic">No students available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 8: Startup Analytics -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Startup Partner Analytics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Startup trust and no show KPIs -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="ig-card p-6 bg-gradient-to-br from-teal-500/5 to-transparent border border-teal-500/10">
                        <p class="ig-eyebrow text-teal-800">Startup Trust Index</p>
                        <div class="space-y-3 mt-4 text-xs font-semibold text-gray-700">
                            <div class="flex justify-between">
                                <span>Average Trust Score:</span>
                                <span>{{ $data['startups']['avg_trust'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Startups above 80 Trust:</span>
                                <span class="text-emerald-700">{{ $data['startups']['above_80'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hiring Quality Metrics -->
                    <div class="ig-card p-6">
                        <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-3">Hiring Success & Quality Analytics</h3>
                        <div class="space-y-3 text-xs font-semibold text-gray-700">
                            <div class="flex justify-between">
                                <span class="text-[var(--ig-muted)]">Avg Internship Duration:</span>
                                <span>{{ $data['hiring_quality']['avg_duration'] }} months</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--ig-muted)]">Completed Internships:</span>
                                <span>{{ $data['hiring_quality']['completed_internships'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--ig-muted)]">Failed Hires (Cancelled):</span>
                                <span class="text-red-650">{{ $data['hiring_quality']['cancelled_hires'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--ig-muted)]">Withdrawn Offers:</span>
                                <span class="text-gray-500">{{ $data['hiring_quality']['withdrawn_offers'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Hiring Startups Table -->
                <div class="lg:col-span-8 ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Top Hiring Startups</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-[var(--ig-line)] text-[var(--ig-muted)] font-bold uppercase">
                                    <th class="pb-3">Startup Company</th>
                                    <th class="pb-3 text-right">Tasks Posted</th>
                                    <th class="pb-3 text-right">Placements Completed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--ig-line-2)] font-medium text-gray-800">
                                @forelse($data['startups']['top_list'] as $sp)
                                    <tr>
                                        <td class="py-3 font-bold text-[var(--ig-ink)]">{{ $sp->company_name ?? $sp->user->name ?? 'Startup #' . $sp->id }}</td>
                                        <td class="py-3 text-right">{{ $sp->tasks_posted_count }}</td>
                                        <td class="py-3 text-right font-black text-emerald-700">{{ $sp->hires_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-3 text-center text-gray-400 italic">No startups active.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 9: Domain Analytics -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Domain Performance</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Top Performing Domain Card -->
                <div class="lg:col-span-4 ig-card bg-gradient-to-br from-[var(--ig-accent-soft)] to-transparent border border-[var(--ig-accent)]/15 p-6 flex flex-col justify-between">
                    <div>
                        <p class="ig-eyebrow text-[var(--ig-accent)] font-bold tracking-widest uppercase">Ecosystem Category Leader</p>
                        <h3 class="ig-display text-2xl text-[var(--ig-ink)] mt-2">{{ $data['domains']['top_name'] }}</h3>
                        <p class="text-xs text-[var(--ig-muted)] mt-1.5 leading-relaxed">
                            Calculated by weighing placements, revenue generation, and student supply volume.
                        </p>
                    </div>
                </div>

                <!-- Domain Details List -->
                <div class="lg:col-span-8 ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Domain Metrics Breakdown</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-[var(--ig-line)] text-[var(--ig-muted)] font-bold uppercase">
                                    <th class="pb-3">Domain</th>
                                    <th class="pb-3 text-right">Students</th>
                                    <th class="pb-3 text-right">Tasks</th>
                                    <th class="pb-3 text-right">Applications</th>
                                    <th class="pb-3 text-right">Hires</th>
                                    <th class="pb-3 text-right">Revenue (INR)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--ig-line-2)] font-medium text-gray-800">
                                @foreach($data['domains']['list'] as $domainName => $row)
                                    <tr class="{{ $domainName === $data['domains']['top_name'] ? 'bg-[var(--ig-accent-soft)]/10 font-bold' : '' }}">
                                        <td class="py-3 font-bold text-[var(--ig-ink)]">{{ $domainName }}</td>
                                        <td class="py-3 text-right">{{ $row['students'] }}</td>
                                        <td class="py-3 text-right">{{ $row['tasks'] }}</td>
                                        <td class="py-3 text-right">{{ $row['applications'] }}</td>
                                        <td class="py-3 text-right">{{ $row['hires'] }}</td>
                                        <td class="py-3 text-right text-emerald-700 font-extrabold font-poppins">₹{{ number_format($row['revenue'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 10: Skill Demand & Revenue -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Skill Demand Gap & Revenue Analysis</h2>
            <div class="ig-card p-6 ig-reveal">
                <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Talent Supply and Demand Shortage Details</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[var(--ig-line)] text-[var(--ig-muted)] font-bold uppercase">
                                <th class="pb-3">Skill Name</th>
                                <th class="pb-3 text-right">Task Demand (Tasks Posted)</th>
                                <th class="pb-3 text-right">Verified Supply (Students)</th>
                                <th class="pb-3 text-right text-red-650">Shortage Gap</th>
                                <th class="pb-3 text-right text-emerald-700">Commissions Earned (INR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--ig-line-2)] font-medium text-gray-800">
                            @forelse($data['skills_revenue'] as $row)
                                <tr>
                                    <td class="py-3 font-bold text-[var(--ig-ink)]">{{ $row['skill_name'] }}</td>
                                    <td class="py-3 text-right font-poppins">{{ $row['demand'] }}</td>
                                    <td class="py-3 text-right font-poppins">{{ $row['supply'] }}</td>
                                    <td class="py-3 text-right font-extrabold font-poppins text-red-600">
                                        {{ $row['gap'] > 0 ? $row['gap'] . ' shortage' : '✓ sufficient' }}
                                    </td>
                                    <td class="py-3 text-right font-extrabold text-emerald-700 font-poppins">₹{{ number_format($row['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 text-center text-gray-400 italic">No skills demand data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 11: Verification Analytics -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Verification Pipelines</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Students verification backlog and review links -->
                <div class="lg:col-span-6 ig-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Student ID Card Queue</h3>
                            <a href="{{ route('admin.student-id-queue') }}" class="text-xs font-bold text-[var(--ig-accent)] hover:underline">Go to Queue →</a>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                                <span class="block text-[8px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Pending (Manual)</span>
                                <span class="text-lg font-black text-amber-600 font-poppins">{{ $data['verification']['student_pending'] }}</span>
                            </div>
                            <div class="p-3 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                                <span class="block text-[8px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Approved IDs</span>
                                <span class="text-lg font-black text-emerald-700 font-poppins">{{ $data['verification']['student_approved'] }}</span>
                            </div>
                            <div class="p-3 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                                <span class="block text-[8px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Rejected IDs</span>
                                <span class="text-lg font-black text-red-650 font-poppins">{{ $data['verification']['student_rejected'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Startup credentials review queue -->
                <div class="lg:col-span-6 ig-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider">Startup Document Queue</h3>
                            <a href="{{ route('admin.verifications') }}" class="text-xs font-bold text-[var(--ig-accent)] hover:underline">Go to Queue →</a>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                                <span class="block text-[8px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Pending Review</span>
                                <span class="text-lg font-black text-amber-600 font-poppins">{{ $data['verification']['startup_pending'] }}</span>
                            </div>
                            <div class="p-3 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                                <span class="block text-[8px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Verified businesses</span>
                                <span class="text-lg font-black text-emerald-700 font-poppins">{{ $data['verification']['startup_approved'] }}</span>
                            </div>
                            <div class="p-3 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                                <span class="block text-[8px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Rejected profiles</span>
                                <span class="text-lg font-black text-red-650 font-poppins">{{ $data['verification']['startup_rejected'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI OCR Accuracy stats -->
            <div class="ig-card p-6 ig-reveal">
                <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Gemini AI OCR Scanner Statistics</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-xs">
                    <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                        <span class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">AI Approved (Auto-pass)</span>
                        <span class="text-2xl font-black text-emerald-700 font-poppins">{{ $data['verification']['ai_approved'] }}</span>
                    </div>
                    <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                        <span class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">AI Rejected (Auto-fail)</span>
                        <span class="text-2xl font-black text-red-650 font-poppins">{{ $data['verification']['ai_rejected'] }}</span>
                    </div>
                    <div class="p-4 bg-[var(--ig-bg-2)] border border-[var(--ig-line-2)] rounded-xl">
                        <span class="block text-[10px] font-bold text-[var(--ig-muted)] uppercase tracking-wider">Escalated to Manual Audit</span>
                        <span class="text-2xl font-black text-amber-600 font-poppins">{{ $data['verification']['ai_escalated'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 12: Wallet Diagnostics -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Wallet Integrity Diagnostics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ig-reveal">
                <!-- Largest Wallet Holders -->
                <div class="lg:col-span-6 ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Largest Startup Wallets</h3>
                    <div class="space-y-2">
                        @foreach($data['wallet']['largest_wallets'] as $wh)
                            <div class="flex justify-between items-center text-xs font-semibold">
                                <span class="text-[var(--ig-ink)] font-bold">{{ $wh['name'] }}</span>
                                <span class="font-poppins font-black text-gray-800">₹{{ number_format($wh['balance'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Largest Payout Recipients -->
                <div class="lg:col-span-6 ig-card p-6">
                    <h3 class="text-xs font-bold text-[var(--ig-muted)] uppercase tracking-wider mb-4">Largest Student Wallet Balances</h3>
                    <div class="space-y-2">
                        @foreach($data['wallet']['largest_payouts'] as $pr)
                            <div class="flex justify-between items-center text-xs font-semibold">
                                <span class="text-[var(--ig-ink)] font-bold">{{ $pr['name'] }}</span>
                                <span class="font-poppins font-black text-emerald-700">₹{{ number_format($pr['balance'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ledger mismatches lists -->
            @if(count($data['wallet']['mismatches']) > 0)
                <div class="ig-card bg-red-50/50 border border-red-200 p-6 ig-reveal">
                    <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-3">⚠️ Ledger Balance Anomalies</h3>
                    <div class="space-y-2 text-xs font-medium text-red-700">
                        @foreach($data['wallet']['mismatches'] as $wm)
                            <p>
                                <strong>{{ $wm['type'] }} "{{ $wm['name'] }}" Mismatch:</strong> 
                                Profile Wallet is ₹{{ number_format($wm['balance'], 2) }}, but Ledger transactions aggregate to ₹{{ number_format($wm['ledger'], 2) }}. (Mismatch: ₹{{ number_format($wm['anomaly'], 2) }}).
                            </p>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="ig-card bg-emerald-50/30 border border-emerald-200/50 p-5 ig-reveal">
                    <p class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                        <span>✓ Wallet Diagnostics Clear:</span>
                        <span class="font-medium text-gray-700">All student and startup wallet balances align perfectly with transaction history credits/debits.</span>
                    </p>
                </div>
            @endif
        </div>

        <!-- Section 13: Admin Alerts Center -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Ecosystem Alerts Center</h2>
            <div class="ig-card p-6 space-y-3.5 ig-reveal">
                @forelse($data['alerts'] as $alert)
                    <div class="flex items-start gap-3 p-4 rounded-xl border {{ $alert['severity'] === 'critical' ? 'bg-red-50 border-red-200 text-red-800' : ($alert['severity'] === 'high' ? 'bg-orange-50 border-orange-200 text-orange-800' : ($alert['severity'] === 'medium' ? 'bg-yellow-50 border-yellow-250 text-yellow-800' : 'bg-blue-50 border-blue-200 text-blue-800')) }}">
                        <span class="text-lg flex-shrink-0">
                            {{ $alert['severity'] === 'critical' ? '🚨' : ($alert['severity'] === 'high' ? '⚠️' : ($alert['severity'] === 'medium' ? '⚡' : 'ℹ️')) }}
                        </span>
                        <div>
                            <span class="block text-[9px] uppercase font-bold tracking-wider opacity-75">{{ $alert['category'] }} • {{ $alert['severity'] }} severity</span>
                            <p class="text-xs font-semibold mt-0.5 leading-relaxed">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-gray-550 font-bold italic">
                        ✓ All systems operational. No active anomalies or backlog alerts.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Old Control Panel Shortcuts Retained -->
        <div class="space-y-6">
            <h2 class="ig-display text-2xl pb-3 border-b border-[var(--ig-line)] ig-reveal">Operations Control Panels</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ig-reveal">
                <a href="{{ route('admin.students') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">01</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">Manage Students</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">MANUAL STUDENT PROFILE MODERATION</p>
                    </div>
                </a>
                <a href="{{ route('admin.startups') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">02</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">Manage Startups</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">MANUAL STARTUP PROFILE MODERATION</p>
                    </div>
                </a>
                <a href="{{ route('admin.verifications') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">03</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">Verify Startups</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">BUSINESS KYC REVIEW</p>
                    </div>
                </a>
                <a href="{{ route('admin.student-id-queue') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">04</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">🤖 AI ID Queue</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">KYC SCANNER MANUAL AUDIT</p>
                    </div>
                </a>
                <a href="{{ route('admin.tasks') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">05</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">Moderate Tasks</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">MARKETPLACE OFFER MODERATION</p>
                    </div>
                </a>
                <a href="{{ route('admin.wallets') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">06</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">Manage Wallets</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">VAULT AND STIPEND OVERRIDES</p>
                    </div>
                </a>
                <a href="{{ route('admin.topup.index') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">07</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">Topup Requests</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">DEPOSIT TRANSFERS QUEUE</p>
                    </div>
                </a>
                <a href="{{ route('admin.ai-debug') }}" class="ig-card p-6 flex flex-col justify-between h-36 group relative overflow-hidden bg-white border border-[var(--ig-line)] hover:translate-y-[-4px] hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] text-[var(--ig-accent)] font-mono font-bold bg-[var(--ig-accent-soft)] py-0.5 px-2 rounded-full transition-all duration-300 group-hover:bg-[var(--ig-accent)] group-hover:text-white">08</span>
                        <span class="text-[var(--ig-muted)] group-hover:text-[var(--ig-accent)] group-hover:translate-x-1 transition-all duration-300">→</span>
                    </div>
                    <div>
                        <h3 class="ig-display text-base text-[var(--ig-ink)] font-bold group-hover:text-[var(--ig-accent)] transition-colors duration-300">🤖 AI Debugger</h3>
                        <p class="text-[10px] text-[var(--ig-muted)] mt-0.5 font-bold tracking-wider uppercase">GEMINI DIAGNOSTICS CONTROL</p>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <!-- Chart.js configuration scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data bindings from PHP backend
            const revenueData = @json($data['revenue']['chart_12m']);
            const funnelData = @json($data['funnel']);
            const dropoffData = @json($data['funnel_drops']);

            // 1. REVENUE LINE CHART
            const revCanvas = document.getElementById('revenueChart');
            if (revCanvas) {
                new Chart(revCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: revenueData.map(d => d.month),
                        datasets: [
                            {
                                label: 'Task Commissions',
                                data: revenueData.map(d => d.tasks),
                                borderColor: '#E03E0B',
                                backgroundColor: 'rgba(224, 62, 11, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Placement Fees',
                                data: revenueData.map(d => d.hiring),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Total Revenue',
                                data: revenueData.map(d => d.total),
                                borderColor: '#1f2937',
                                backgroundColor: 'rgba(31, 41, 55, 0.05)',
                                borderWidth: 3,
                                borderDash: [5, 5],
                                fill: false,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Inter, system-ui',
                                        weight: 'bold',
                                        size: 10
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: { family: 'Inter, system-ui', size: 9 },
                                    callback: function(value) { return '₹' + value; }
                                }
                            },
                            x: {
                                ticks: { font: { family: 'Inter, system-ui', size: 9 } }
                            }
                        }
                    }
                });
            }

            // 2. HORIZONTAL PIPELINE & drop-offs CHART
            const funnelCanvas = document.getElementById('funnelChart');
            if (funnelCanvas) {
                new Chart(funnelCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Applications', 'Task Started', 'Task Completed', 'Interviewed', 'Offer Sent', 'Offer Accepted', 'Joined', 'Completed Hire'],
                        datasets: [{
                            label: 'Candidates count',
                            data: [
                                funnelData.applications,
                                funnelData.task_started,
                                funnelData.task_completed,
                                funnelData.interviewed,
                                funnelData.offer_sent,
                                funnelData.offer_accepted,
                                funnelData.joined,
                                funnelData.completed
                            ],
                            backgroundColor: [
                                '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#10b981'
                            ],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { font: { family: 'Inter, system-ui', size: 9 } }
                            },
                            y: {
                                ticks: { font: { family: 'Inter, system-ui', size: 9, weight: 'bold' } }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
