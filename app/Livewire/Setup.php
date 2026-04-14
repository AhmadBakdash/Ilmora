<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Setup extends Component
{
    public string $school_name = '';
    public string $admin_name = '';
    public string $admin_email = '';
    public string $admin_password = '';
    public string $admin_password_confirmation = '';
    public bool $done = false;

    protected array $rules = [
        'school_name' => 'required|string|max:255',
        'admin_name' => 'required|string|max:255',
        'admin_email' => 'required|email|unique:users,email',
        'admin_password' => 'required|min:8|confirmed',
    ];

    public function submit(): void
    {
        $this->validate();
        $school = School::create([
            'name' => $this->school_name,
            'slug' => Str::slug($this->school_name),
        ]);
        $user = User::create([
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'password' => Hash::make($this->admin_password),
            'school_id' => $school->id,
            'role' => 'school_admin',
        ]);
        auth()->login($user);
        $this->done = true;
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.setup')->layout('components.layouts.app');
    }
}
