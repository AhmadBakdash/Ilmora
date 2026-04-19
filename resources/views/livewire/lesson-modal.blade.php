<div x-data="{ tab: 'attendance' }">

    {{-- Tab switcher --}}
    <div class="flex gap-1 p-1 rounded-xl bg-slate-100 dark:bg-navy-800 mb-6">
        <button @click="tab = 'attendance'"
                :class="tab === 'attendance'
                    ? 'bg-white dark:bg-navy-700 text-slate-800 dark:text-slate-100 shadow-sm'
                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ __('modal.tab.attendance') }}
        </button>
        <button @click="tab = 'assignments'"
                :class="tab === 'assignments'
                    ? 'bg-white dark:bg-navy-700 text-slate-800 dark:text-slate-100 shadow-sm'
                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="flex-1 flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium transition-all duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
            </svg>
            {{ __('modal.tab.assignments') }}
        </button>
    </div>

    {{-- ========================================================== --}}
    {{-- ATTENDANCE TAB                                              --}}
    {{-- ========================================================== --}}
    <div x-show="tab === 'attendance'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

        {{-- Date picker --}}
        <div class="flex items-center gap-3 mb-5">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 flex-shrink-0">
                {{ __('modal.attendance.date') }}
            </label>
            <input wire:model.live="attendanceDate" type="date"
                   class="field py-1.5 text-sm w-auto" dir="ltr">
        </div>

        {{-- Success flash --}}
        @if(session()->has('attendance_success'))
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl mb-4
                    bg-emerald-50 dark:bg-emerald-900/20
                    border border-emerald-200 dark:border-emerald-800
                    text-emerald-700 dark:text-emerald-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ session('attendance_success') }}
        </div>
        @endif

        @if($lesson && $lesson->group && $lesson->group->students->count() > 0)
        <div class="space-y-1.5 mb-5">
            @foreach($lesson->group->students as $student)
            @php
                $status = $attendanceStatuses[$student->id] ?? 'present';
                $statusColors = [
                    'present' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800',
                    'absent'  => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
                    'late'    => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
                ];
                $words    = explode(' ', trim($student->name));
                $initials = mb_strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
            @endphp
            <div class="flex items-center justify-between p-3 rounded-xl border
                        {{ $statusColors[$status] ?? $statusColors['present'] }}
                        transition-colors duration-150">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-navy-700 flex items-center justify-center
                                text-[11px] font-bold text-slate-600 dark:text-slate-300 flex-shrink-0">
                        {{ $initials }}
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $student->name }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    @foreach(['present' => __('modal.attendance.present'), 'late' => __('modal.attendance.late'), 'absent' => __('modal.attendance.absent')] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" wire:model.live="attendanceStatuses.{{ $student->id }}" value="{{ $val }}" class="sr-only peer">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-medium transition-all
                                     peer-checked:{{ $val === 'present' ? 'bg-emerald-600 text-white' : ($val === 'late' ? 'bg-amber-500 text-white' : 'bg-red-600 text-white') }}
                                     {{ $attendanceStatuses[$student->id] ?? 'present' === $val ? '' : 'bg-white dark:bg-navy-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-navy-600' }}">
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        <button wire:click="saveAttendance"
                wire:loading.attr="disabled"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                       text-white text-sm font-semibold shadow-sm transition-all disabled:opacity-60">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ __('modal.attendance.save') }}
        </button>
        @else
        <div class="flex flex-col items-center justify-center py-10 text-center">
            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
            </div>
            <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('modal.attendance.no_students') }}</p>
        </div>
        @endif
    </div>

    {{-- ========================================================== --}}
    {{-- ASSIGNMENTS TAB                                             --}}
    {{-- ========================================================== --}}
    <div x-show="tab === 'assignments'" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

        {{-- Grading panel --}}
        @if($gradingAssignmentId)
        @php $gradingAssignment = $lesson?->assignments->firstWhere('id', $gradingAssignmentId); @endphp
        <div class="rounded-2xl border-2 border-emerald-300 dark:border-emerald-700
                    bg-emerald-50/50 dark:bg-emerald-900/10 p-5 mb-5 animate-scale-in">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-0.5">
                        {{ __('modal.grading.prefix') }}
                    </p>
                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $gradingAssignment?->title }}</h4>
                </div>
                <button wire:click="closeGrading"
                        class="w-7 h-7 flex items-center justify-center rounded-lg
                               text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                               hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @if(session()->has('grade_success'))
            <div class="flex items-center gap-2 px-3 py-2 rounded-xl mb-3
                        bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ session('grade_success') }}
            </div>
            @endif

            @if($gradingAssignment && $gradingAssignment->students->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($gradingAssignment->students as $student)
                <div class="bg-white dark:bg-navy-800 rounded-xl border border-slate-100 dark:border-navy-700 p-3">
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100 mb-2.5">{{ $student->name }}</p>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('modal.grading.status') }}</label>
                            <select wire:model="gradeStatuses.{{ $student->id }}" class="field py-1.5 text-xs">
                                <option value="pending">{{ __('modal.grading.pending') }}</option>
                                <option value="done">{{ __('modal.grading.done') }}</option>
                                <option value="needs_repeat">{{ __('modal.grading.needs_repeat') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('modal.grading.note') }}</label>
                            <input wire:model="gradeNotes.{{ $student->id }}" type="text"
                                   class="field py-1.5 text-xs" placeholder="{{ __('modal.grading.note_placeholder') }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button wire:click="saveGrades"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                           text-white text-sm font-semibold shadow-sm transition-all disabled:opacity-60">
                {{ __('modal.grading.save') }}
            </button>
            @else
            <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-4">{{ __('modal.grading.no_students') }}</p>
            @endif
        </div>
        @endif

        {{-- Create assignment form (hidden when grading panel is open) --}}
        @unless($gradingAssignmentId)
        <div class="rounded-2xl border border-slate-200 dark:border-navy-600
                    bg-slate-50 dark:bg-navy-800/50 p-5 mb-5">
            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4">
                {{ __('modal.assignment.new') }}
            </h4>

            {{-- Type selector --}}
            <div class="flex gap-2 mb-4">
                @foreach(['hifz' => __('modal.assignment.hifz'), 'murajaah' => __('modal.assignment.murajaah'), 'tilawah' => __('modal.assignment.tilawah')] as $val => $label)
                <label class="cursor-pointer flex-1">
                    <input type="radio" wire:model.live="assignmentType" value="{{ $val }}" class="sr-only peer">
                    <span class="block text-center py-1.5 px-2 rounded-lg text-xs font-semibold transition-all
                                 border border-slate-200 dark:border-navy-600
                                 peer-checked:border-emerald-500 peer-checked:bg-emerald-600 peer-checked:text-white
                                 bg-white dark:bg-navy-800 text-slate-600 dark:text-slate-400
                                 hover:border-emerald-300 dark:hover:border-emerald-700">
                        {{ $label }}
                    </span>
                </label>
                @endforeach
            </div>

            <div class="space-y-3">
                {{-- Surah select --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('modal.assignment.surah') }}</label>
                    <select wire:model.live="assignmentSurahNumber" class="field text-sm py-2">
                        <option value="">{{ __('modal.assignment.no_surah') }}</option>
                        @foreach($surahs as $surah)
                            <option value="{{ $surah->id }}">{{ $surah->id }}. {{ $surah->name_en }} ({{ $surah->name_ar }}) — {{ $surah->total_ayahs }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ayah range (shown only when surah is selected) --}}
                @if($assignmentSurahNumber)
                @php $selectedSurah = $surahs->firstWhere('id', $assignmentSurahNumber); @endphp
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                            {{ __('modal.assignment.from_ayah') }}
                        </label>
                        <input wire:model="assignmentStartAyah" type="number" min="1"
                               max="{{ $selectedSurah?->total_ayahs }}"
                               class="field text-sm py-2" placeholder="1" dir="ltr">
                        @error('assignmentStartAyah')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                            {{ __('modal.assignment.to_ayah') }}
                            <span class="text-slate-400">({{ __('modal.assignment.max') }} {{ $selectedSurah?->total_ayahs }})</span>
                        </label>
                        <input wire:model="assignmentEndAyah" type="number" min="1"
                               max="{{ $selectedSurah?->total_ayahs }}"
                               class="field text-sm py-2" placeholder="{{ $selectedSurah?->total_ayahs }}" dir="ltr">
                        @error('assignmentEndAyah')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
                @endif

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('modal.assignment.title_label') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="assignmentTitle" type="text" class="field text-sm py-2">
                    @error('assignmentTitle')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('modal.assignment.notes') }}</label>
                    <textarea wire:model="assignmentDescription" rows="2"
                              class="field text-sm py-2 resize-none"
                              placeholder="{{ __('modal.assignment.instructions') }}"></textarea>
                </div>

                {{-- Due date --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">{{ __('modal.assignment.due_date') }}</label>
                    <input wire:model="assignmentDueDate" type="date" class="field text-sm py-2 w-auto" dir="ltr">
                </div>

                <button wire:click="createAssignment"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700
                               text-white text-sm font-semibold shadow-sm transition-all disabled:opacity-60">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    {{ __('modal.assignment.create') }}
                </button>
            </div>
        </div>
        @endunless

        {{-- Assignment success flash --}}
        @if(session()->has('assignment_success'))
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl mb-4
                    bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800
                    text-emerald-700 dark:text-emerald-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            {{ session('assignment_success') }}
        </div>
        @endif

        {{-- Assignment list --}}
        @if($lesson && $lesson->assignments->count() > 0)
        <div class="space-y-3">
            <h4 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                {{ __('modal.assignment.list_title') }}
            </h4>
            @foreach($lesson->assignments as $assignment)
            @php
                $typeColors = [
                    'hifz'     => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                    'murajaah' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                    'tilawah'  => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
                ];
                $typeColor = $typeColors[$assignment->type] ?? 'bg-slate-100 text-slate-600';
                $doneCount = $assignment->students->where('pivot.status', 'done')->count();
                $totalCount = $assignment->students->count();
                $progress = $totalCount > 0 ? round(($doneCount / $totalCount) * 100) : 0;
                $isActive = $gradingAssignmentId === $assignment->id;
            @endphp
            <div class="rounded-2xl border p-4 transition-all duration-200
                        {{ $isActive
                            ? 'border-emerald-300 dark:border-emerald-700 bg-emerald-50/50 dark:bg-emerald-900/10'
                            : 'border-slate-100 dark:border-navy-700 bg-white dark:bg-navy-900' }}">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-md {{ $typeColor }}">
                                {{ ucfirst($assignment->type) }}
                            </span>
                            @if($assignment->status === 'completed')
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md
                                             bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                    {{ __('modal.assignment.completed') }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $assignment->title }}</p>
                        @if($assignment->surah)
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-arabic mt-0.5">
                                {{ $assignment->surah->name_ar }}
                                @if($assignment->start_ayah && $assignment->end_ayah)
                                    ({{ $assignment->start_ayah }}–{{ $assignment->end_ayah }})
                                @endif
                            </p>
                        @endif
                    </div>
                    @if($assignment->due_date)
                    <div class="text-end flex-shrink-0">
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ __('modal.assignment.due') }}</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-300" dir="ltr">{{ $assignment->due_date->format('M d') }}</p>
                    </div>
                    @endif
                </div>

                @if($assignment->description)
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ $assignment->description }}</p>
                @endif

                {{-- Progress bar --}}
                <div class="mb-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500">{{ $doneCount }}/{{ $totalCount }} {{ __('modal.assignment.done') }}</span>
                        <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ $progress }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-navy-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-500"
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <button wire:click="openGrading({{ $assignment->id }})"
                        class="flex items-center gap-1.5 text-xs font-semibold
                               text-emerald-700 dark:text-emerald-400
                               hover:text-emerald-800 dark:hover:text-emerald-300
                               transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                    </svg>
                    {{ __('modal.grading.grade_btn') }}
                </button>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('modal.assignment.empty') }}</p>
        </div>
        @endif

    </div>

</div>
