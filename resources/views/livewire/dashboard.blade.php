<div class="space-y-6 animate-fade-in">

@php
/* ── Time grid config ──────────────────────────── */
$gridStart   = 7;  // 7 AM
$gridEnd     = 21; // 9 PM
$totalHours  = $gridEnd - $gridStart; // 14
$hourPx      = 72;
$gridHeight  = $totalHours * $hourPx; // 1008px
$hours       = range($gridStart, $gridEnd - 1);

/* ── Today detection ────────────────────────────── */
$today          = \Carbon\Carbon::today();
$isCurrentWeek  = $weekStartDate->isSameWeek($today, true);
$todayDow       = (int)$today->dayOfWeekIso; // 1=Mon 5=Fri

/* ── Color palette by group_id ───────────────────  */
$palette = [
    ['bg' => 'bg-emerald-100/80 dark:bg-emerald-900/40',  'border' => 'border-s-2 border-emerald-500', 'title' => 'text-emerald-800 dark:text-emerald-200', 'sub' => 'text-emerald-600 dark:text-emerald-400', 'dot' => 'bg-emerald-500'],
    ['bg' => 'bg-amber-100/80 dark:bg-amber-900/40',      'border' => 'border-s-2 border-amber-500',   'title' => 'text-amber-800 dark:text-amber-200',   'sub' => 'text-amber-600 dark:text-amber-400',   'dot' => 'bg-amber-500'],
    ['bg' => 'bg-violet-100/80 dark:bg-violet-900/40',    'border' => 'border-s-2 border-violet-500',  'title' => 'text-violet-800 dark:text-violet-200', 'sub' => 'text-violet-600 dark:text-violet-400', 'dot' => 'bg-violet-500'],
    ['bg' => 'bg-rose-100/80 dark:bg-rose-900/40',        'border' => 'border-s-2 border-rose-500',    'title' => 'text-rose-800 dark:text-rose-200',     'sub' => 'text-rose-600 dark:text-rose-400',     'dot' => 'bg-rose-500'],
    ['bg' => 'bg-sky-100/80 dark:bg-sky-900/40',          'border' => 'border-s-2 border-sky-500',     'title' => 'text-sky-800 dark:text-sky-200',       'sub' => 'text-sky-600 dark:text-sky-400',       'dot' => 'bg-sky-500'],
    ['bg' => 'bg-orange-100/80 dark:bg-orange-900/40',    'border' => 'border-s-2 border-orange-500',  'title' => 'text-orange-800 dark:text-orange-200', 'sub' => 'text-orange-600 dark:text-orange-400', 'dot' => 'bg-orange-500'],
];
$color = fn($lesson) => $palette[($lesson->group_id ?? $lesson->id ?? 0) % count($palette)];
@endphp

