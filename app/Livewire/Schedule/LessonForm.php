<?php

namespace App\Livewire\Schedule;

use App\Http\Requests\StoreLessonRequest;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LessonForm extends Component
{
    use AuthorizesRequests;

    public string $title = '';
    public int|string $group_id = '';
    public int|string $teacher_id = '';
    public int|string $day_of_week = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $room = '';
    public string $status = 'scheduled';

    public bool $showForm = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return (new StoreLessonRequest())->rules();
    }

    public function updatedGroupId(int|string $value): void
    {
        $group = Group::find($value);
        if ($group) {
            $this->teacher_id = $group->teacher_id;
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $lesson = Lesson::findOrFail($id);
        $this->authorize('update', $lesson);
        $this->editingId    = $id;
        $this->title        = $lesson->title;
        $this->group_id     = $lesson->group_id;
        $this->teacher_id   = $lesson->teacher_id;
        $this->day_of_week  = $lesson->day_of_week;
        $this->start_time   = substr($lesson->start_time, 0, 5);
        $this->end_time     = substr($lesson->end_time, 0, 5);
        $this->room         = $lesson->room ?? '';
        $this->status       = $lesson->status;
        $this->showForm     = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $lesson = Lesson::findOrFail($this->editingId);
            $this->authorize('update', $lesson);
            $lesson->update($data);
        } else {
            $this->authorize('create', Lesson::class);
            Lesson::create($data);
        }

        $this->resetForm();
        session()->flash('success', $this->editingId ? 'Lesson updated.' : 'Lesson created.');
        $this->editingId = null;
    }

    public function delete(int $id): void
    {
        $lesson = Lesson::findOrFail($id);
        $this->authorize('delete', $lesson);
        $lesson->delete();
        session()->flash('success', 'Lesson deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->title       = '';
        $this->group_id    = '';
        $this->teacher_id  = '';
        $this->day_of_week = '';
        $this->start_time  = '';
        $this->end_time    = '';
        $this->room        = '';
        $this->status      = 'scheduled';
        $this->showForm    = false;
        $this->editingId   = null;
    }

    public function render()
    {
        $schoolId = auth()->user()->school_id;

        return view('livewire.schedule.lesson-form', [
            'lessons'  => Lesson::with(['group', 'teacher'])
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get(),
            'groups'   => Group::all(),
            'teachers' => User::where('school_id', $schoolId)
                ->whereIn('role', ['teacher', 'school_admin'])
                ->get(),
            'days'     => [
                7 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
            ],
        ])->layout('components.layouts.app');
    }
}
