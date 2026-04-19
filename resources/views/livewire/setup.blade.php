<div class="w-full max-w-lg animate-scale-in">

    {{-- Logo + heading --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl
                    bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-glow mb-4">
            <span class="text-white text-2xl font-bold font-arabic">إ</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 font-arabic">إلمورا</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('setup.tagline') }}</p>
    </div>

    <x-card padding="p-8">

        {{-- Progress indicator --}}
        <div class="flex items-center gap-2 mb-7">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">1</div>
                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">{{ __('setup.create_school') }}</span>
            </div>
            <div class="flex-1 h-px bg-slate-200 dark:bg-navy-600 mx-2"></div>
            <div class="flex items-center gap-2 opacity-40">
                <div class="w-7 h-7 rounded-full border-2 border-slate-300 dark:border-slate-600 text-slate-400 flex items-center justify-center text-xs font-bold">2</div>
                <span class="text-xs font-medium text-slate-400">جاهز</span>
            </div>
        </div>

        <form wire:submit="submit" class="space-y-5">

            {{-- School name --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('setup.school_name') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="school_name" type="text" class="field"
                       placeholder="مدرسة القرآن الكريم">
                @error('school_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Divider --}}
            <div class="flex items-center gap-3 py-1">
                <div class="flex-1 h-px bg-slate-100 dark:bg-navy-700"></div>
                <span class="text-xs text-slate-400 dark:text-slate-500">حساب المشرف</span>
                <div class="flex-1 h-px bg-slate-100 dark:bg-navy-700"></div>
            </div>

            {{-- Admin name --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('setup.admin_name') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="admin_name" type="text" class="field">
                @error('admin_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Admin email --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('setup.admin_email') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="admin_email" type="email" class="field" dir="ltr">
                @error('admin_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Password grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('setup.password') }}
                    </label>
                    <div x-data="{ show: false }" class="relative">
                        <input wire:model="admin_password" :type="show ? 'text' : 'password'" class="field pe-10" dir="ltr">
                        <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute end-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('admin_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('setup.confirm_password') }}
                    </label>
                    <div x-data="{ show: false }" class="relative">
                        <input wire:model="admin_password_confirmation" :type="show ? 'text' : 'password'" class="field pe-10" dir="ltr">
                        <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute end-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl
                       bg-emerald-600 hover:bg-emerald-700
                       text-white text-sm font-semibold
                       shadow-sm hover:shadow-glow-sm
                       transition-all duration-150
                       focus:outline-none focus:ring-2 focus:ring-emerald-500/40
                       disabled:opacity-60 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove>{{ __('setup.submit') }}</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    جاري الإنشاء…
                </span>
            </button>

        </form>
    </x-card>

    <div class="mt-6 flex justify-center">
        <x-theme-toggle />
    </div>
</div>