{{-- ── Page header ─────────────────────────────── --}}
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">

    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">
            {{ __('dashboard.title') }}
        </h2>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-0.5">
            {{ $weekStartDate->locale(app()->getLocale())->isoFormat('D MMMM') }} —
            {{ $weekStartDate->copy()->addDays(6)->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}
        </p>
    </div>

    <div class="flex items-center gap-2">
        <button wire:click="previousWeek"
                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-navy-600
                       flex items-center justify-center
                       text-slate-500 dark:text-slate-400
                       hover:bg-slate-100 dark:hover:bg-navy-700
                       transition-colors duration-150">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
        </button>

        <button wire:click="$set('weekStart', '{{ \Carbon\Carbon::today()->startOfWeek(0)->format('Y-m-d') }}')"
                class="px-3 h-9 rounded-xl border border-slate-200 dark:border-navy-600
                       text-xs font-medium text-slate-600 dark:text-slate-300
                       hover:bg-slate-100 dark:hover:bg-navy-700
                       transition-colors duration-150">
            اليوم
        </button>

        <button wire:click="nextWeek"
                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-navy-600
                       flex items-center justify-center
                       text-slate-500 dark:text-slate-400
                       hover:bg-slate-100 dark:hover:bg-navy-700
                       transition-colors duration-150">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </button>

        <a href="{{ route('lessons') }}"
           class="inline-flex items-center gap-1.5 px-4 h-9 rounded-xl
                  bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium
                  shadow-sm hover:shadow-glow-sm transition-all duration-150 ms-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            {{ __('dashboard.add_lesson') }}
        </a>
    </div>
</div>

{{-- ── Timetable grid ───────────────────────────── --}}
<div class="bg-white dark:bg-navy-800 rounded-2xl border border-slate-100 dark:border-navy-700 shadow-card overflow-hidden">

    {{-- Day headers --}}
    <div class="flex border-b border-slate-100 dark:border-navy-700">
        {{-- Time gutter --}}
        <div class="w-14 flex-shrink-0 border-e border-slate-100 dark:border-navy-700"></div>

        @php $dayOrder = [7, 1, 2, 3, 4, 5, 6]; /* Sun=7 first (ISO), then Mon–Sat */ @endphp
        @foreach($dayOrder as $position => $isoDay)
            @php
                $colDate = $weekStartDate->copy()->addDays($position);
                $isToday = $isCurrentWeek && $todayDow === $isoDay;
            @endphp
            <div @class([
                'flex-1 px-3 py-3 text-center border-e last:border-e-0 border-slate-100 dark:border-navy-700',
                'bg-emerald-50/60 dark:bg-emerald-900/10' => $isToday,
            ])>
                <p @class([
                    'text-[11px] font-semibold uppercase tracking-wider mb-0.5',
                    'text-emerald-600 dark:text-emerald-400' => $isToday,
                    'text-slate-400 dark:text-slate-500' => !$isToday,
                ])>{{ __('lessons.days.'.$isoDay) }}</p>
                <p @class([
                    'text-lg font-bold leading-none',
                    'text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-800/40 w-9 h-9 rounded-full flex items-center justify-center mx-auto' => $isToday,
                    'text-slate-700 dark:text-slate-300' => !$isToday,
                ])>{{ $colDate->day }}</p>
            </div>
        @endforeach
    </div>

    {{-- Scrollable time grid --}}
    <div class="overflow-y-auto" style="max-height: 70vh;">
        <div class="flex" style="min-height: {{ $gridHeight }}px;">

            {{-- Hour labels --}}
            <div class="w-14 flex-shrink-0 relative border-e border-slate-100 dark:border-navy-700">
                @foreach($hours as $h)
                    <div class="absolute w-full flex items-start justify-center"
                         style="top: {{ ($h - $gridStart) * $hourPx }}px; height: {{ $hourPx }}px;">
                        <span class="text-[10px] text-slate-300 dark:text-slate-600 font-medium pt-1 select-none">
                            {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Day columns --}}
            @foreach($dayOrder as $position => $isoDay)
                @php
                    $isToday = $isCurrentWeek && $todayDow === $isoDay;
                @endphp
                <div @class([
                    'flex-1 relative border-e last:border-e-0 border-slate-100 dark:border-navy-700',
                    'bg-emerald-50/30 dark:bg-emerald-900/5' => $isToday,
                ]) style="height: {{ $gridHeight }}px;">

                    {{-- Hour grid lines --}}
                    @foreach($hours as $h)
                        <div class="absolute start-0 end-0 border-t border-slate-100/80 dark:border-navy-700/60"
                             style="top: {{ ($h - $gridStart) * $hourPx }}px;"></div>
                        {{-- Half-hour line --}}
                        <div class="absolute start-0 end-0 border-t border-dashed border-slate-100/50 dark:border-navy-700/30"
                             style="top: {{ ($h - $gridStart) * $hourPx + $hourPx / 2 }}px;"></div>
                    @endforeach

                    {{-- Current time indicator (today only) --}}
                    @if($isToday)
                        <div
                            x-data="{ top: 0 }"
                            x-init="
                                const calc = () => {
                                    const now = new Date();
                                    const mins = (now.getHours() - {{ $gridStart }}) * 60 + now.getMinutes();
                                    top = Math.max(0, Math.round((mins / 60) * {{ $hourPx }}));
                                };
                                calc();
                                setInterval(calc, 30000);
                            "
                            :style="'top:' + top + 'px'"
                            class="absolute start-0 end-0 z-10 pointer-events-none flex items-center"
                        >
                            <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm flex-shrink-0 -ms-1.5 rtl:-me-1.5 rtl:ms-0"></div>
                            <div class="flex-1 h-px bg-red-500 opacity-60"></div>
                        </div>
                    @endif

                    {{-- Lesson cards --}}
                    @if($this->lessons->has($isoDay))
                        @php
                            $dayLessons = $this->lessons[$isoDay]->sortBy('start_time')->values();

                            /* ── Step 1: assign each lesson a column index ── */
                            $colEnds      = [];   // end_minute of the last lesson placed in each column
                            $lessonColIdx = [];   // lesson id → column index

                            foreach ($dayLessons as $l) {
                                [$lsh, $lsm] = array_pad(explode(':', substr($l->start_time, 0, 5)), 2, '0');
                                [$leh, $lem] = array_pad(explode(':', substr($l->end_time,   0, 5)), 2, '0');
                                $lsMin = (int)$lsh * 60 + (int)$lsm;
                                $leMin = (int)$leh * 60 + (int)$lem;

                                $placed = false;
                                foreach ($colEnds as $ci => $cEnd) {
                                    if ($cEnd <= $lsMin) {
                                        $colEnds[$ci]       = $leMin;
                                        $lessonColIdx[$l->id] = $ci;
                                        $placed = true;
                                        break;
                                    }
                                }
                                if (!$placed) {
                                    $lessonColIdx[$l->id] = count($colEnds);
                                    $colEnds[]            = $leMin;
                                }
                            }

                            /* ── Step 2: for each lesson, find the max column among all lessons
                               that overlap with it — that gives the denominator for width ── */
                            $lessonLayouts = [];
                            foreach ($dayLessons as $l) {
                                [$lsh, $lsm] = array_pad(explode(':', substr($l->start_time, 0, 5)), 2, '0');
                                [$leh, $lem] = array_pad(explode(':', substr($l->end_time,   0, 5)), 2, '0');
                                $lsMin = (int)$lsh * 60 + (int)$lsm;
                                $leMin = (int)$leh * 60 + (int)$lem;

                                $maxCol = $lessonColIdx[$l->id];
                                foreach ($dayLessons as $other) {
                                    [$osh, $osm] = array_pad(explode(':', substr($other->start_time, 0, 5)), 2, '0');
                                    [$oeh, $oem] = array_pad(explode(':', substr($other->end_time,   0, 5)), 2, '0');
                                    $osMin = (int)$osh * 60 + (int)$osm;
                                    $oeMin = (int)$oeh * 60 + (int)$oem;
                                    if ($osMin < $leMin && $oeMin > $lsMin) {
                                        $maxCol = max($maxCol, $lessonColIdx[$other->id]);
                                    }
                                }
                                $lessonLayouts[$l->id] = ['col' => $lessonColIdx[$l->id], 'total' => $maxCol + 1];
                            }
                        @endphp

                        @foreach($dayLessons as $lesson)
                            @php
                                [$sh, $sm] = array_pad(explode(':', substr($lesson->start_time, 0, 5)), 2, '0');
                                [$eh, $em] = array_pad(explode(':', substr($lesson->end_time,   0, 5)), 2, '0');
                                $startMin = ((int)$sh - $gridStart) * 60 + (int)$sm;
                                $endMin   = ((int)$eh - $gridStart) * 60 + (int)$em;
                                $topPx    = max(0, round(($startMin / 60) * $hourPx));
                                $heightPx = max(36, round((($endMin - $startMin) / 60) * $hourPx));
                                $c        = $color($lesson);
                                $short    = $heightPx < 60;

                                $layout   = $lessonLayouts[$lesson->id];
                                $colW     = round(100 / $layout['total'], 4);
                                $colL     = round($layout['col'] * $colW, 4);
                            @endphp

                            <div
                                wire:click="selectLesson({{ $lesson->id }})"
                                class="absolute rounded-xl {{ $c['bg'] }} {{ $c['border'] }}
                                       px-2 py-1 cursor-pointer overflow-hidden
                                       hover:brightness-95 dark:hover:brightness-110
                                       transition-all duration-150 hover:shadow-card-md hover:-translate-y-px
                                       group z-[1] hover:z-[2]"
                                style="top:{{ $topPx }}px; height:{{ $heightPx }}px;
                                       left:calc({{ $colL }}% + 2px); width:calc({{ $colW }}% - 4px);"
                            >
                                <p class="text-[11px] font-semibold truncate leading-tight {{ $c['title'] }}">
                                    {{ $lesson->title }}
                                </p>
                                @unless($short)
                                    <p class="text-[10px] truncate mt-0.5 {{ $c['sub'] }}">
                                        {{ substr($lesson->start_time, 0, 5) }} – {{ substr($lesson->end_time, 0, 5) }}
                                    </p>
                                    @if($lesson->group)
                                        <p class="text-[10px] truncate {{ $c['sub'] }} opacity-80">
                                            {{ $lesson->group->name }}
                                        </p>
                                    @endif
                                @endunless
                            </div>
                        @endforeach
                    @endif

                    {{-- Empty slot click-to-add hint --}}
                    @if(!$this->lessons->has($isoDay))
                        <a href="{{ route('lessons') }}"
                           class="absolute inset-2 rounded-xl border-2 border-dashed border-transparent
                                  hover:border-emerald-200 dark:hover:border-emerald-800
                                  flex items-center justify-center
                                  opacity-0 hover:opacity-100
                                  transition-all duration-200 group">
                            <svg class="w-6 h-6 text-emerald-400 dark:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</div>

