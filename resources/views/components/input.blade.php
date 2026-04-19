@props([
    'label'    => null,
    'name'     => null,
    'type'     => 'text',
    'error'    => null,
    'hint'     => null,
    'required' => false,
])

<div>
    @if($label)
        <label
            @if($name) for="{{ $name }}" @endif
            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5"
        >
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea
            {{ $attributes->merge(['class' => 'field ' . ($error ? 'border-red-400 dark:border-red-500 focus:ring-red-400/30 focus:border-red-400' : '')]) }}
        >{{ $slot }}</textarea>
    @elseif($type === 'select')
        <div class="relative">
            <select {{ $attributes->merge(['class' => 'field appearance-none pe-9 ' . ($error ? 'border-red-400 dark:border-red-500' : '')]) }}>
                {{ $slot }}
            </select>
            <div class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    @else
        <input
            type="{{ $type }}"
            {{ $attributes->merge(['class' => 'field ' . ($error ? 'border-red-400 dark:border-red-500 focus:ring-red-400/30 focus:border-red-400' : '')]) }}
        >
    @endif

    @if($error)
        <p class="mt-1 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
            <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $error }}
        </p>
    @elseif($hint)
        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">{{ $hint }}</p>
    @endif
</div>
