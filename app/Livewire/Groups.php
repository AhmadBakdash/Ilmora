<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\User;
use Livewire\Component;

class Groups extends Component
{
    public string $name = '';
    public string $description = '';
    public ?int $teacher_id = null;
    public bool $showForm = false;
    public ?int $editingId = null;
    public ?int $managingStudentsGroupId = null;
    public array $selectedStudents = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
        ];
    }

    public function mount(): void
    {
        $this->teacher_id = auth()->id();
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'teacher_id' => $this->teacher_id,
            'school_id' => auth()->user()->school_id,
        ];
        if ($this->editingId) {
            Group::find($this->editingId)->update($data);
        } else {
            Group::create($data);
        }
        $this->reset(['name', 'description', 'showForm', 'editingId']);
        $this->teacher_id = auth()->id();
    }

    public function edit(int $id): void
    {
        $group = Group::find($id);
        $this->editingId = $id;
        $this->name = $group->name;
        $this->description = $group->description ?? '';
        $this->teacher_id = $group->teacher_id;
        $this->showForm = true;
    }

    public function delete(int $id): void
    {
        Group::find($id)->delete();
    }

    public function manageStudents(int $id): void
    {
        $this->managingStudentsGroupId = $id;
        $group = Group::with('students')->find($id);
        $this->selectedStudents = $group->students->pluck('id')->toArray();
    }

    public function syncStudents(): void
    {
        Group::find($this->managingStudentsGroupId)->students()->sync($this->selectedStudents);
        $this->managingStudentsGroupId = null;
        $this->selectedStudents = [];
    }

    public function render()
    {
        $schoolId = auth()->user()->school_id;
        return view('livewire.groups', [
            'groups' => Group::with(['teacher', 'students'])->where('school_id', $schoolId)->get(),
            'teachers' => User::where('school_id', $schoolId)->whereIn('role', ['teacher', 'school_admin'])->get(),
            'students' => User::where('school_id', $schoolId)->where('role', 'student')->get(),
        ])->layout('components.layouts.app');
    }
}
