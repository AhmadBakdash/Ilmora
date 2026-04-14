<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login(): void
    {
        $this->validate();
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            $this->redirect(route('dashboard'), navigate: true);
        } else {
            $this->addError('email', 'Invalid credentials.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.app');
    }
}
