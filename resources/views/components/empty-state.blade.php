@props([
    'icon'        => null,
    'title'       => 'لا توجد بيانات',
    'description' => null,
    'action'      => null,
    'actionLabel' => null,
    'actionHref'  => null,
])

<div class="flex flex-col items-center justify-center py-16 px-6 text-center animate-fade-in">
    {{-- Icon --}}
    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-navy-700 flex items-center justify-center mb-5">
        @if($icon)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5"
                 class="w-8 h-8 text-slate-400 dark:text-slate-500">
                {!! $icon !!}
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5"
                 class="w-8 h-8 text-slate-400 dark:text-slate-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
        @endif
    </div>

    <h3 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-1">{{ $title }}</h3>

    @if($description)
        <p class="text-sm text-slate-400 dark:text-slate-500 max-w-xs mb-5">{{ $description }}</p>
    @endif

    @if($action)
        {{ $action }}
    @elseif($actionLabel && $actionHref)
        <a href="{{ $actionHref }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium
                  bg-emerald-600 hover:bg-emerald-700 text-white transition-colors duration-150">
            {{ $actionLabel }}
        </a>
    @endif
</div>
