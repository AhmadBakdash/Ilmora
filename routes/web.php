<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Setup;
use App\Livewire\Dashboard;
use App\Livewire\Groups;
use App\Livewire\Students;
use App\Livewire\Schedule\LessonForm;
use App\Livewire\Teachers;
use App\Livewire\Auth\StudentRegister;

Route::middleware(\App\Http\Middleware\EnsureSchoolExists::class)->group(function () {
    Route::get('/setup', Setup::class)->name('setup');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::middleware('role:school_admin|teacher')->group(function () {
            Route::get('/lessons', LessonForm::class)->name('lessons');
            Route::get('/groups', Groups::class)->name('groups');
            Route::get('/students', Students::class)->name('students');
        });

        Route::middleware('role:school_admin')->group(function () {
            Route::get('/teachers', Teachers::class)->name('teachers');
        });
    });
});

Route::get('/', function () {
    if (\App\Models\School::exists()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('setup');
});

Route::get('/register/{school:slug}', StudentRegister::class)->name('student.register');
Route::get('/locale/{locale}', function (string $locale) {
    $supported = ['ar', 'en', 'de', 'fr', 'tr', 'ms', 'ur'];
    if (!in_array($locale, $supported)) abort(404);
    if (auth()->check()) {
        auth()->user()->update(['locale' => $locale]);
    }
    return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
})->name('locale.switch');

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
