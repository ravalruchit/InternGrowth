@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[var(--ig-accent)] text-sm font-medium leading-5 text-[var(--ig-ink)] focus:outline-none focus:border-[var(--ig-accent)] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[var(--ig-muted)] hover:text-[var(--ig-ink-2)] hover:border-[var(--ig-line-2)] focus:outline-none focus:text-[var(--ig-ink-2)] focus:border-[var(--ig-line-2)] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
