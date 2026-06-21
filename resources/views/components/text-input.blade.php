@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[var(--ig-line-2)] focus:border-[var(--ig-ink)] focus:ring-[var(--ig-ink)]/10 rounded-md shadow-sm']) }}>
