<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InternGrowth — Verified Work for Students</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
</head>
<body class="ig-body antialiased min-h-screen">

    <!-- ─── NAV ─── -->
    <nav class="ig-nav">
        <div class="ig-container">
            <div class="flex items-center justify-between h-[68px]">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-[var(--ig-ink)] flex items-center justify-center text-[var(--ig-bg)] ig-display text-lg">IG</div>
                    <div class="leading-tight">
                        <div class="ig-display text-[19px]">InternGrowth</div>
                        <div class="ig-eyebrow text-[9.5px] -mt-0.5">Verified Work · Real Reputation</div>
                    </div>
                </a>
                <div class="hidden md:flex items-center gap-7">
                    <a href="#how" class="ig-nav-link">How it works</a>
                    <a href="#trust" class="ig-nav-link">IPRS Score</a>
                    <a href="{{ route('tasks.index') }}" class="ig-nav-link">Marketplace</a>
                    <a href="#students" class="ig-nav-link">For Students</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="ig-btn ig-btn-primary"><span>Dashboard</span><span class="arrow">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-[var(--ig-accent)]">Sign in</a>
                        <a href="{{ route('register') }}" class="ig-btn ig-btn-primary"><span>Get started</span><span class="arrow">→</span></a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ─── HERO ─── -->
    <section class="relative overflow-hidden">
        <div class="ig-orb ig-orb-a ig-orb-follow"></div>
        <div class="ig-orb ig-orb-b ig-orb-follow"></div>

        <div class="ig-container relative pt-16 pb-24 md:pt-24 md:pb-32">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-end">
                <!-- Left -->
                <div class="lg:col-span-8">
                    <p class="ig-eyebrow ig-anim-fade-up">— A new pipeline for hireable students</p>
                    <h1 class="ig-display text-[44px] sm:text-[64px] md:text-[84px] mt-5 ig-anim-fade-up ig-delay-1">
                        Build a <span class="ig-serif text-[var(--ig-accent)]">portfolio</span><br>
                        startups <em class="ig-serif not-italic text-[var(--ig-ink-2)]">actually</em> trust.<span class="ig-cursor"></span>
                    </h1>
                    <p class="mt-7 max-w-xl text-lg text-[var(--ig-ink-2)] leading-relaxed ig-anim-fade-up ig-delay-2">
                        InternGrowth is the marketplace where students take on real startup tasks, get verified by employers, and grow an <strong>IPRS reputation score</strong> that travels with them — into internships, full-time offers, and the rest of their career.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-9 ig-anim-fade-up ig-delay-3">
                        <a href="{{ route('register') }}" class="ig-magnetic">
                            <span class="ig-btn ig-btn-accent">
                                <span>Start building free</span>
                                <span class="arrow">→</span>
                            </span>
                        </a>
                        <a href="{{ route('tasks.index') }}" class="ig-btn ig-btn-ghost">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Browse the marketplace</span>
                        </a>
                    </div>

                    <!-- Trust row -->
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-3 mt-12 ig-anim-fade-up ig-delay-4">
                        <div class="flex -space-x-2.5">
                            <span class="ig-avatar" style="background:#FF4F19">A</span>
                            <span class="ig-avatar" style="background:#16322F">M</span>
                            <span class="ig-avatar" style="background:#1F3FB5">P</span>
                            <span class="ig-avatar" style="background:#C84B3B">R</span>
                            <span class="ig-avatar" style="background:#0B0F14;color:var(--ig-lime)">+</span>
                        </div>
                        <p class="text-sm text-[var(--ig-ink-2)]"><strong data-counter="2400" data-counter-suffix="+">0</strong> students already shipping work</p>
                        <span class="hidden md:inline-block w-px h-5 bg-[var(--ig-line-2)]"></span>
                        <p class="text-sm text-[var(--ig-ink-2)] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[var(--ig-lime-deep)] animate-pulse"></span>
                            <strong data-counter="184">0</strong> startups hiring live
                        </p>
                    </div>
                </div>

                <!-- Right: floating profile card -->
                <div class="lg:col-span-4 ig-anim-scale-in ig-delay-3">
                    <div class="ig-card-dark p-6 relative" data-tilt>
                        <div class="flex items-center justify-between mb-5">
                            <span class="ig-eyebrow" style="color:#C9C1AE">Live · Talent Profile</span>
                            <span class="ig-chip ig-chip-lime">VERIFIED</span>
                        </div>

                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-14 h-14 rounded-2xl bg-[var(--ig-accent)] flex items-center justify-center ig-display text-2xl text-white">A</div>
                            <div>
                                <p class="ig-display text-xl text-white leading-tight">Aanya R.</p>
                                <p class="ig-mono text-[11px]" style="color:#9C9580">@aanya · IIT-D · CS '26</p>
                            </div>
                        </div>

                        <!-- IPRS gauge -->
                        <div class="bg-black/30 rounded-2xl p-4 mb-4 border border-white/5">
                            <div class="flex justify-between items-end mb-2">
                                <span class="ig-eyebrow" style="color:#9C9580">IPRS Score</span>
                                <span class="ig-mono text-[10px]" style="color:#9C9580">out of 100</span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="ig-stat-num text-5xl text-[var(--ig-lime)]" data-counter="94">0</span>
                                <span class="text-sm" style="color:#C9C1AE">· Elite</span>
                            </div>
                            <div class="mt-3 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-[var(--ig-lime)] rounded-full" style="width:0;animation:igFill 1.6s .8s var(--ease-out) forwards"></div>
                            </div>
                        </div>

                        <!-- Metrics -->
                        <div class="grid grid-cols-2 gap-2 text-[12px]">
                            <div class="flex justify-between"><span style="color:#9C9580">On-time</span><span class="text-white font-semibold">98%</span></div>
                            <div class="flex justify-between"><span style="color:#9C9580">Trust</span><span class="text-white font-semibold">96%</span></div>
                            <div class="flex justify-between"><span style="color:#9C9580">Rating</span><span class="text-white font-semibold">4.9/5</span></div>
                            <div class="flex justify-between"><span style="color:#9C9580">Tasks</span><span class="text-white font-semibold">23</span></div>
                        </div>

                        <div class="mt-5 pt-5 border-t border-white/10 flex items-center justify-between">
                            <div class="flex gap-1.5">
                                <span class="ig-tag" style="border-color:#3a3f47;color:#C9C1AE">React</span>
                                <span class="ig-tag" style="border-color:#3a3f47;color:#C9C1AE">Figma</span>
                                <span class="ig-tag" style="border-color:#3a3f47;color:#C9C1AE">+8</span>
                            </div>
                            <span class="ig-mono text-[10px]" style="color:#9C9580">igrow.app/aanya</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>@keyframes igFill { to { width: 94%; } }</style>
    </section>

    <!-- ─── MARQUEE (Trusted startups) ─── -->
    <section class="border-y border-[var(--ig-line)] bg-[var(--ig-bg-2)] py-7 overflow-hidden">
        <div class="ig-container mb-4">
            <p class="ig-eyebrow">Trusted by startups hiring student talent</p>
        </div>
        <div class="overflow-hidden">
            <div class="ig-marquee">
                @php $brands = ['Razorpay','Zerodha','CRED','Cohere','Linear','Notion','Vercel','Supabase','Ola','Postman','Browserstack','Razorpay','Zerodha','CRED','Cohere','Linear','Notion','Vercel','Supabase','Ola','Postman','Browserstack']; @endphp
                @foreach($brands as $b)
                    <span class="ig-display text-3xl md:text-4xl text-[var(--ig-ink-2)] opacity-70 hover:opacity-100 hover:text-[var(--ig-accent)] transition cursor-default">{{ $b }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ─── HOW IT WORKS ─── -->
    <section id="how" class="ig-container py-24 md:py-32">
        <div class="ig-section-head ig-reveal">
            <div>
                <p class="ig-eyebrow mb-3">— 01 / How it works</p>
                <h2 class="ig-display text-4xl md:text-6xl max-w-3xl">A receipt for every piece of work you ship.</h2>
            </div>
            <a href="{{ route('register') }}" class="hidden md:inline-flex ig-btn ig-btn-ghost"><span>Try the flow</span><span class="arrow">→</span></a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php $steps = [
                ['01','Pick a real task','Browse verified startup tasks — design, code, research, growth. Apply in one click.', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2'],
                ['02','Ship & get verified','Submit your work. Founders review, leave feedback, and verify completion on-chain of trust.', 'M5 13l4 4L19 7'],
                ['03','Grow your IPRS','Every verified task lifts your reputation score. Startups discover you. Offers come to your inbox.', 'M13 7l5 5m0 0l-5 5m5-5H6'],
            ]; @endphp
            @foreach($steps as $i => $s)
                <div class="ig-card p-8 ig-reveal" data-reveal-delay="{{ $i * 120 }}">
                    <div class="flex items-center justify-between mb-12">
                        <span class="ig-mono text-[11px] text-[var(--ig-muted)]">{{ $s[0] }}</span>
                        <svg class="w-5 h-5 text-[var(--ig-ink-2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $s[3] }}"/></svg>
                    </div>
                    <h3 class="ig-display text-3xl mb-3">{{ $s[1] }}</h3>
                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed">{{ $s[2] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ─── IPRS section ─── -->
    <section id="trust" class="ig-container py-24 md:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 ig-reveal">
                <p class="ig-eyebrow mb-3">— 02 / The IPRS Score</p>
                <h2 class="ig-display text-4xl md:text-6xl mb-6">A reputation that <span class="ig-serif text-[var(--ig-accent)]">compounds.</span></h2>
                <p class="text-[var(--ig-ink-2)] leading-relaxed mb-6">
                    IPRS is our proprietary <strong>InternGrowth Professional Reputation Score</strong>. It blends 7 signals — trust, completion rate, on-time delivery, startup satisfaction, communication, interview performance, and professional conduct — into one number recruiters can rely on.
                </p>
                <div class="grid grid-cols-2 gap-3 mb-7">
                    @foreach([
                        ['Trust score','Cross-verified by founders'],
                        ['Completion rate','Did you finish what you started'],
                        ['On-time delivery','Shipped before deadline'],
                        ['Startup satisfaction','1–5 rating per project'],
                        ['Communication','Replies, clarity, ownership'],
                        ['Interview perf.','Conversion of intros to offers'],
                    ] as $m)
                        <div class="ig-card p-4">
                            <p class="text-sm font-semibold">{{ $m[0] }}</p>
                            <p class="text-[11.5px] text-[var(--ig-muted)] mt-1">{{ $m[1] }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('register') }}" class="ig-btn ig-btn-primary"><span>Build my IPRS</span><span class="arrow">→</span></a>
            </div>

            <div class="lg:col-span-7 ig-reveal" data-reveal-delay="120">
                <div class="ig-card-dark p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background:var(--ig-accent)"></div>

                    <div class="relative">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="ig-eyebrow" style="color:#9C9580">IPRS · Live preview</p>
                                <p class="ig-display text-2xl text-white mt-2">Reputation Scorecard</p>
                            </div>
                            <span class="ig-chip ig-chip-lime">VERIFIED</span>
                        </div>

                        <div class="flex items-end gap-6 mb-8">
                            <div>
                                <p class="ig-stat-num text-7xl md:text-8xl text-white">
                                    <span data-counter="94">0</span>
                                </p>
                                <p class="ig-mono text-[11px] mt-1" style="color:#9C9580">out of 100 · top 3%</p>
                            </div>
                            <div class="flex-1 space-y-3 pb-2">
                                @php $bars = [['Trust',96],['Completion',100],['On-time',98],['Communication',96],['Satisfaction',92]]; @endphp
                                @foreach($bars as $b)
                                    <div>
                                        <div class="flex justify-between text-[11px] mb-1" style="color:#C9C1AE">
                                            <span>{{ $b[0] }}</span><span class="font-semibold text-white">{{ $b[1] }}%</span>
                                        </div>
                                        <div class="h-[3px] rounded-full bg-white/10 overflow-hidden">
                                            <div class="h-full bg-[var(--ig-lime)] rounded-full" style="width:{{ $b[1] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-5 flex flex-wrap items-center justify-between gap-3 text-[12px]" style="color:#C9C1AE">
                            <span>Last 12 verified projects · 3 startups</span>
                            <span class="ig-mono">Updated 2 min ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── Stats ─── -->
    <section class="bg-[var(--ig-surface-ink)] text-white py-20">
        <div class="ig-container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                @php $stats = [
                    ['data-counter="2400" data-counter-suffix="+"','Students shipping work'],
                    ['data-counter="184"','Startups hiring','Active'],
                    ['data-counter="1289"','Verified tasks completed'],
                    ['data-counter="92"','% IPRS get hired','%'],
                ]; @endphp
                @foreach([
                    [2400,'Students shipping work','+'],
                    [184,'Startups hiring live',''],
                    [1289,'Verified tasks done',''],
                    [92,'IPRS 80+ get hired','%'],
                ] as $i => $st)
                    <div class="ig-reveal" data-reveal-delay="{{ $i * 100 }}">
                        <p class="ig-stat-num text-5xl md:text-6xl"><span data-counter="{{ $st[0] }}" data-counter-suffix="{{ $st[2] }}">0</span></p>
                        <p class="ig-mono text-[11px] mt-3 opacity-70 uppercase tracking-widest">{{ $st[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ─── For Students ─── -->
    <section id="students" class="ig-container py-24 md:py-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="ig-reveal">
                <p class="ig-eyebrow mb-3">— 03 / For Students</p>
                <h2 class="ig-display text-4xl md:text-6xl mb-6">
                    Stop writing resumes. <span class="ig-serif text-[var(--ig-accent)]">Start shipping receipts.</span>
                </h2>
                <ul class="space-y-4 mb-8">
                    @foreach([
                        'Pick paid microtasks that match your skills',
                        'Get founder feedback within 48 hours',
                        'Auto-generated verified experience portfolio records',
                        'A public profile recruiters can\'t fake-check',
                    ] as $li)
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-5 h-5 rounded-full bg-[var(--ig-lime)] flex items-center justify-center flex-shrink-0">
                                <svg width="11" height="11" fill="none" stroke="var(--ig-ink)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-[var(--ig-ink-2)]">{{ $li }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="ig-btn ig-btn-primary"><span>Create student account</span><span class="arrow">→</span></a>
            </div>

            <div class="ig-reveal" data-reveal-delay="120">
                <div class="relative">
                    <!-- Sample task cards stack -->
                    <div class="ig-card p-6 ml-12 rotate-1 ig-tilt">
                        <span class="ig-chip ig-chip-accent">OPEN · 5 days left</span>
                        <h4 class="ig-display text-2xl mt-3">Landing page revamp</h4>
                        <p class="text-[var(--ig-muted)] text-sm mt-1">Razorpay · Frontend</p>
                        <div class="flex justify-between items-end mt-5">
                            <div class="flex gap-1.5"><span class="ig-tag">React</span><span class="ig-tag">Framer</span></div>
                            <p class="ig-display text-xl">₹15,000</p>
                        </div>
                    </div>
                    <div class="ig-card p-6 -mt-2 -rotate-1 ig-tilt">
                        <span class="ig-chip ig-chip-lime">IN PROGRESS</span>
                        <h4 class="ig-display text-2xl mt-3">User onboarding flow</h4>
                        <p class="text-[var(--ig-muted)] text-sm mt-1">Linear · Design</p>
                        <div class="flex justify-between items-end mt-5">
                            <div class="flex gap-1.5"><span class="ig-tag">Figma</span><span class="ig-tag">UX</span></div>
                            <p class="ig-display text-xl">₹22,000</p>
                        </div>
                    </div>
                    <div class="ig-card p-6 -mt-2 ml-12 rotate-1 ig-tilt">
                        <span class="ig-chip ig-chip-success">VERIFIED · paid</span>
                        <h4 class="ig-display text-2xl mt-3">API documentation</h4>
                        <p class="text-[var(--ig-muted)] text-sm mt-1">Postman · Tech writing</p>
                        <div class="flex justify-between items-end mt-5">
                            <div class="flex gap-1.5"><span class="ig-tag">Docs</span><span class="ig-tag">+1</span></div>
                            <p class="ig-display text-xl">₹8,500</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── CTA ─── -->
    <section class="ig-container pb-24">
        <div class="ig-card-dark p-12 md:p-20 text-center relative overflow-hidden ig-reveal">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full blur-3xl opacity-25" style="background:radial-gradient(circle, var(--ig-accent) 0%, transparent 65%);"></div>
            <div class="relative">
                <p class="ig-eyebrow mb-4" style="color:var(--ig-lime)">— Ready when you are</p>
                <h2 class="ig-display text-5xl md:text-7xl text-white max-w-3xl mx-auto">
                    Your first verified task is <span class="ig-serif text-[var(--ig-lime)]">one click away.</span>
                </h2>
                <div class="flex flex-wrap gap-3 justify-center mt-10">
                    <a href="{{ route('register') }}" class="ig-magnetic"><span class="ig-btn ig-btn-lime"><span>Get started · free</span><span class="arrow">→</span></span></a>
                    <a href="{{ route('tasks.index') }}" class="ig-btn" style="background:transparent;color:white;border:1px solid rgba(255,255,255,.2)">
                        <span>See open tasks</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (large on landing) -->
    <footer class="ig-footer relative overflow-hidden mt-24">
        <div class="absolute -top-32 -left-32 w-[420px] h-[420px] rounded-full blur-[100px] opacity-30" style="background:radial-gradient(circle, var(--ig-accent) 0%, transparent 65%);"></div>
        <div class="absolute -bottom-32 -right-20 w-[380px] h-[380px] rounded-full blur-[100px] opacity-20" style="background:radial-gradient(circle, var(--ig-lime) 0%, transparent 65%);"></div>

        <div class="ig-container relative py-20">
            <!-- Big editorial line -->
            <div class="mb-16">
                <p class="ig-eyebrow text-[var(--ig-lime)] mb-4">— Built for the next generation</p>
                <h2 class="ig-display text-4xl md:text-6xl text-white max-w-3xl">
                    Verified work. Real reputation. <span class="ig-serif text-[var(--ig-lime)]">No filler.</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-[var(--ig-ink)] ig-display text-lg">IG</div>
                        <span class="ig-display text-xl text-white">InternGrowth</span>
                    </div>
                    <p class="text-sm max-w-md leading-relaxed">A marketplace where students build a verifiable portfolio of real startup work — and earn reputation that gets them hired.</p>
                </div>

                <div>
                    <h4 class="text-sm mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('tasks.index') }}">Marketplace</a></li>
                        <li><a href="{{ route('leaderboard') }}">Leaderboard</a></li>
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        @auth<li><a href="{{ route('report.show') }}">Report Issue</a></li>@endauth
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy</a></li>
                        <li><a href="{{ route('terms') }}">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pt-8 border-t border-white/10">
                <p class="ig-mono text-[11px] text-white/50">© {{ date('Y') }} InternGrowth · Made for students who ship.</p>
                <p class="ig-mono text-[11px] text-white/50 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[var(--ig-lime)] animate-pulse"></span>
                    Status: All systems operational
                </p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/interngrowth.js') }}"></script>
</body>
</html>
