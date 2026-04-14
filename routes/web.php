<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Setup;
use App\Livewire\Dashboard;
use App\Livewire\Groups;
use App\Livewire\Students;

Route::middleware(\App\Http\Middleware\EnsureSchoolExists::class)->group(function () {
    Route::get('/setup', Setup::class)->name('setup');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/groups', Groups::class)->name('groups');
        Route::get('/students', Students::class)->name('students');
    });
});

Route::get('/', function () {
    if (\App\Models\School::exists()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('setup');
});

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