{{-- ── Lesson detail modal ──────────────────────── --}}
@if($this->selectedLesson)
    <div
        wire:click.self="closeModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-data
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    >
        <div
            class="relative w-full max-w-2xl max-h-[90vh] flex flex-col
                   bg-white dark:bg-navy-800 rounded-2xl shadow-card-lg overflow-hidden animate-scale-in"
        >
            {{-- Modal header --}}
            <div class="flex items-start justify-between p-5 border-b border-slate-100 dark:border-navy-700 flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 truncate">
                        {{ $this->selectedLesson->title }}
                    </h2>
                    <div class="flex items-center flex-wrap gap-2 mt-1">
                        @if($this->selectedLesson->group)
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $this->selectedLesson->group->name }}</span>
                            <span class="text-slate-300 dark:text-slate-600">·</span>
                        @endif
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ substr($this->selectedLesson->start_time, 0, 5) }} – {{ substr($this->selectedLesson->end_time, 0, 5) }}
                        </span>
                        @if($this->selectedLesson->room)
                            <span class="text-slate-300 dark:text-slate-600">·</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $this->selectedLesson->room }}</span>
                        @endif
                        <x-badge :type="$this->selectedLesson->status">
                            {{ __('lessons.form.status_'.$this->selectedLesson->status) }}
                        </x-badge>
                    </div>
                </div>
                <button wire:click="closeModal"
                        class="ms-3 w-8 h-8 flex items-center justify-center rounded-xl
                               text-slate-400 hover:text-slate-600 dark:hover:text-slate-200
                               hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="flex-1 overflow-y-auto p-5">
                @livewire('lesson-modal', ['lessonId' => $this->selectedLesson->id], key($this->selectedLesson->id))
            </div>
        </div>
    </div>
@endif

</div>
