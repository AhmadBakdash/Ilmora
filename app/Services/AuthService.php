<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function attemptLogin(string $email, string $password, bool $remember = false): bool
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            return false;
        }

        session()->regenerate();

        return true;
    }

    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function user(): ?User
    {
        return Auth::user();
    }
}
