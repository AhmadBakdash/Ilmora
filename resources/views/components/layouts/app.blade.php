<!DOCTYPE html>
<html
    x-data
    x-init="$store.ui.applyDark()"
    dir="{{ in_array(app()->getLocale(), ['ar', 'ur']) ? 'rtl' : 'ltr' }}"
    lang="{{ app()->getLocale() }}"
    class="h-full"
>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إلمورا — {{ config('app.name', 'Ilmora') }}</title>
    <meta name="description" content="منصة إدارة مدارس القرآن الكريم">

    {{-- Preconnect for Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=Amiri:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Apply dark mode before render to avoid flash --}}
    <script>
        (function() {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-cream-50 dark:bg-navy-950 antialiased">

@auth
{{-- ─── Authenticated layout: sidebar + topbar ─────────────────── --}}
<div class="flex h-full">

    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Main area — shifts right of sidebar on desktop --}}
    <div
        class="flex flex-col flex-1 min-h-screen min-w-0 transition-all duration-300"
        :class="$store.ui.sidebarOpen ? 'lg:ms-sidebar' : ''"
    >

        {{-- ─── Top Bar ─────────────────────────────────────────── --}}
        @php
            $pageTitle = match(true) {
                request()->routeIs('dashboard') => __('nav.dashboard'),
                request()->routeIs('lessons')   => __('nav.lessons'),
                request()->routeIs('groups')    => __('nav.groups'),
                request()->routeIs('teachers')  => __('nav.teachers'),
                request()->routeIs('students')  => __('nav.students'),
                default                         => config('app.name', 'Ilmora'),
            };
            $schoolName = auth()->user()->school->name ?? config('app.name', 'Ilmora');
        @endphp

        <header class="h-16 flex-shrink-0 flex items-center justify-between
                        px-4 lg:px-6
                        bg-white/80 dark:bg-navy-900/80
                        backdrop-blur-md
                        border-b border-slate-100 dark:border-navy-700
                        sticky top-0 z-20">

            {{-- Start: hamburger + title --}}
            <div class="flex items-center gap-3">
                <button @click="$store.ui.toggleSidebar()"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-slate-500 dark:text-slate-400
                               hover:bg-slate-100 dark:hover:bg-navy-700
                               hover:text-slate-700 dark:hover:text-slate-200
                               transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                <div class="hidden sm:block">
                    <h1 class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $pageTitle }}</h1>
                    <p class="text-xs text-slate-400 dark:text-slate-500 leading-none mt-0.5">{{ $schoolName }}</p>
                </div>
            </div>

            {{-- End: notifications + theme + user --}}
            <div class="flex items-center gap-1">

                {{-- Notification bell (placeholder) --}}
                <button class="w-9 h-9 flex items-center justify-center rounded-xl
                               text-slate-500 dark:text-slate-400
                               hover:bg-slate-100 dark:hover:bg-navy-700
                               hover:text-slate-700 dark:hover:text-slate-200
                               transition-colors duration-150 relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                    </svg>
                </button>

                {{-- Theme toggle --}}
                <x-theme-toggle />

                {{-- Divider --}}
                <div class="w-px h-6 bg-slate-200 dark:bg-navy-700 mx-1"></div>

                {{-- User chip --}}
                <div class="flex items-center gap-2 ps-1">
                    <x-avatar :name="auth()->user()->name" size="sm" />
                    <span class="hidden md:block text-sm font-medium text-slate-700 dark:text-slate-200 max-w-[120px] truncate">
                        {{ auth()->user()->name }}
                    </span>
                </div>
            </div>
        </header>

        {{-- ─── Page content ──────────────────────────────────── --}}
        <main class="flex-1 overflow-auto">
            <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 py-6">
                {{ $slot }}
            </div>
        </main>

    </div>{{-- /main area --}}
</div>{{-- /flex --}}

@else
{{-- ─── Guest layout: centered ─────────────────────────────────── --}}
<div class="min-h-screen flex flex-col items-center justify-center p-4
            bg-gradient-to-br from-cream-50 via-white to-emerald-50/30
            dark:from-navy-950 dark:via-navy-900 dark:to-navy-800
            bg-mesh-pattern">
    {{ $slot }}
</div>
@endauth

</body>
</html>
