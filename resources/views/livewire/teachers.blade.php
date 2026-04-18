<div class="space-y-6 animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ __('nav.teachers') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ __('teachers.subtitle', ['count' => $teachers->count()]) }}
            </p>
        </div>
        @unless($showForm)
        <button wire:click="create"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                       text-white text-sm font-semibold shadow-sm hover:shadow-glow-sm transition-all duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            {{ __('teachers.add') }}
        </button>
        @endunless
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20
                border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20
                border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Inline form --}}
    @if($showForm)
    <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50
                bg-emerald-50/30 dark:bg-emerald-900/10 p-6 animate-scale-in">
        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-5">
            {{ $editingId ? __('teachers.edit_title') : __('teachers.new') }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('teachers.form.name') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="name" type="text" class="field">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('teachers.form.email') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="email" type="email" class="field" dir="ltr">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('teachers.form.password') }}
                    @if($editingId)
                        <span class="font-normal text-slate-400 dark:text-slate-500">({{ __('teachers.form.password_hint') }})</span>
                    @else
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input wire:model="password" :type="show ? 'text' : 'password'" class="field pe-10" dir="ltr">
                    <button type="button" @click="show = !show" tabindex="-1"
                            class="absolute end-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                </div>
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex items-center gap-3 mt-5">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold
                           shadow-sm transition-all duration-150 disabled:opacity-60">
                {{ $editingId ? __('teachers.btn.update') : __('teachers.btn.create') }}
            </button>
            <button wire:click="cancel"
                    class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-navy-700 text-slate-600 dark:text-slate-300
                           hover:bg-slate-200 dark:hover:bg-navy-600 text-sm font-medium transition-colors">
                {{ __('teachers.btn.cancel') }}
            </button>
        </div>
    </div>
    @endif

    {{-- Teachers table --}}
    @if($teachers->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300">{{ __('teachers.empty') }}</h3>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1 mb-5">{{ __('teachers.empty_hint') }}</p>
        <button wire:click="create"
                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-all">
            {{ __('teachers.add') }}
        </button>
    </div>
    @else
    <div class="rounded-2xl bg-white dark:bg-navy-900
                border border-slate-100 dark:border-navy-700
                shadow-card overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-navy-700">
                    <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        {{ __('teachers.col.name') }}
                    </th>
                    <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">
                        {{ __('teachers.col.email') }}
                    </th>
                    <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                        {{ __('teachers.col.role') }}
                    </th>
                    <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">
                        {{ __('teachers.col.groups') }}
                    </th>
                    <th class="px-5 py-3.5 text-end text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        {{ __('teachers.col.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-navy-800">
                @foreach($teachers as $teacher)
                @php
                    $words    = explode(' ', trim($teacher->name));
                    $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
                    $hue      = ord($teacher->name[0] ?? 'A') % 6;
                    $avColors = ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                                 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                                 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-400',
                                 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
                                 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400',
                                 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-400'];
                @endphp
                <tr class="hover:bg-slate-50/50 dark:hover:bg-navy-800/50 transition-colors duration-100">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $avColors[$hue] }}">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">{{ $teacher->name }}</p>
                                    @if($teacher->id === auth()->id())
                                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-md
                                                     bg-emerald-100 dark:bg-emerald-900/40
                                                     text-emerald-700 dark:text-emerald-400">
                                            {{ __('teachers.you') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 md:hidden truncate" dir="ltr">{{ $teacher->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        <span class="text-sm text-slate-500 dark:text-slate-400" dir="ltr">{{ $teacher->email }}</span>
                    </td>
                    <td class="px-5 py-3.5 hidden sm:table-cell">
                        @if($teacher->role === 'school_admin')
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg
                                         bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                                </svg>
                                {{ __('teachers.role.admin') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-lg
                                         bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                                </svg>
                                {{ __('teachers.role.teacher') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 hidden lg:table-cell">
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('teachers.groups_count', ['count' => $teacher->teachingGroups->count()]) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="edit({{ $teacher->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                           text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                                           hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                            </button>
                            @if($teacher->id !== auth()->id())
                            <button wire:click="delete({{ $teacher->id }})"
                                    wire:confirm="{{ __('teachers.confirm_delete', ['name' => $teacher->name]) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                           text-slate-400 hover:text-red-600 dark:hover:text-red-400
                                           hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                            @else
                            <div class="w-8 h-8"></div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
