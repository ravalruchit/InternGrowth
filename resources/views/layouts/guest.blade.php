@php
    $topStudent = \App\Models\StudentProfile::with(['user', 'reputationScore'])
        ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
        ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) desc')
        ->select('student_profiles.*')
        ->first();

    if ($topStudent && $topStudent->user) {
        $tsUser  = $topStudent->user;
        $tsRep   = $topStudent->reputationScore;
        $tsScore = $tsRep ? round($tsRep->overall_score) : 50;
        $tsInitial = strtoupper(substr($tsUser->name, 0, 1));
        
        $tsNameParts = explode(' ', $tsUser->name);
        $tsShortName = $tsNameParts[0] . (isset($tsNameParts[1]) ? ' ' . substr($tsNameParts[1], 0, 1) . '.' : '');
        $tsCollegeName = $topStudent->college_name ?? 'Student';
        $tsBio = $topStudent->bio ?: 'Closed 2 internship offers in 3 weeks. Founders saw my IPRS, not my CGPA.';
        if (strlen($tsBio) > 120) {
            $tsBio = substr($tsBio, 0, 117) . '...';
        }
    } else {
        // Fallback to static dummy if no students in DB yet
        $tsInitial = 'N';
        $tsShortName = 'Nikhil S.';
        $tsScore = 91;
        $tsCollegeName = 'NIT Trichy';
        $tsBio = 'Closed 2 internship offers in 3 weeks. Founders saw my IPRS, not my CGPA.';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InternGrowth') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
</head>
<body class="ig-body antialiased min-h-screen">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 relative">

        <!-- Left: Editorial pane -->
        <aside class="hidden lg:flex flex-col justify-between bg-[var(--ig-surface-ink)] text-white p-12 relative overflow-hidden">
            <div class="absolute -top-20 -left-20 w-[500px] h-[500px] rounded-full blur-[100px] opacity-30" style="background: radial-gradient(circle, var(--ig-accent) 0%, transparent 65%);"></div>
            <div class="absolute -bottom-32 -right-20 w-[400px] h-[400px] rounded-full blur-[100px] opacity-25" style="background: radial-gradient(circle, var(--ig-lime) 0%, transparent 65%);"></div>

            <div class="relative">
                <a href="/">
                    <x-application-logo class="h-9 w-auto text-white" />
                </a>
            </div>

            <div class="relative">
                <p class="ig-eyebrow mb-5" style="color:var(--ig-lime)">— Why students pick InternGrowth</p>
                <h2 class="ig-display text-5xl leading-[0.95] mb-6">
                    Ship work. Get verified. <span class="ig-serif text-[var(--ig-lime)]">Get hired.</span>
                </h2>
                <p class="text-[15px] leading-relaxed max-w-md" style="color:#C9C1AE">
                    Stop sending cold resumes into the void. Build a portfolio of real, founder-verified work, and let your IPRS score do the talking.
                </p>

                <!-- Mini testimonial -->
                <div class="mt-12 p-6 rounded-2xl border border-white/10 bg-white/[.03] max-w-md">
                    <p class="ig-serif text-xl text-white leading-relaxed">"{{ $tsBio }}"</p>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-9 h-9 rounded-full bg-[var(--ig-accent)] flex items-center justify-center ig-display text-sm text-white">{{ $tsInitial }}</div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $tsShortName }}</p>
                            <p class="ig-mono text-[10px]" style="color:#9C9580">IPRS {{ $tsScore }} · {{ $tsCollegeName }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center gap-6 text-[12px]" style="color:#9C9580">
                <span class="ig-mono">© {{ date('Y') }}</span>
                <a href="{{ route('privacy') }}" class="hover:text-[var(--ig-lime)] transition">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:text-[var(--ig-lime)] transition">Terms</a>
            </div>
        </aside>

        <!-- Right: Auth form pane -->
        <main class="flex items-center justify-center p-6 lg:p-12 relative">
            <div class="w-full max-w-md">
                <!-- Mobile brand -->
                <a href="/" class="flex lg:hidden mb-10">
                    <x-application-logo class="h-9 w-auto text-[var(--ig-ink)]" />
                </a>

                <div class="ig-anim-fade-up">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/interngrowth.js') }}"></script>
</body>
</html>