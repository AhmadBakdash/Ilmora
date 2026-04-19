<?php

namespace App\Livewire;

use App\Http\Requests\StoreGroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Groups extends Component
{
    use AuthorizesRequests;

    public string $name = '';
    public string $description = '';
    public ?int $teacher_id = null;
    public bool $showForm = false;
    public ?int $editingId = null;
    public ?int $managingStudentsGroupId = null;
    public array $selectedStudents = [];

    protected function rules(): array
    {
        return (new StoreGroupRequest())->rules();
    }

    public function mount(): void
    {
        $this->teacher_id = auth()->id();
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name'        => $this->name,
            'description' => $this->description,
            'teacher_id'  => $this->teacher_id,
        ];

        if ($this->editingId) {
            $group = Group::findOrFail($this->editingId);
            $this->authorize('update', $group);
            $group->update($data);
        } else {
            $this->authorize('create', Group::class);
            Group::create($data);
        }

        $this->reset(['name', 'description', 'showForm', 'editingId']);
        $this->teacher_id = auth()->id();
    }

    public function edit(int $id): void
    {
        $group = Group::findOrFail($id);
        $this->authorize('update', $group);
        $this->editingId    = $id;
        $this->name         = $group->name;
        $this->description  = $group->description ?? '';
        $this->teacher_id   = $group->teacher_id;
        $this->showForm     = true;
    }

    public function delete(int $id): void
    {
        $group = Group::findOrFail($id);
        $this->authorize('delete', $group);
        $group->delete();
    }

    public function manageStudents(int $id): void
    {
        $group = Group::with('students')->findOrFail($id);
        $this->authorize('manageStudents', $group);
        $this->managingStudentsGroupId = $id;
        $this->selectedStudents = $group->students->pluck('id')->toArray();
    }

    public function syncStudents(): void
    {
        $group = Group::findOrFail($this->managingStudentsGroupId);
        $this->authorize('manageStudents', $group);
        $group->students()->sync($this->selectedStudents);
        $this->managingStudentsGroupId = null;
        $this->selectedStudents = [];
    }

    public function render()
    {
        $schoolId = auth()->user()->school_id;
        return view('livewire.groups', [
            'groups'   => Group::with(['teacher', 'students'])->get(),
            'teachers' => User::where('school_id', $schoolId)->whereIn('role', ['teacher', 'school_admin'])->get(),
            'students' => User::where('school_id', $schoolId)->where('role', 'student')->get(),
        ])->layout('components.layouts.app');
    }
}
