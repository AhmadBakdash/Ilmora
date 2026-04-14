<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Weekly Timetable</h1>
        <div class="flex items-center gap-3">
            <button wire:click="previousWeek" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-sm">&larr; Prev</button>
            <span class="text-sm font-medium text-gray-600">
                {{ $weekStartDate->format('M d') }} - {{ $weekStartDate->copy()->addDays(4)->format('M d, Y') }}
            </span>
            <button wire:click="nextWeek" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-sm">Next &rarr;</button>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-4">
        @foreach ($days as $index => $day)
            @php $dayNum = $index + 1; @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="bg-indigo-50 rounded-t-xl px-3 py-2 border-b border-indigo-100">
                    <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide">{{ $day }}</p>
                    <p class="text-xs text-indigo-400">{{ $weekStartDate->copy()->addDays($index)->format('M d') }}</p>
                </div>
                <div class="p-2 space-y-2 min-h-[200px]">
                    @if ($this->lessons->has($dayNum))
                        @foreach ($this->lessons[$dayNum] as $lesson)
                            <div wire:click="selectLesson({{ $lesson->id }})"
                                 class="cursor-pointer bg-indigo-100 hover:bg-indigo-200 rounded-lg p-2 transition-colors">
                                <p class="text-xs font-semibold text-indigo-800">{{ $lesson->title }}</p>
                                <p class="text-xs text-indigo-600">{{ substr($lesson->start_time, 0, 5) }} - {{ substr($lesson->end_time, 0, 5) }}</p>
                                @if ($lesson->room)
                                    <p class="text-xs text-indigo-400">Room {{ $lesson->room }}</p>
                                @endif
                                <p class="text-xs text-indigo-500 mt-1">{{ $lesson->group->name ?? '' }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-300 text-center mt-8">No lessons</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($this->selectedLesson)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $this->selectedLesson->title }}</h2>
                        <p class="text-sm text-gray-500">{{ $this->selectedLesson->group->name ?? '' }} &bull; {{ $this->selectedLesson->day_name }}</p>
                        <p class="text-sm text-gray-500">{{ substr($this->selectedLesson->start_time, 0, 5) }} - {{ substr($this->selectedLesson->end_time, 0, 5) }}@if ($this->selectedLesson->room) &bull; Room {{ $this->selectedLesson->room }}@endif</p>
                    </div>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                </div>

                <div class="p-6">
                    @livewire('lesson-modal', ['lessonId' => $this->selectedLesson->id], key($this->selectedLesson->id))
                </div>
            </div>
        </div>
    @endif
</div>
