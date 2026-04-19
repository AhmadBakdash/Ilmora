<div class="space-y-6 animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ __('nav.lessons') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ __('lessons.subtitle', ['count' => $lessons->count()]) }}
            </p>
        </div>
        @unless($showForm)
        <button wire:click="create"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                       text-white text-sm font-semibold shadow-sm hover:shadow-glow-sm transition-all duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            {{ __('lessons.add') }}
        </button>
        @endunless
    </div>

    {{-- Flash success --}}
    @if(session('success'))
    <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20
                border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Inline create/edit form --}}
    @if($showForm)
    <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800/50
                bg-emerald-50/30 dark:bg-emerald-900/10 p-6 animate-scale-in">
        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-5">
            {{ $editingId ? __('lessons.edit_title') : __('lessons.new') }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Title --}}
            <div class="sm:col-span-2 lg:col-span-3">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.title') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="title" type="text" class="field">
                @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Group --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.group') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model.live="group_id" class="field">
                    <option value="">{{ __('lessons.select.group') }}</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Teacher --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.teacher') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="teacher_id" class="field">
                    <option value="">{{ __('lessons.select.teacher') }}</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Day --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.day') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="day_of_week" class="field">
                    <option value="">{{ __('lessons.select.day') }}</option>
                    @foreach($days as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('day_of_week')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Start time --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.start_time') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="start_time" type="time" class="field" dir="ltr">
                @error('start_time')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- End time --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.end_time') }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="end_time" type="time" class="field" dir="ltr">
                @error('end_time')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Room --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.room') }}
                    <span class="font-normal text-slate-400 dark:text-slate-500">({{ __('lessons.form.room_optional') }})</span>
                </label>
                <input wire:model="room" type="text" class="field">
            </div>

            {{-- Status (edit only) --}}
            @if($editingId)
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    {{ __('lessons.form.status') }}
                </label>
                <select wire:model="status" class="field">
                    <option value="scheduled">{{ __('lessons.form.status_scheduled') }}</option>
                    <option value="cancelled">{{ __('lessons.form.status_cancelled') }}</option>
                </select>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold
                           shadow-sm transition-all duration-150 disabled:opacity-60">
                {{ $editingId ? __('lessons.btn.update') : __('lessons.btn.create') }}
            </button>
            <button wire:click="cancel"
                    class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-navy-700 text-slate-600 dark:text-slate-300
                           hover:bg-slate-200 dark:hover:bg-navy-600 text-sm font-medium transition-colors">
                {{ __('lessons.btn.cancel') }}
            </button>
        </div>
    </div>
    @endif

    {{-- Lessons table --}}
    @if($lessons->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300">{{ __('lessons.empty.title') }}</h3>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1 mb-5">{{ __('lessons.empty.hint') }}</p>
        <button wire:click="create"
                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-all">
            {{ __('lessons.add') }}
        </button>
    </div>
    @else
    <div class="rounded-2xl bg-white dark:bg-navy-900
                border border-slate-100 dark:border-navy-700
                shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-navy-700">
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ __('lessons.col.title') }}
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                            {{ __('lessons.col.group') }}
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">
                            {{ __('lessons.col.teacher') }}
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ __('lessons.col.day') }}
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ __('lessons.col.time') }}
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">
                            {{ __('lessons.col.room') }}
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                            {{ __('lessons.col.status') }}
                        </th>
                        <th class="px-5 py-3.5 text-end text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ __('lessons.col.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-navy-800">
                    @foreach($lessons as $lesson)
                    @php
                        $palette = [
                            'from-emerald-500 to-emerald-700',
                            'from-blue-500 to-blue-700',
                            'from-violet-500 to-violet-700',
                            'from-amber-500 to-amber-700',
                            'from-rose-500 to-rose-700',
                            'from-teal-500 to-teal-700',
                        ];
                        $groupColor = $palette[($lesson->group_id ?? 0) % 6];
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-navy-800/50 transition-colors duration-100">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-1 h-8 rounded-full bg-gradient-to-b {{ $groupColor }} flex-shrink-0"></div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate max-w-[160px]">{{ $lesson->title }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell">
                            <span class="text-sm text-slate-600 dark:text-slate-300">{{ $lesson->group->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5 hidden md:table-cell">
                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $lesson->teacher->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-sm text-slate-600 dark:text-slate-300">{{ $days[$lesson->day_of_week] ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-sm text-slate-600 dark:text-slate-300 tabular-nums" dir="ltr">
                                {{ substr($lesson->start_time, 0, 5) }} – {{ substr($lesson->end_time, 0, 5) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 hidden lg:table-cell">
                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $lesson->room ?: '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell">
                            @if($lesson->status === 'cancelled')
                                <span class="inline-flex items-center text-xs font-semibold px-2 py-1 rounded-lg
                                             bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                    {{ __('lessons.form.status_cancelled') }}
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-semibold px-2 py-1 rounded-lg
                                             bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                    {{ __('lessons.form.status_scheduled') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="edit({{ $lesson->id }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg
                                               text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                                               hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $lesson->id }})"
                                        wire:confirm="{{ __('lessons.confirm_delete') }}"
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
        </div>
    </div>
    @endif

</div>
