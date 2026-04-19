@php
$nav = [
    ['route' => 'dashboard', 'key' => 'nav.dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>'],
    ['route' => 'lessons',   'key' => 'nav.lessons',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>'],
    ['route' => 'groups',    'key' => 'nav.groups',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>'],
    ['route' => 'teachers',  'key' => 'nav.teachers',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>'],
    ['route' => 'students',  'key' => 'nav.students',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>'],
];
@endphp

{{-- Overlay (mobile) --}}
<div
    x-show="$store.ui.sidebarOpen"
    x-transition:enter="transition-opacity duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="$store.ui.toggleSidebar()"
    class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"
    style="display:none"
></div>

{{-- Sidebar --}}
<aside
    :class="$store.ui.sidebarOpen ? 'translate-x-0' : 'ltr:-translate-x-full rtl:translate-x-full'"
    class="fixed top-0 start-0 z-40 h-screen w-sidebar
           flex flex-col
           bg-white dark:bg-navy-900
           border-e border-slate-100 dark:border-navy-700
           transition-transform duration-300 ease-in-out
           shadow-card-md lg:shadow-none"
>
    {{-- Logo --}}
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100 dark:border-navy-700 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-sm group-hover:shadow-glow-sm transition-shadow">
                <span class="text-white text-sm font-bold font-arabic">إ</span>
            </div>
            <div class="leading-none">
                <span class="block text-base font-bold text-emerald-700 dark:text-emerald-400 font-arabic">إلمورا</span>
                <span class="block text-[10px] text-slate-400 dark:text-slate-500 tracking-wide uppercase mt-0.5">Ilmora</span>
            </div>
        </a>
        {{-- Close button (mobile) --}}
        <button @click="$store.ui.toggleSidebar()"
                class="lg:hidden w-7 h-7 flex items-center justify-center rounded-lg
                       text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                       hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
        @foreach($nav as $item)
            <a
                href="{{ route($item['route']) }}"
                @click="$store.ui.closeSidebarOnMobile()"
                @class([
                    'nav-item',
                    'nav-item-active' => request()->routeIs($item['route']),
                    'nav-item-default' => !request()->routeIs($item['route']),
                ])
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    {!! $item['icon'] !!}
                </svg>
                <span>{{ __($item['key']) }}</span>

                @if(request()->routeIs($item['route']))
                    <div class="ms-auto w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- User section --}}
    <div class="border-t border-slate-100 dark:border-navy-700 p-4 flex-shrink-0">
        @auth
        <div class="flex items-center gap-3 mb-3">
            <x-avatar :name="auth()->user()->name" size="sm" />
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        {{-- Language switcher --}}
        @php
            $languages = [
                'ar' => 'العربية',
                'en' => 'English',
                'de' => 'Deutsch',
                'fr' => 'Français',
                'tr' => 'Türkçe',
                'ms' => 'Melayu',
                'ur' => 'اردو',
            ];
            $currentLocale = app()->getLocale();
        @endphp
        <div x-data="{ open: false }" class="mb-2">
            <button @click="open = !open"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm
                           text-slate-500 dark:text-slate-400
                           hover:bg-slate-100 dark:hover:bg-navy-700
                           hover:text-slate-700 dark:hover:text-slate-200
                           transition-colors duration-150">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253M3 12a8.96 8.96 0 0 1 .284-2.253"/>
                </svg>
                <span class="flex-1 text-start">{{ $languages[$currentLocale] ?? $currentLocale }}</span>
                <svg class="w-3 h-3 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="mt-1 rounded-xl border border-slate-100 dark:border-navy-700
                        bg-slate-50 dark:bg-navy-800 py-1"
                 style="display:none">
                @foreach($languages as $code => $label)
                    <a href="{{ route('locale.switch', $code) }}"
                       class="flex items-center gap-2 px-3 py-1.5 text-sm transition-colors duration-100
                              {{ $code === $currentLocale
                                  ? 'text-emerald-600 dark:text-emerald-400 font-medium'
                                  : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-navy-700' }}">
                        <span class="flex-1">{{ $label }}</span>
                        @if($code === $currentLocale)
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm
                           text-slate-500 dark:text-slate-400
                           hover:bg-red-50 dark:hover:bg-red-900/20
                           hover:text-red-600 dark:hover:text-red-400
                           transition-colors duration-150">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                </svg>
                {{ __('nav.logout') }}
            </button>
        </form>
        @endauth
    </div>
</aside>
