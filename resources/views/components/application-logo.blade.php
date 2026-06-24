<div {{ $attributes->merge(['class' => 'flex items-center gap-2 group/logo']) }}>
    <style>
        .group\/logo {
            --logo-height: 2.25rem;
            height: var(--logo-height);
        }
        .group\/logo.h-5 { --logo-height: 1.25rem; }
        .group\/logo.h-6 { --logo-height: 1.5rem; }
        .group\/logo.h-7 { --logo-height: 1.75rem; }
        .group\/logo.h-8 { --logo-height: 2rem; }
        .group\/logo.h-9 { --logo-height: 2.25rem; }
        .group\/logo.h-10 { --logo-height: 2.5rem; }
        .group\/logo.h-12 { --logo-height: 3rem; }
        .group\/logo.h-16 { --logo-height: 4rem; }

        .group\/logo .logo-svg {
            height: calc(var(--logo-height) * 0.85);
            width: calc(var(--logo-height) * 0.85);
        }
        .group\/logo .logo-text {
            font-size: calc(var(--logo-height) * 0.66);
        }
        .group\/logo .logo-text-serif {
            font-size: calc(var(--logo-height) * 0.75);
        }
    </style>
    <!-- SVG Icon (Three Capsules representing Intern -> Growth) -->
    <svg viewBox="0 0 32 32" class="logo-svg flex-shrink-0" style="overflow: visible;" fill="none" xmlns="http://www.w3.org/2000/svg">
        <style>
            .logo-bar {
                transform-box: fill-box;
                transform-origin: bottom;
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), fill 0.3s ease;
            }
            .logo-bar-1 { fill: #5C6470; }
            .logo-bar-2 { fill: currentColor; }
            .logo-bar-3 { fill: var(--ig-accent, #FF4F19); }

            .group\/logo:hover .logo-bar-1 { transform: translateY(-3px); transition-delay: 0s; }
            .group\/logo:hover .logo-bar-2 { transform: translateY(-4px); transition-delay: 0.03s; }
            .group\/logo:hover .logo-bar-3 { transform: translateY(-3px); transition-delay: 0.06s; }
        </style>
        <!-- Bar 1 (Short) -->
        <rect class="logo-bar logo-bar-1" x="3" y="19" width="6" height="10" rx="3" />
        <!-- Bar 2 (Medium) -->
        <rect class="logo-bar logo-bar-2" x="13" y="11" width="6" height="18" rx="3" />
        <!-- Bar 3 (Tall) -->
        <rect class="logo-bar logo-bar-3" x="23" y="3" width="6" height="26" rx="3" />
    </svg>

    <!-- Wordmark using HTML/CSS for absolute typography precision and zero SVG font-fallback issues -->
    <span class="logo-text ig-display tracking-tight font-extrabold text-current select-none whitespace-nowrap leading-none flex items-baseline">
        Intern<span class="logo-text-serif ig-serif text-[var(--ig-accent)] font-normal italic ml-0.5">Growth</span>
    </span>
</div>
