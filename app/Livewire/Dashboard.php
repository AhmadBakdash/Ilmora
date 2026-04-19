<?php

namespace App\Livewire;

use App\Models\Lesson;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public string $weekStart;
    public ?int $selectedLessonId = null;

    public function mount(): void
    {
        $this->weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
    }

    public function previousWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->format('Y-m-d');
    }

    public function nextWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->format('Y-m-d');
    }

    public function selectLesson(int $id): void
    {
        $this->selectedLessonId = $id;
    }

    public function closeModal(): void
    {
        $this->selectedLessonId = null;
    }

    public function getLessonsProperty()
    {
        return Lesson::with(['group', 'teacher'])
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');
    }

    public function getSelectedLessonProperty()
    {
        if (!$this->selectedLessonId) return null;
        return Lesson::with(['group.students', 'assignments', 'attendances'])->find($this->selectedLessonId);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'days' => [
                1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday',
            ],
            'weekStartDate' => Carbon::parse($this->weekStart),
        ])->layout('components.layouts.app');
    }
}
