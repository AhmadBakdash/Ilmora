@props([
    'name' => 'User',
    'size' => 'md',  // sm | md | lg | xl
    'src'  => null,
])

@php
$sizes = [
    'sm' => 'w-7 h-7 text-xs',
    'md' => 'w-9 h-9 text-sm',
    'lg' => 'w-11 h-11 text-base',
    'xl' => 'w-14 h-14 text-lg',
];

// Pick a consistent color based on first letter
$colors = [
    'bg-emerald-500', 'bg-amber-500', 'bg-violet-500',
    'bg-rose-500', 'bg-sky-500', 'bg-orange-500',
    'bg-teal-500', 'bg-pink-500', 'bg-indigo-500',
];
$colorClass = $colors[ord(strtolower($name[0] ?? 'u')) % count($colors)];

// Initials: first letter of each word, max 2
$words    = array_filter(explode(' ', $name));
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice($words, 0, 2))));
@endphp

<div {{ $attributes->merge([
    'class' => implode(' ', [
        $sizes[$size] ?? $sizes['md'],
        'rounded-full flex-shrink-0 flex items-center justify-center font-semibold text-white select-none',
        $src ? 'overflow-hidden' : $colorClass,
    ]),
]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="w-full h-full object-cover">
    @else
        {{ $initials }}
    @endif
</div>
