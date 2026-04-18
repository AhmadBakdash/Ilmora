<div class="space-y-6 animate-fade-in" x-data="{ search: '' }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ __('nav.students') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ __('students.subtitle', ['count' => $students->count()]) }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input x-model="search" type="search"
                       placeholder="{{ __('students.search') }}"
                       class="field ps-9 pe-4 py-2 w-44 sm:w-56">
            </div>
            <button wire:click="$set('showForm', true)"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                           text-white text-sm font-semibold shadow-sm hover:shadow-glow-sm transition-all duration-150 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                {{ __('students.add') }}
            </button>
        </div>
    </div>

    {{-- Registration link banner --}}
    @php $regUrl = route('student.register', ['school' => auth()->user()->school->slug]) @endphp
    <div class="flex items-center gap-3 rounded-xl border border-emerald-200 dark:border-emerald-800/50
                bg-emerald-50/50 dark:bg-emerald-900/10 px-4 py-3 text-sm">
        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
        </svg>
        <span class="text-slate-600 dark:text-slate-400">{{ __('students.register_link_label') }}</span>
        <span class="font-mono text-xs text-emerald-700 dark:text-emerald-400 truncate" dir="ltr">{{ $regUrl }}</span>
        <button type="button"
                x-data
                @click="navigator.clipboard.writeText('{{ $regUrl }}').then(() => { $el.textContent = '✓'; setTimeout(() => $el.textContent = '{{ __('students.btn.copy') }}', 1500) })"
                class="ms-auto flex-shrink-0 text-xs font-medium text-emerald-700 dark:text-emerald-400
                       hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors">
            {{ __('students.btn.copy') }}
        </button>
    </div>

    {{-- Inline form --}}
    @if($showForm)
    <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50
                bg-emerald-50/30 dark:bg-emerald-900/10 p-6 animate-scale-in">
        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-5">
            {{ $editingId ? __('students.edit_title') : __('students.new') }}
        </h3>
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('students.form.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text" class="field">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Age --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('students.form.age') }}
                        <span class="font-normal text-slate-400">({{ __('students.form.optional') }})</span>
                    </label>
                    <input wire:model="age" type="number" min="3" max="99" class="field" dir="ltr">
                    @error('age')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Guardian Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('students.form.guardian_name') }}
                        <span class="font-normal text-slate-400">({{ __('students.form.optional') }})</span>
                    </label>
                    <input wire:model="guardian_name" type="text" class="field">
                    @error('guardian_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Guardian Phone --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('students.form.phone') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="phone" type="tel" class="field" dir="ltr">
                    @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Email (optional) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('students.form.email') }}
                        <span class="font-normal text-slate-400">({{ __('students.form.optional') }})</span>
                    </label>
                    <input wire:model="email" type="email" class="field" dir="ltr">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Password (always optional) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('students.form.password') }}
                        <span class="font-normal text-slate-400">({{ __('students.form.password_keep') }})</span>
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

            {{-- Siblings --}}
            @if($editingId && $students->count() > 1)
            <div class="pt-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('students.form.siblings') }}
                    <span class="font-normal text-slate-400">({{ __('students.form.optional') }})</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($students->where('id', '!=', $editingId) as $other)
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 dark:border-navy-700
                                  bg-white dark:bg-navy-900 px-3 py-2 hover:border-emerald-400 dark:hover:border-emerald-600
                                  transition-colors has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/20">
                        <input type="checkbox" wire:model="siblingIds" value="{{ $other->id }}"
                               class="w-4 h-4 rounded accent-emerald-600">
                        <span class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ $other->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('siblingIds')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            @endif

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold
                               shadow-sm transition-all duration-150 disabled:opacity-60">
                    {{ __('students.btn.save') }}
                </button>
                <button type="button" wire:click="$set('showForm', false)"
                        class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-navy-700 text-slate-600 dark:text-slate-300
                               hover:bg-slate-200 dark:hover:bg-navy-600 text-sm font-medium transition-colors">
                    {{ __('students.btn.cancel') }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Table --}}
    @if($students->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300">{{ __('students.empty') }}</h3>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1 mb-5">{{ __('students.empty_hint') }}</p>
        <button wire:click="$set('showForm', true)"
                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-all">
            {{ __('students.add') }}
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
                        {{ __('students.col.name') }}
                    </th>
                    <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                        {{ __('students.col.guardian') }}
                    </th>
                    <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">
                        {{ __('students.col.siblings') }}
                    </th>
                    <th class="px-5 py-3.5 text-end text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        {{ __('students.col.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-navy-800">
                @foreach($students as $student)
                <tr x-show="!search || '{{ strtolower($student->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($student->phone ?? '') }}'.includes(search.toLowerCase())"
                    class="hover:bg-slate-50/50 dark:hover:bg-navy-800/50 transition-colors duration-100">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @php
                                $words  = explode(' ', trim($student->name));
                                $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
                                $hue = ord($student->name[0] ?? 'A') % 6;
                                $avatarColors = ['bg-emerald-100 text-emerald-700','bg-blue-100 text-blue-700','bg-violet-100 text-violet-700','bg-amber-100 text-amber-700','bg-rose-100 text-rose-700','bg-teal-100 text-teal-700'];
                            @endphp
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $avatarColors[$hue] }}">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">{{ $student->name }}</p>
                                @if($student->guardian_name)
                                <p class="text-xs text-slate-400 dark:text-slate-500 sm:hidden truncate">{{ $student->guardian_name }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 hidden sm:table-cell">
                        @if($student->guardian_name)
                        <div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $student->guardian_name }}</p>
                            @if($student->phone)
                            <p class="text-xs text-slate-400 dark:text-slate-500" dir="ltr">{{ $student->phone }}</p>
                            @endif
                        </div>
                        @else
                        <span class="text-sm text-slate-300 dark:text-slate-600">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 hidden md:table-cell">
                        @if($student->siblings->isNotEmpty())
                            <div class="flex flex-wrap gap-1">
                                @foreach($student->siblings as $sibling)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                             bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $sibling->name }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-slate-300 dark:text-slate-600">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="edit({{ $student->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                           text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                                           hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $student->id }})"
                                    wire:confirm="{{ __('students.confirm_delete') }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                           text-slate-400 hover:text-red-600 dark:hover:text-red-400
                                           hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- No results after filter --}}
        <div x-show="search && !document.querySelectorAll('tbody tr[style!=\'display: none;\']').length"
             class="px-5 py-8 text-center text-sm text-slate-400 dark:text-slate-500">
            {{ __('students.no_results') }}
        </div>
    </div>
    @endif

</div>
