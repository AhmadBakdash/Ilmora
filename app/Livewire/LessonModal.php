<?php

namespace App\Livewire;

use App\Http\Requests\StoreAssignmentRequest;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\Surah;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LessonModal extends Component
{
    use AuthorizesRequests;

    public ?int $lessonId = null;

    // Attendance
    public string $attendanceDate = '';
    public array $attendanceStatuses = [];

    // Assignment creation
    public string $assignmentType           = 'hifz';
    public int|string $assignmentSurahNumber = '';
    public int|string $assignmentStartAyah  = '';
    public int|string $assignmentEndAyah    = '';
    public string $assignmentTitle          = '';
    public string $assignmentDescription    = '';
    public ?string $assignmentDueDate       = null;

    // Grading
    public ?int $gradingAssignmentId = null;
    public array $gradeStatuses      = []; // [studentId => status]
    public array $gradeNotes         = []; // [studentId => note]

    public function mount(): void
    {
        $this->attendanceDate = Carbon::today()->format('Y-m-d');
        if ($this->lessonId) {
            $this->loadAttendance();
        }
    }

    // -------------------------------------------------------------------------
    // Attendance
    // -------------------------------------------------------------------------

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
        $lesson = Lesson::findOrFail($this->lessonId);
        $this->authorize('markAttendance', $lesson);

        foreach ($this->attendanceStatuses as $studentId => $status) {
            Attendance::updateOrCreate(
                ['lesson_id' => $this->lessonId, 'student_id' => $studentId, 'date' => $this->attendanceDate],
                ['status' => $status]
            );
        }
        session()->flash('attendance_success', 'Attendance saved.');
    }

    // -------------------------------------------------------------------------
    // Assignment creation
    // -------------------------------------------------------------------------

    public function updatedAssignmentSurahNumber(): void
    {
        if ($this->assignmentSurahNumber) {
            $surah = Surah::find($this->assignmentSurahNumber);
            if ($surah) {
                $this->assignmentStartAyah = '';
                $this->assignmentEndAyah   = '';
                $typeName = match ($this->assignmentType) {
                    'murajaah' => "Muraja'ah",
                    'tilawah'  => 'Tilawah',
                    default    => 'Hifz',
                };
                $this->assignmentTitle = $typeName . ': ' . $surah->name_en . ' (' . $surah->name_ar . ')';
            }
        }
    }

    public function createAssignment(): void
    {
        $lesson = Lesson::findOrFail($this->lessonId);
        $this->authorize('manageAssignments', $lesson);

        $this->validate((new StoreAssignmentRequest())->rules());

        $assignment = Assignment::create([
            'lesson_id'    => $this->lessonId,
            'type'         => $this->assignmentType,
            'surah_number' => $this->assignmentSurahNumber ?: null,
            'start_ayah'   => $this->assignmentStartAyah ?: null,
            'end_ayah'     => $this->assignmentEndAyah ?: null,
            'title'        => $this->assignmentTitle,
            'description'  => $this->assignmentDescription,
            'due_date'     => $this->assignmentDueDate,
            'status'       => 'assigned',
        ]);

        $lesson->load('group.students');
        foreach ($lesson->group->students as $student) {
            $assignment->students()->attach($student->id, ['status' => 'pending']);
        }

        $this->reset([
            'assignmentType', 'assignmentSurahNumber', 'assignmentStartAyah',
            'assignmentEndAyah', 'assignmentTitle', 'assignmentDescription', 'assignmentDueDate',
        ]);
        $this->assignmentType = 'hifz';
        session()->flash('assignment_success', 'Assignment created.');
    }

    // -------------------------------------------------------------------------
    // Grading
    // -------------------------------------------------------------------------

    public function openGrading(int $assignmentId): void
    {
        $assignment = Assignment::with(['students', 'lesson'])->findOrFail($assignmentId);
        $this->authorize('grade', $assignment);
        $this->gradingAssignmentId = $assignmentId;
        $this->gradeStatuses = [];
        $this->gradeNotes    = [];
        foreach ($assignment->students as $student) {
            $this->gradeStatuses[$student->id] = $student->pivot->status ?? 'pending';
            $this->gradeNotes[$student->id]    = $student->pivot->note ?? '';
        }
    }

    public function closeGrading(): void
    {
        $this->gradingAssignmentId = null;
        $this->gradeStatuses       = [];
        $this->gradeNotes          = [];
    }

    public function saveGrades(): void
    {
        $assignment = Assignment::with(['students', 'lesson'])->findOrFail($this->gradingAssignmentId);
        $this->authorize('grade', $assignment);

        foreach ($this->gradeStatuses as $studentId => $status) {
            $assignment->students()->updateExistingPivot($studentId, [
                'status' => $status,
                'note'   => $this->gradeNotes[$studentId] ?? '',
            ]);
        }

        $assignment->refresh()->load('students');
        $allDone = $assignment->students->isNotEmpty()
            && $assignment->students->every(fn ($s) => $s->pivot->status === 'done');

        $assignment->update(['status' => $allDone ? 'completed' : 'assigned']);

        session()->flash('grade_success', 'Grades saved.');
        $this->closeGrading();
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        $lesson = null;
        if ($this->lessonId) {
            $lesson = Lesson::with([
                'group.students',
                'assignments.students',
                'assignments.surah',
                'attendances',
            ])->find($this->lessonId);
        }

        return view('livewire.lesson-modal', [
            'lesson' => $lesson,
            'surahs' => Surah::orderBy('id')->get(),
        ]);
    }
}
