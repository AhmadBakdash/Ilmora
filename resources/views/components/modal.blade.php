@props([
    'id'       => 'modal',
    'maxWidth' => 'max-w-2xl',
    'show'     => false,
    'closeable'=> true,
])

{{-- This is a Blade-only presentational modal (non-Livewire).
     For Livewire modals, the overlay + close are handled in the parent view. --}}

<div
    x-data="{ open: @js($show) }"
    x-show="open"
    x-on:open-modal.window="if($event.detail === '{{ $id }}') open = true"
    x-on:close-modal.window="open = false"
    x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        @if($closeable) @click="open = false" @endif
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
    ></div>

    {{-- Content --}}
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div
            x-transition:enter="transition duration-300 ease-spring"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full {{ $maxWidth }} bg-white dark:bg-navy-800 rounded-2xl shadow-card-lg dark:shadow-dark-card overflow-hidden"
        >
            {{ $slot }}
        </div>
    </div>
</div>
