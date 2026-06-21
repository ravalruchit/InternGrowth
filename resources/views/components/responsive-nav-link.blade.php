@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[var(--ig-accent)] text-start text-base font-medium text-[var(--ig-accent)] bg-[var(--ig-accent-soft)]/20 focus:outline-none focus:text-[var(--ig-accent)] focus:bg-[var(--ig-accent-soft)]/40 focus:border-[var(--ig-accent)] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[var(--ig-muted)] hover:text-[var(--ig-ink)] hover:bg-[var(--ig-bg-2)] hover:border-[var(--ig-line-2)] focus:outline-none focus:text-[var(--ig-ink)] focus:bg-[var(--ig-bg-2)] focus:border-[var(--ig-line-2)] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
