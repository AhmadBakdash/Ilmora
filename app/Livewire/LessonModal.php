<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Lesson;
use Carbon\Carbon;
use Livewire\Component;

class LessonModal extends Component
{
    public ?int $lessonId = null;
    public string $assignmentTitle = '';
    public string $assignmentDescription = '';
    public ?string $assignmentDueDate = null;
    public string $attendanceDate = '';
    public array $attendanceStatuses = [];

    public function mount(): void
    {
        $this->attendanceDate = Carbon::today()->format('Y-m-d');
        if ($this->lessonId) {
            $this->loadAttendance();
        }
    }

    public function updatedAttendanceDate(): void
    {
        $this->loadAttendance();
    }

    public function loadAttendance(): void
    {
        if (!$this->lessonId) return;
        $lesson = Lesson::with('group.students')->find($this->lessonId);
        if (!$lesson) return;
        $existing = Attendance::where('lesson_id', $this->lessonId)
            ->where('date', $this->attendanceDate)
            ->get()
            ->keyBy('student_id');
        $this->attendanceStatuses = [];
        foreach ($lesson->group->students as $student) {
            $this->attendanceStatuses[$student->id] = $existing[$student->id]->status ?? 'present';
        }
    }

    public function saveAttendance(): void
    {
        foreach ($this->attendanceStatuses as $studentId => $status) {
            Attendance::updateOrCreate(
                ['lesson_id' => $this->lessonId, 'student_id' => $studentId, 'date' => $this->attendanceDate],
                ['status' => $status]
            );
        }
        session()->flash('attendance_success', 'Attendance saved.');
    }

    public function createAssignment(): void
    {
        $this->validate([
            'assignmentTitle' => 'required|string|max:255',
            'assignmentDueDate' => 'nullable|date',
        ]);
        $assignment = Assignment::create([
            'lesson_id' => $this->lessonId,
            'title' => $this->assignmentTitle,
            'description' => $this->assignmentDescription,
            'due_date' => $this->assignmentDueDate,
        ]);
        $lesson = Lesson::with('group.students')->find($this->lessonId);
        foreach ($lesson->group->students as $student) {
            $assignment->students()->attach($student->id, ['status' => 'pending']);
        }
        $this->reset(['assignmentTitle', 'assignmentDescription', 'assignmentDueDate']);
        session()->flash('assignment_success', 'Assignment created.');
    }

    public function render()
    {
        $lesson = null;
        if ($this->lessonId) {
            $lesson = Lesson::with(['group.students', 'assignments.students', 'attendances'])->find($this->lessonId);
        }
        return view('livewire.lesson-modal', compact('lesson'));
    }
}
