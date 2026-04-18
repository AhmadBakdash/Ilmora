<?php

namespace App\Livewire\Auth;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class StudentRegister extends Component
{
    public School $school;

    public string $name          = '';
    public string $age           = '';
    public string $guardian_name = '';
    public string $phone         = '';
    public string $email         = '';

    public bool $registered = false;

    protected function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'age'           => 'required|integer|min:3|max:99',
            'guardian_name' => 'required|string|max:255',
            'phone'         => 'required|string|max:30',
            'email'         => 'nullable|email|unique:users,email',
        ];
    }

    public function mount(School $school): void
    {
        $this->school = $school;
    }

    public function register(): void
    {
        $this->validate();

        User::create([
            'name'          => $this->name,
            'age'           => (int) $this->age,
            'guardian_name' => $this->guardian_name,
            'phone'         => $this->phone,
            'email'         => $this->email ?: null,
            'password'      => Hash::make(Str::random(32)),
            'school_id'     => $this->school->id,
            'role'          => 'student',
        ]);

        $this->registered = true;
    }

    public function render()
    {
        return view('livewire.auth.student-register')
            ->layout('components.layouts.app');
    }
}
