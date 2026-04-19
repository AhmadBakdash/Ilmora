<div class="space-y-6 animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ __('nav.groups') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ __('groups.subtitle', ['count' => $groups->count()]) }}
            </p>
        </div>
        <button wire:click="$set('showForm', true)"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                       text-white text-sm font-semibold shadow-sm hover:shadow-glow-sm transition-all duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            {{ __('groups.add') }}
        </button>
    </div>

    {{-- Inline create/edit form --}}
    @if($showForm)
    <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50
                bg-emerald-50/30 dark:bg-emerald-900/10 p-6 animate-scale-in">
        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-5">
            {{ $editingId ? __('groups.edit_title') : __('groups.new') }}
        </h3>
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('groups.form.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text" class="field" placeholder="{{ __('groups.name_placeholder') }}">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('groups.form.teacher') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="teacher_id" class="field">
                        <option value="">{{ __('groups.select_teacher') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('groups.form.description') }}
                </label>
                <textarea wire:model="description" rows="2" class="field resize-none"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold
                               shadow-sm transition-all duration-150 disabled:opacity-60">
                    {{ __('groups.btn.save') }}
                </button>
                <button type="button" wire:click="$set('showForm', false)"
                        class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-navy-700 text-slate-600 dark:text-slate-300
                               hover:bg-slate-200 dark:hover:bg-navy-600 text-sm font-medium transition-colors">
                    {{ __('groups.btn.cancel') }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Student management panel --}}
    @if($managingStudentsGroupId)
    @php $mg = $groups->firstWhere('id', $managingStudentsGroupId); @endphp
    <div class="rounded-2xl border border-blue-200 dark:border-blue-800/50
                bg-blue-50/30 dark:bg-blue-900/10 p-6 animate-scale-in">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">
                    {{ __('groups.manage_students_title') }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $mg?->name }}</p>
            </div>
            <button wire:click="$set('managingStudentsGroupId', null)"
                    class="w-8 h-8 flex items-center justify-center rounded-xl
                           text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                           hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @if($students->isEmpty())
            <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-6">{{ __('students.empty') }}</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 max-h-60 overflow-y-auto mb-5">
            @foreach($students as $student)
            <label class="flex items-center gap-2.5 p-2.5 rounded-xl cursor-pointer
                          border border-slate-200 dark:border-navy-600
                          hover:border-emerald-300 dark:hover:border-emerald-700
                          hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                          has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/20
                          transition-colors">
                <input type="checkbox" wire:model="selectedStudents" value="{{ $student->id }}"
                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 flex-shrink-0">
                <span class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">{{ $student->name }}</span>
            </label>
            @endforeach
        </div>
        @endif

        <div class="flex items-center gap-3">
            <button wire:click="syncStudents"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-all">
                {{ __('groups.btn.save') }}
            </button>
            <button wire:click="$set('managingStudentsGroupId', null)"
                    class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-navy-700 text-slate-600 dark:text-slate-300
                           hover:bg-slate-200 dark:hover:bg-navy-600 text-sm font-medium transition-colors">
                {{ __('groups.btn.cancel') }}
            </button>
        </div>
    </div>
    @endif

    {{-- Groups grid --}}
    @if($groups->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300">{{ __('groups.empty') }}</h3>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1 mb-5">{{ __('groups.empty_hint') }}</p>
        <button wire:click="$set('showForm', true)"
                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-all">
            {{ __('groups.add') }}
        </button>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($groups as $group)
        @php
            $palette = [
                ['from-emerald-500','to-emerald-700','bg-emerald-50 dark:bg-emerald-900/20','text-emerald-700 dark:text-emerald-400'],
                ['from-blue-500','to-blue-700','bg-blue-50 dark:bg-blue-900/20','text-blue-700 dark:text-blue-400'],
                ['from-violet-500','to-violet-700','bg-violet-50 dark:bg-violet-900/20','text-violet-700 dark:text-violet-400'],
                ['from-amber-500','to-amber-700','bg-amber-50 dark:bg-amber-900/20','text-amber-700 dark:text-amber-400'],
                ['from-rose-500','to-rose-700','bg-rose-50 dark:bg-rose-900/20','text-rose-700 dark:text-rose-400'],
                ['from-teal-500','to-teal-700','bg-teal-50 dark:bg-teal-900/20','text-teal-700 dark:text-teal-400'],
            ];
            $c = $palette[$group->id % 6];
        @endphp
        <div class="rounded-2xl bg-white dark:bg-navy-900
                    border border-slate-100 dark:border-navy-700
                    shadow-card hover:shadow-card-md
                    overflow-hidden group/card
                    transition-shadow duration-200">
            {{-- Color accent strip --}}
            <div class="h-1.5 bg-gradient-to-r {{ $c[0] }} {{ $c[1] }}"></div>

            <div class="p-5">
                {{-- Name --}}
                <h3 class="font-semibold text-slate-800 dark:text-slate-100 truncate mb-1">{{ $group->name }}</h3>
                @if($group->description)
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-3">{{ $group->description }}</p>
                @else
                    <div class="mb-3"></div>
                @endif

                {{-- Teacher --}}
                @if($group->teacher)
                <div class="flex items-center gap-2 mb-4 min-w-0">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex-shrink-0
                                flex items-center justify-center text-white text-[10px] font-bold">
                        {{ mb_strtoupper(mb_substr($group->teacher->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate leading-tight">{{ $group->teacher->name }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 leading-tight">{{ __('groups.teacher_label') }}</p>
                    </div>
                </div>
                @endif

                {{-- Student count pill --}}
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg {{ $c[2] }} {{ $c[3] }} mb-5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                    <span class="text-xs font-semibold">
                        {{ __('groups.students_count', ['count' => $group->students->count()]) }}
                    </span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    <button wire:click="manageStudents({{ $group->id }})"
                            class="flex-1 text-xs font-medium py-1.5 px-2.5 rounded-lg
                                   border border-slate-200 dark:border-navy-600
                                   bg-slate-50 dark:bg-navy-800
                                   text-slate-600 dark:text-slate-300
                                   hover:border-emerald-300 dark:hover:border-emerald-700
                                   hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                                   hover:text-emerald-700 dark:hover:text-emerald-400
                                   transition-colors duration-150 truncate">
                        {{ __('groups.btn.manage_students') }}
                    </button>
                    <button wire:click="edit({{ $group->id }})"
                            class="w-7 h-7 flex items-center justify-center rounded-lg
                                   text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                                   hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                        </svg>
                    </button>
                    <button wire:click="delete({{ $group->id }})"
                            wire:confirm="{{ __('groups.confirm_delete') }}"
                            class="w-7 h-7 flex items-center justify-center rounded-lg
                                   text-slate-400 hover:text-red-600 dark:hover:text-red-400
                                   hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
