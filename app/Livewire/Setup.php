<?php

namespace App\Livewire;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
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

    public function submit()
    {
        $this->validate();
        try {
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

            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->route('dashboard');
        } catch (\Throwable $throwable) {
            report($throwable);
            $this->addError('form', 'Setup failed. Please try again or contact support if the problem persists.');
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.setup');
    }
}
