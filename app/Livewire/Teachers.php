<?php

namespace App\Livewire;

use App\Http\Requests\StoreTeacherRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Teachers extends Component
{
    public string $name     = '';
    public string $email    = '';
    public string $password = '';

    public bool $showForm  = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return StoreTeacherRequest::rulesFor($this->editingId);
    }

    public function create(): void
    {
        $this->reset(['name', 'email', 'password', 'editingId']);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $teacher        = User::findOrFail($id);
        $this->editingId = $id;
        $this->name     = $teacher->name;
        $this->email    = $teacher->email;
        $this->password = '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'      => $this->name,
            'email'     => $this->email,
            'school_id' => auth()->user()->school_id,
            'role'      => 'teacher',
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
        } else {
            $teacher = User::create($data);
            $teacher->assignRole('teacher');
        }

        $this->reset(['name', 'email', 'password', 'showForm', 'editingId']);
        session()->flash('success', $this->editingId ? 'Teacher updated.' : 'Teacher created.');
    }

    public function delete(int $id): void
    {
        $teacher = User::where('school_id', auth()->user()->school_id)
            ->where('role', 'teacher')
            ->findOrFail($id);

        // Prevent deleting self
        if ($teacher->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $teacher->delete();
        session()->flash('success', 'Teacher deleted.');
    }

    public function cancel(): void
    {
        $this->reset(['name', 'email', 'password', 'showForm', 'editingId']);
    }

    public function render()
    {
        return view('livewire.teachers', [
            'teachers' => User::with('teachingGroups')
                ->where('school_id', auth()->user()->school_id)
                ->whereIn('role', ['teacher', 'school_admin'])
                ->orderBy('name')
                ->get(),
        ])->layout('components.layouts.app');
    }
}
