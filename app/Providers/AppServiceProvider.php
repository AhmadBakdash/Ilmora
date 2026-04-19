<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use App\Policies\AssignmentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\LessonPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Group::class, GroupPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
        Gate::policy(Assignment::class, AssignmentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
