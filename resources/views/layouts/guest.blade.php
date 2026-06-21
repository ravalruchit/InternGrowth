<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InternGrowth') }}</title>
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
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white text-[var(--ig-ink)] flex items-center justify-center ig-display text-lg">IG</div>
                    <div>
                        <p class="ig-display text-xl">InternGrowth</p>
                        <p class="ig-eyebrow text-[9.5px]" style="color:#9C9580">Verified Work · Real Reputation</p>
                    </div>
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
                    <p class="ig-serif text-xl text-white leading-relaxed">"Closed 2 internship offers in 3 weeks. Founders saw my IPRS, not my CGPA."</p>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-9 h-9 rounded-full bg-[var(--ig-accent)] flex items-center justify-center ig-display text-sm text-white">N</div>
                        <div>
                            <p class="text-sm font-semibold text-white">Nikhil S.</p>
                            <p class="ig-mono text-[10px]" style="color:#9C9580">IPRS 91 · NIT Trichy</p>
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
                <a href="/" class="flex lg:hidden items-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-xl bg-[var(--ig-ink)] text-[var(--ig-bg)] flex items-center justify-center ig-display text-lg">IG</div>
                    <span class="ig-display text-xl">InternGrowth</span>
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