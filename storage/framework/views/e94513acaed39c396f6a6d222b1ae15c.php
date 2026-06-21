<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'InternGrowth')); ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo e(asset('css/design-system.css')); ?>">
</head>
<body class="ig-body antialiased min-h-screen">

    <!-- ───────── NAV ───────── -->
    <nav class="ig-nav">
        <div class="ig-container">
            <div class="flex items-center justify-between h-[68px]">
                <!-- Brand -->
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 group">
                    <div class="relative w-9 h-9 rounded-xl bg-[var(--ig-ink)] flex items-center justify-center text-[var(--ig-bg)] overflow-hidden">
                        <span class="ig-display text-lg leading-none">IG</span>
                        <span class="absolute inset-0 bg-[var(--ig-accent)] origin-bottom scale-y-0 group-hover:scale-y-100 transition-transform duration-500"></span>
                        <span class="ig-display text-lg leading-none relative z-10">IG</span>
                    </div>
                    <div class="leading-tight">
                        <div class="ig-display text-[19px] tracking-tight">InternGrowth</div>
                        <div class="ig-eyebrow text-[9.5px] -mt-0.5">Verified Work · Real Reputation</div>
                    </div>
                </a>

                <!-- Links -->
                <div class="hidden md:flex items-center gap-7">
                    <a href="<?php echo e(route('dashboard')); ?>" class="ig-nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">Dashboard</a>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="ig-nav-link <?php echo e(request()->routeIs('tasks.index') ? 'active' : ''); ?>">Marketplace</a>
                    <a href="<?php echo e(route('leaderboard')); ?>" class="ig-nav-link <?php echo e(request()->routeIs('leaderboard') ? 'active' : ''); ?>">Leaderboard</a>
                    <?php if(auth()->check() && auth()->user()->isStudent()): ?>
                        <a href="<?php echo e(route('student.analytics')); ?>" class="ig-nav-link <?php echo e(request()->routeIs('student.analytics') ? 'active' : ''); ?>">Analytics</a>
                    <?php endif; ?>
                </div>

                <!-- Right -->
                <div class="flex items-center gap-3">
                    <?php if(auth()->guard()->check()): ?>
                    <!-- Notifications -->
                    <div class="relative" id="notif-wrapper">
                        <button id="notif-btn" onclick="toggleNotifDropdown()" class="relative w-10 h-10 rounded-full border border-[var(--ig-line)] hover:border-[var(--ig-ink)] flex items-center justify-center transition" aria-label="Notifications">
                            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3C7.7 6.2 6 8.4 6 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
                            <span id="notif-badge" class="absolute -top-0.5 -right-0.5 hidden w-4 h-4 rounded-full bg-[var(--ig-accent)] text-white text-[9px] font-bold items-center justify-center">0</span>
                        </button>

                        <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl border border-[var(--ig-line)] shadow-[var(--shadow-lg)] z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-[var(--ig-line)] bg-[var(--ig-bg)]">
                                <span class="ig-eyebrow">Notifications</span>
                                <button onclick="markAllRead()" class="text-[11px] font-semibold text-[var(--ig-accent)] hover:underline">Mark all read</button>
                            </div>
                            <div id="notif-list" class="max-h-80 overflow-y-auto">
                                <div class="px-4 py-8 text-center text-[var(--ig-faint)] text-sm" id="notif-empty">No notifications</div>
                            </div>
                            <div class="px-4 py-3 border-t border-[var(--ig-line)] text-center bg-[var(--ig-bg)]">
                                <a href="<?php echo e(route('notifications.index')); ?>" class="text-[12px] font-semibold text-[var(--ig-ink)] hover:text-[var(--ig-accent)]">View all →</a>
                            </div>
                        </div>
                    </div>

                    <!-- User -->
                    <div class="relative group">
                        <button class="flex items-center gap-2.5 pl-2 pr-3.5 py-1.5 rounded-full border border-[var(--ig-line)] hover:border-[var(--ig-ink)] transition">
                            <span class="ig-avatar text-[12px]" style="width:28px;height:28px;"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></span>
                            <span class="text-[13px] font-medium"><?php echo e(Str::limit(auth()->user()->name, 14)); ?></span>
                            <svg class="w-3.5 h-3.5 text-[var(--ig-muted)] group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-[var(--ig-line)] shadow-[var(--shadow-lg)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                            <?php if(auth()->user()->role === 'student'): ?>
                                <a href="<?php echo e(route('student.profile')); ?>" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[var(--ig-bg)] transition">
                                    <svg class="w-4 h-4 text-[var(--ig-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                            <?php elseif(auth()->user()->role === 'startup'): ?>
                                <a href="<?php echo e(route('startup.profile')); ?>" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[var(--ig-bg)] transition">
                                    <svg class="w-4 h-4 text-[var(--ig-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                    Company Profile
                                </a>
                            <?php endif; ?>
                            <div class="h-px bg-[var(--ig-line)]"></div>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-[var(--ig-rose)] hover:bg-[var(--ig-bg)] transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- ───────── FLASH MESSAGES ───────── -->
    <?php $__currentLoopData = ['success' => ['msg' => session('success'), 'cls' => 'ig-banner-success'],
               'message_sent' => ['msg' => session('message_sent'), 'cls' => 'ig-banner-success'],
               'task_created' => ['msg' => session('task_created'), 'cls' => 'ig-banner'],
               'error' => ['msg' => session('error'), 'cls' => 'ig-banner-warn']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($f['msg']): ?>
            <div id="flash-<?php echo e($key); ?>" class="ig-container mt-6 ig-anim-fade-up">
                <div class="ig-banner <?php echo e($f['cls']); ?> ig-z10">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-sm font-medium flex-1"><?php echo e($f['msg']); ?></p>
                    <button onclick="document.getElementById('flash-<?php echo e($key); ?>').remove()" class="text-[var(--ig-muted)] hover:text-[var(--ig-ink)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- ───────── MAIN ───────── -->
    <main class="ig-z10 py-12">
        <?php echo e($slot); ?>

    </main>

    <!-- ───────── FOOTER ───────── -->
    <footer class="ig-footer relative overflow-hidden mt-24">
        <div class="ig-container py-10 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[var(--ig-ink)] ig-display text-sm font-semibold">IG</div>
                <span class="ig-mono text-[11px] opacity-60">© <?php echo e(date('Y')); ?> InternGrowth · Built for students who ship.</span>
            </div>
            <div class="flex gap-6 text-[12px]">
                <a href="<?php echo e(route('privacy')); ?>">Privacy</a>
                <a href="<?php echo e(route('terms')); ?>">Terms</a>
                <a href="<?php echo e(route('contact')); ?>">Contact</a>
            </div>
        </div>
    </footer>

    <!-- Auto-dismiss flash -->
    <script>
        ['flash-success','flash-message_sent','flash-task_created','flash-error'].forEach(id=>{
            const el=document.getElementById(id);
            if(el){setTimeout(()=>{el.style.transition='opacity .5s';el.style.opacity='0';setTimeout(()=>el.remove(),500)},4500);}
        });
    </script>

    <?php if(auth()->guard()->check()): ?>
    <script>
        const NOTIF_URL = '<?php echo e(route("notifications.unread-count")); ?>';
        const NOTIF_READ_ALL = '<?php echo e(route("notifications.read-all")); ?>';
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let dropdownOpen = false;

        function toggleNotifDropdown() {
            dropdownOpen = !dropdownOpen;
            document.getElementById('notif-dropdown').classList.toggle('hidden', !dropdownOpen);
            if (dropdownOpen) fetchDropdownNotifs();
        }
        document.addEventListener('click', (e) => {
            const w = document.getElementById('notif-wrapper');
            if (w && !w.contains(e.target) && dropdownOpen) {
                dropdownOpen = false;
                document.getElementById('notif-dropdown').classList.add('hidden');
            }
        });
        async function refreshBadge() {
            try {
                const res = await fetch(NOTIF_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                const badge = document.getElementById('notif-badge');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden'); badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden'); badge.classList.remove('flex');
                }
            } catch (e) {}
        }
        async function markAllRead() {
            await fetch(NOTIF_READ_ALL, { method:'POST', headers:{'X-CSRF-TOKEN': CSRF, 'X-Requested-With':'XMLHttpRequest'} });
            refreshBadge(); fetchDropdownNotifs();
        }
        async function fetchDropdownNotifs() {
            try {
                const res = await fetch('<?php echo e(url("/notifications/dropdown")); ?>', { headers: { 'X-Requested-With':'XMLHttpRequest' }});
                if (!res.ok) return;
                const data = await res.json(); renderDropdown(data.notifications);
            } catch(e){}
        }
        function renderDropdown(items) {
            const list = document.getElementById('notif-list');
            if (!items || items.length === 0) {
                list.innerHTML = '<div class="px-4 py-8 text-center text-sm" style="color:var(--ig-faint)">No notifications</div>'; return;
            }
            const icon = { success:'✓', warning:'!', error:'×', info:'·' };
            list.innerHTML = items.map(n => `
                <div class="px-4 py-3 border-b border-[var(--ig-line)] last:border-0 hover:bg-[var(--ig-bg)] cursor-pointer ${n.is_read ? 'opacity-60' : ''}" onclick="markOneRead(${n.id}, this, '${n.target_url || '#'}')">
                    <div class="flex items-start gap-3">
                        <span class="ig-avatar" style="width:22px;height:22px;font-size:11px;">${icon[n.type] || '·'}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-semibold truncate">${n.title}${!n.is_read ? ' <span class="inline-block w-1.5 h-1.5 rounded-full" style="background:var(--ig-accent)"></span>' : ''}</p>
                            <p class="text-[12px] mt-0.5 line-clamp-2" style="color:var(--ig-muted)">${n.message}</p>
                            <p class="ig-mono text-[10px] mt-1" style="color:var(--ig-faint)">${n.time_ago}</p>
                        </div>
                    </div>
                </div>`).join('');
        }
        async function markOneRead(id, el, targetUrl) {
            await fetch(`/notifications/${id}/read`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'} });
            el.classList.add('opacity-60'); refreshBadge();
            if (targetUrl && targetUrl !== '#') {
                window.location.href = targetUrl;
            }
        }
        refreshBadge(); setInterval(refreshBadge, 30000);
    </script>
    <?php endif; ?>

    <script src="<?php echo e(asset('js/interngrowth.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\Raval Ruchit\Desktop\interndesign\InternGrowth\resources\views/layouts/app.blade.php ENDPATH**/ ?>