<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InternGrowth — Verified Work for Students</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/design-system.css')); ?>?v=1.2">
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
                    <a href="<?php echo e(route('tasks.index')); ?>" class="ig-nav-link">Marketplace</a>
                    <a href="#students" class="ig-nav-link">For Students</a>
                </div>
                <div class="flex items-center gap-3">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="ig-btn ig-btn-primary"><span>Dashboard</span><span class="arrow">→</span></a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium hover:text-[var(--ig-accent)]">Sign in</a>
                        <a href="<?php echo e(route('register')); ?>" class="ig-btn ig-btn-primary"><span>Get started</span><span class="arrow">→</span></a>
                    <?php endif; ?>
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
                        Your <span class="ig-serif text-[var(--ig-accent)]">Work</span> Becomes<br>
                        Your <em class="ig-serif not-italic text-[var(--ig-ink-2)]">Resume.</em><span class="ig-cursor"></span>
                    </h1>
                    <p class="mt-7 max-w-xl text-lg text-[var(--ig-ink-2)] leading-relaxed ig-anim-fade-up ig-delay-2">
                        InternGrowth is the marketplace where students take on real startup tasks, get verified by employers, and grow an <strong>IPRS reputation score</strong> that travels with them — into internships, full-time offers, and the rest of their career.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-9 ig-anim-fade-up ig-delay-3">
                        <a href="<?php echo e(route('register')); ?>" class="ig-magnetic">
                            <span class="ig-btn ig-btn-accent">
                                <span>Start building free</span>
                                <span class="arrow">→</span>
                            </span>
                        </a>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="ig-btn ig-btn-ghost">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Browse the marketplace</span>
                        </a>
                    </div>

                    <!-- Trust row (dynamic) -->
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-3 mt-12 ig-anim-fade-up ig-delay-4">
                        <div class="flex -space-x-2.5">
                            <?php $avatarColors = ['#FF4F19','#16322F','#1F3FB5','#C84B3B']; ?>
                            <?php $__currentLoopData = \App\Models\StudentProfile::with('user')->limit(4)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="ig-avatar" style="background:<?php echo e($avatarColors[$i % 4]); ?>"><?php echo e(strtoupper(substr($sp->user->name ?? 'S', 0, 1))); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <span class="ig-avatar" style="background:#0B0F14;color:var(--ig-lime)">+</span>
                        </div>
                        <p class="text-sm text-[var(--ig-ink-2)]"><strong data-counter="<?php echo e($studentsCount); ?>" data-counter-suffix="+">0</strong> students already shipping work</p>
                        <span class="hidden md:inline-block w-px h-5 bg-[var(--ig-line-2)]"></span>
                        <p class="text-sm text-[var(--ig-ink-2)] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[var(--ig-lime-deep)] animate-pulse"></span>
                            <strong data-counter="<?php echo e($startupsCount); ?>">0</strong> startups hiring live
                        </p>
                    </div>
                </div>

                <!-- Right: floating profile card (dynamic — top leaderboard student) -->
                <div class="lg:col-span-4 ig-anim-scale-in ig-delay-3">
                    <?php if($topStudent && $topStudent->user): ?>
                    <?php
                        $tsUser  = $topStudent->user;
                        $tsRep   = $topStudent->reputationScore;
                        $tsScore = $tsRep ? round($tsRep->overall_score) : 50;
                        $tsInitial = strtoupper(substr($tsUser->name, 0, 1));
                        $tsName  = $tsUser->name;
                        $tsNameParts = explode(' ', $tsName);
                        $tsShort = $tsNameParts[0] . (isset($tsNameParts[1]) ? ' ' . substr($tsNameParts[1], 0, 1) . '.' : '');
                        $tsCollege = $topStudent->college_name ?? 'Student';
                        $tsYear  = $topStudent->graduation_year ?? '';
                        $tsRole  = $topStudent->preferred_role ?? $topStudent->primary_domain ?? 'Student';
                        $tsTrust = $tsRep ? round($tsRep->trust_score) : 50;
                        $tsOnTime = $tsRep ? round($tsRep->on_time_rate) : 50;
                        $tsComm  = $tsRep ? round($tsRep->communication_rating) : 50;
                        $tsSat   = $tsRep ? round($tsRep->satisfaction_rating) : 50;
                        $tsProjects = $tsRep ? $tsRep->total_verified_projects : 0;
                        $tsSkills = $topStudent->skills ? $topStudent->skills->take(2) : collect();
                        $tsExtraSkills = $topStudent->skills ? max(0, $topStudent->skills->count() - 2) : 0;
                        $tsLevel = $tsScore >= 90 ? 'Elite' : ($tsScore >= 75 ? 'Pro' : ($tsScore >= 50 ? 'Rising' : 'Starter'));
                        $tsVerified = $topStudent->is_verified;
                    ?>
                    <div class="ig-card-dark p-6 relative" data-tilt>
                        <div class="flex items-center justify-between mb-5">
                            <span class="ig-eyebrow" style="color:#C9C1AE">Live · Talent Profile</span>
                            <?php if($tsVerified): ?><span class="ig-chip ig-chip-lime">VERIFIED</span><?php else: ?><span class="ig-chip" style="border-color:#3a3f47;color:#9C9580">UNVERIFIED</span><?php endif; ?>
                        </div>

                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-14 h-14 rounded-2xl bg-[var(--ig-accent)] flex items-center justify-center ig-display text-2xl text-white"><?php echo e($tsInitial); ?></div>
                            <div>
                                <p class="ig-display text-xl text-white leading-tight"><?php echo e($tsShort); ?></p>
                                <p class="ig-mono text-[11px]" style="color:#9C9580"><?php echo e($tsCollege); ?><?php echo e($tsYear ? " · '" . substr($tsYear, -2) : ''); ?></p>
                            </div>
                        </div>

                        <!-- IPRS gauge -->
                        <div class="bg-black/30 rounded-2xl p-4 mb-4 border border-white/5">
                            <div class="flex justify-between items-end mb-2">
                                <span class="ig-eyebrow" style="color:#9C9580">IPRS Score</span>
                                <span class="ig-mono text-[10px]" style="color:#9C9580">out of 100</span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="ig-stat-num text-5xl text-[var(--ig-lime)]" data-counter="<?php echo e($tsScore); ?>">0</span>
                                <span class="text-sm" style="color:#C9C1AE">· <?php echo e($tsLevel); ?></span>
                            </div>
                            <div class="mt-3 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-[var(--ig-lime)] rounded-full" style="width:0;animation:igFill 1.6s .8s var(--ease-out) forwards"></div>
                            </div>
                        </div>

                        <!-- Metrics -->
                        <div class="grid grid-cols-2 gap-2 text-[12px]">
                            <div class="flex justify-between"><span style="color:#9C9580">On-time</span><span class="text-white font-semibold"><?php echo e($tsOnTime); ?>%</span></div>
                            <div class="flex justify-between"><span style="color:#9C9580">Trust</span><span class="text-white font-semibold"><?php echo e($tsTrust); ?>%</span></div>
                            <div class="flex justify-between"><span style="color:#9C9580">Comm.</span><span class="text-white font-semibold"><?php echo e($tsComm); ?>%</span></div>
                            <div class="flex justify-between"><span style="color:#9C9580">Projects</span><span class="text-white font-semibold"><?php echo e($tsProjects); ?></span></div>
                        </div>

                        <div class="mt-5 pt-5 border-t border-white/10 flex items-center justify-between">
                            <div class="flex gap-1.5">
                                <?php $__currentLoopData = $tsSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="ig-tag" style="border-color:#3a3f47;color:#C9C1AE"><?php echo e($skill->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($tsExtraSkills > 0): ?>
                                    <span class="ig-tag" style="border-color:#3a3f47;color:#C9C1AE">+<?php echo e($tsExtraSkills); ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="ig-mono text-[10px]" style="color:#9C9580">#1 Leaderboard</span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="ig-card-dark p-6 relative" data-tilt>
                        <div class="flex items-center justify-between mb-5">
                            <span class="ig-eyebrow" style="color:#C9C1AE">Be the first!</span>
                        </div>
                        <p class="text-white ig-display text-xl">No students yet</p>
                        <p class="text-sm mt-2" style="color:#9C9580">Sign up and become the first on our leaderboard.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <style>@keyframes igFill { to { width: <?php echo e($topStudent && $topStudent->reputationScore ? round($topStudent->reputationScore->overall_score) : 50); ?>%; } }</style>
    </section>

    <!-- ─── MARQUEE (Real startups from DB) ─── -->
    <?php if(count($startupNames) > 0): ?>
    <section class="border-y border-[var(--ig-line)] bg-[var(--ig-bg-2)] py-7 overflow-hidden">
        <div class="ig-container mb-4">
            <p class="ig-eyebrow">Startups hiring student talent on InternGrowth</p>
        </div>
        <div class="overflow-hidden">
            <div class="ig-marquee">
                <?php
                    // Duplicate the list so the marquee scrolls seamlessly
                    $marqueeNames = array_merge($startupNames, $startupNames, $startupNames);
                ?>
                <?php $__currentLoopData = $marqueeNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="ig-display text-3xl md:text-4xl text-[var(--ig-ink-2)] opacity-70 hover:opacity-100 hover:text-[var(--ig-accent)] transition cursor-default"><?php echo e($b); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ─── HOW IT WORKS ─── -->
    <section id="how" class="ig-container py-24 md:py-32">
        <div class="ig-section-head ig-reveal">
            <div>
                <p class="ig-eyebrow mb-3">— 01 / How it works</p>
                <h2 class="ig-display text-4xl md:text-6xl max-w-3xl">A receipt for every piece of work you ship.</h2>
            </div>
            <a href="<?php echo e(route('register')); ?>" class="hidden md:inline-flex ig-btn ig-btn-ghost"><span>Try the flow</span><span class="arrow">→</span></a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php $steps = [
                ['01','Pick a real task','Browse verified startup tasks — design, code, research, growth. Apply in one click.', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2'],
                ['02','Ship & get verified','Submit your work. Founders review, leave feedback, and verify completion on-chain of trust.', 'M5 13l4 4L19 7'],
                ['03','Grow your IPRS','Every verified task lifts your reputation score. Startups discover you. Offers come to your inbox.', 'M13 7l5 5m0 0l-5 5m5-5H6'],
            ]; ?>
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="ig-card p-8 ig-reveal" data-reveal-delay="<?php echo e($i * 120); ?>">
                    <div class="flex items-center justify-between mb-12">
                        <span class="ig-mono text-[11px] text-[var(--ig-muted)]"><?php echo e($s[0]); ?></span>
                        <svg class="w-5 h-5 text-[var(--ig-ink-2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="<?php echo e($s[3]); ?>"/></svg>
                    </div>
                    <h3 class="ig-display text-3xl mb-3"><?php echo e($s[1]); ?></h3>
                    <p class="text-sm text-[var(--ig-ink-2)] leading-relaxed"><?php echo e($s[2]); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = [
                        ['Trust score','Cross-verified by founders'],
                        ['Completion rate','Did you finish what you started'],
                        ['On-time delivery','Shipped before deadline'],
                        ['Startup satisfaction','1–5 rating per project'],
                        ['Communication','Replies, clarity, ownership'],
                        ['Interview perf.','Conversion of intros to offers'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ig-card p-4">
                            <p class="text-sm font-semibold"><?php echo e($m[0]); ?></p>
                            <p class="text-[11.5px] text-[var(--ig-muted)] mt-1"><?php echo e($m[1]); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <a href="<?php echo e(route('register')); ?>" class="ig-btn ig-btn-primary"><span>Build my IPRS</span><span class="arrow">→</span></a>
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
                                    <?php $iprsPreview = $topStudent && $topStudent->reputationScore ? round($topStudent->reputationScore->overall_score) : 50; ?>
                                    <span data-counter="<?php echo e($iprsPreview); ?>">0</span>
                                </p>
                                <p class="ig-mono text-[11px] mt-1" style="color:#9C9580">out of 100</p>
                            </div>
                            <div class="flex-1 space-y-3 pb-2">
                                <?php
                                    $rep = $topStudent ? $topStudent->reputationScore : null;
                                    $bars = [
                                        ['Trust', $rep ? round($rep->trust_score) : 50],
                                        ['Completion', $rep ? round($rep->completion_rate) : 50],
                                        ['On-time', $rep ? round($rep->on_time_rate) : 50],
                                        ['Communication', $rep ? round($rep->communication_rating) : 50],
                                        ['Satisfaction', $rep ? round($rep->satisfaction_rating) : 50],
                                    ];
                                ?>
                                <?php $__currentLoopData = $bars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <div class="flex justify-between text-[11px] mb-1" style="color:#C9C1AE">
                                            <span><?php echo e($b[0]); ?></span><span class="font-semibold text-white"><?php echo e($b[1]); ?>%</span>
                                        </div>
                                        <div class="h-[3px] rounded-full bg-white/10 overflow-hidden">
                                            <div class="h-full bg-[var(--ig-lime)] rounded-full" style="width:<?php echo e($b[1]); ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-5 flex flex-wrap items-center justify-between gap-3 text-[12px]" style="color:#C9C1AE">
                            <span><?php echo e($rep && $rep->total_verified_projects ? $rep->total_verified_projects : 0); ?> verified projects</span>
                            <span class="ig-mono">Live data</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── Stats (dynamic) ─── -->
    <section class="bg-[var(--ig-surface-ink)] text-white py-20">
        <div class="ig-container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                <?php $__currentLoopData = [
                    [$studentsCount, 'Students on platform', '+'],
                    [$startupsCount, 'Startups hiring', ''],
                    [$totalTasks, 'Tasks posted', ''],
                    [$verifiedTasks, 'Verified submissions', ''],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ig-reveal" data-reveal-delay="<?php echo e($i * 100); ?>">
                        <p class="ig-stat-num text-5xl md:text-6xl"><span data-counter="<?php echo e($st[0]); ?>" data-counter-suffix="<?php echo e($st[2]); ?>">0</span></p>
                        <p class="ig-mono text-[11px] mt-3 opacity-70 uppercase tracking-widest"><?php echo e($st[1]); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = [
                        'Pick paid microtasks that match your skills',
                        'Get founder feedback within 48 hours',
                        'Auto-generated verified experience portfolio records',
                        'A public profile recruiters can\'t fake-check',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $li): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-5 h-5 rounded-full bg-[var(--ig-lime)] flex items-center justify-center flex-shrink-0">
                                <svg width="11" height="11" fill="none" stroke="var(--ig-ink)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-[var(--ig-ink-2)]"><?php echo e($li); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a href="<?php echo e(route('register')); ?>" class="ig-btn ig-btn-primary"><span>Create student account</span><span class="arrow">→</span></a>
            </div>

            <div class="ig-reveal" data-reveal-delay="120">
                <div class="relative">
                    <!-- Real task cards from DB -->
                    <?php $rotations = ['ml-12 rotate-1', '-rotate-1', '-mt-2 ml-12 rotate-1']; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $latestTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $statusChip = match(strtolower($task->status ?? 'open')) {
                                'open' => '<span class="ig-chip ig-chip-accent">OPEN</span>',
                                'in_progress', 'in progress' => '<span class="ig-chip ig-chip-lime">IN PROGRESS</span>',
                                'completed' => '<span class="ig-chip ig-chip-success">COMPLETED</span>',
                                default => '<span class="ig-chip ig-chip-accent">' . strtoupper($task->status ?? 'OPEN') . '</span>',
                            };
                            $companyName = $task->startup->company_name ?? 'Startup';
                            $taskDomain  = $task->domain ?? $task->role ?? '';
                            $taskSkills  = $task->skills->take(2);
                        ?>
                        <div class="ig-card p-6 <?php echo e($i > 0 ? '-mt-2' : ''); ?> <?php echo e($rotations[$i % 3]); ?> ig-tilt">
                            <?php echo $statusChip; ?>

                            <h4 class="ig-display text-2xl mt-3"><?php echo e(Str::limit($task->title, 30)); ?></h4>
                            <p class="text-[var(--ig-muted)] text-sm mt-1"><?php echo e($companyName); ?><?php echo e($taskDomain ? ' · ' . $taskDomain : ''); ?></p>
                            <div class="flex justify-between items-end mt-5">
                                <div class="flex gap-1.5">
                                    <?php $__currentLoopData = $taskSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="ig-tag"><?php echo e($skill->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php if($task->stipend > 0): ?>
                                    <p class="ig-display text-xl">₹<?php echo e(number_format($task->stipend)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="ig-card p-6 ig-tilt">
                            <span class="ig-chip ig-chip-accent">COMING SOON</span>
                            <h4 class="ig-display text-2xl mt-3">Tasks loading...</h4>
                            <p class="text-[var(--ig-muted)] text-sm mt-1">Real startup tasks will appear here</p>
                        </div>
                    <?php endif; ?>
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
                    <a href="<?php echo e(route('register')); ?>" class="ig-magnetic"><span class="ig-btn ig-btn-lime"><span>Get started · free</span><span class="arrow">→</span></span></a>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="ig-btn" style="background:transparent;color:white;border:1px solid rgba(255,255,255,.2)">
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
                        <li><a href="<?php echo e(route('tasks.index')); ?>">Marketplace</a></li>
                        <li><a href="<?php echo e(route('leaderboard')); ?>">Leaderboard</a></li>
                        <li><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <?php if(auth()->guard()->check()): ?><li><a href="<?php echo e(route('report.show')); ?>">Report Issue</a></li><?php endif; ?>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>
                        <li><a href="<?php echo e(route('privacy')); ?>">Privacy</a></li>
                        <li><a href="<?php echo e(route('terms')); ?>">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pt-8 border-t border-white/10">
                <p class="ig-mono text-[11px] text-white/50">© <?php echo e(date('Y')); ?> InternGrowth · Made for students who ship.</p>
                <p class="ig-mono text-[11px] text-white/50 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[var(--ig-lime)] animate-pulse"></span>
                    Status: All systems operational
                </p>
            </div>
        </div>
    </footer>

    <script src="<?php echo e(asset('js/interngrowth.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/welcome.blade.php ENDPATH**/ ?>