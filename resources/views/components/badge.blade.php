@props([
    'type' => 'default', // hifz | murajaah | tilawah | present | absent | late | admin | teacher | scheduled | cancelled | default
])

@php
$styles = [
    'hifz'      => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'murajaah'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
    'tilawah'   => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300',
    'present'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'absent'    => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    'late'      => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
    'admin'     => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
    'teacher'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
    'scheduled' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    'assigned'  => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'default'   => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
];
@endphp

<span {{ $attributes->merge([
    'class' => 'badge ' . ($styles[$type] ?? $styles['default']),
]) }}>
    {{ $slot }}
</span>
