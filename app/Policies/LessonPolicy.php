<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) return true;
        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('school_admin') || $user->hasRole('teacher');
    }

    public function update(User $user, Lesson $lesson): bool
    {
        if ($user->school_id !== $lesson->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $lesson->teacher_id;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        if ($user->school_id !== $lesson->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $lesson->teacher_id;
    }

    public function markAttendance(User $user, Lesson $lesson): bool
    {
        if ($user->school_id !== $lesson->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $lesson->teacher_id;
    }

    public function manageAssignments(User $user, Lesson $lesson): bool
    {
        if ($user->school_id !== $lesson->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $lesson->teacher_id;
    }
}
