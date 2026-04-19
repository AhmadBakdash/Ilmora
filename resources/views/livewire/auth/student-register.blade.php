<div class="w-full max-w-lg animate-scale-in">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl
                    bg-gradient-to-br from-emerald-500 to-emerald-700
                    shadow-glow mb-4">
            <span class="text-white text-2xl font-bold font-arabic">إ</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 font-arabic">إلمورا</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $school->name }}</p>
    </div>

    @if($registered)
    {{-- Success state --}}
    <x-card padding="p-8">
        <div class="flex flex-col items-center text-center gap-4">
            <div class="w-14 h-14 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ __('register.success_title') }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('register.success_body') }}</p>
            </div>
        </div>
    </x-card>
    @else
    {{-- Registration form --}}
    <x-card padding="p-8">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-1">{{ __('register.title') }}</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">{{ __('register.subtitle') }}</p>

        <form wire:submit="register" class="space-y-5">

            {{-- Student section --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                    {{ __('register.section.student') }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('register.field.name') }} <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="name" type="text" class="field" autocomplete="name">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('register.field.age') }} <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="age" type="number" min="3" max="99" class="field" dir="ltr">
                        @error('age')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-navy-700"></div>

            {{-- Guardian section --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">
                    {{ __('register.section.guardian') }}
                </p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('register.field.guardian_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="guardian_name" type="text" class="field">
                        @error('guardian_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('register.field.phone') }} <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="phone" type="tel" class="field" dir="ltr" autocomplete="tel">
                        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('register.field.email') }}
                            <span class="font-normal text-slate-400">({{ __('students.form.optional') }})</span>
                        </label>
                        <input wire:model="email" type="email" class="field" dir="ltr" autocomplete="email">
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl
                           bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800
                           text-white text-sm font-semibold shadow-sm hover:shadow-glow-sm
                           transition-all duration-150 disabled:opacity-60 disabled:cursor-not-allowed">
                <span wire:loading.remove>{{ __('register.btn.submit') }}</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ __('register.btn.submitting') }}
                </span>
            </button>

        </form>
    </x-card>
    @endif

    <div class="mt-6 flex justify-center">
        <x-theme-toggle />
    </div>

</div>
