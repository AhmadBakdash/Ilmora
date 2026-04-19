@props([
    'variant' => 'primary',  // primary | secondary | danger | ghost
    'size'    => 'md',        // sm | md | lg
    'icon'    => null,
])

@php
$variants = [
    'primary'   => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow-glow-sm focus:ring-emerald-500/40',
    'secondary' => 'bg-white dark:bg-navy-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-navy-600 focus:ring-slate-300/40',
    'danger'    => 'bg-red-600 hover:bg-red-700 text-white shadow-sm focus:ring-red-500/40',
    'ghost'     => 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-navy-700 focus:ring-slate-300/40',
];
$sizes = [
    'sm' => 'px-3 py-1.5 text-xs gap-1.5',
    'md' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-5 py-2.5 text-sm gap-2.5',
];
@endphp

<button {{ $attributes->merge([
    'class' => implode(' ', [
        'inline-flex items-center justify-center rounded-xl font-medium',
        'transition-all duration-150',
        'focus:outline-none focus:ring-2',
        'disabled:opacity-50 disabled:cursor-not-allowed',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size]    ?? $sizes['md'],
    ]),
]) }}>
    @if($icon)
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             class="{{ $size === 'sm' ? 'w-3.5 h-3.5' : 'w-4 h-4' }}">
            {!! $icon !!}
        </svg>
    @endif
    {{ $slot }}
</button>
