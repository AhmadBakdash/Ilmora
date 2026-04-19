@props([
    'padding' => 'p-6',
    'hover'   => false,
])

<div {{ $attributes->merge([
    'class' => implode(' ', array_filter([
        'bg-white dark:bg-navy-800 rounded-2xl border border-slate-100 dark:border-navy-700 shadow-card dark:shadow-dark-card',
        $padding,
        $hover ? 'transition-all duration-200 hover:shadow-card-md hover:-translate-y-0.5 cursor-pointer' : '',
    ])),
]) }}>
    {{ $slot }}
</div>
