<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Students extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public bool $showForm = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editingId ?? 'NULL'),
            'password' => $this->editingId ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'school_id' => auth()->user()->school_id,
            'role' => 'student',
        ];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        if ($this->editingId) {
            User::find($this->editingId)->update($data);
        } else {
            User::create($data);
        }
        $this->reset(['name', 'email', 'password', 'showForm', 'editingId']);
    }

    public function edit(int $id): void
    {
        $student = User::find($id);
        $this->editingId = $id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->showForm = true;
    }

    public function delete(int $id): void
    {
        User::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.students', [
            'students' => User::where('school_id', auth()->user()->school_id)->where('role', 'student')->get(),
        ])->layout('components.layouts.app');
    }
}
