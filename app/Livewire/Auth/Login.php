<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use Livewire\Component;

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
        if (app(AuthService::class)->attemptLogin($this->email, $this->password)) {
            $this->redirectRoute('dashboard');
        } else {
            $this->addError('email', __('Invalid credentials.'));
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
