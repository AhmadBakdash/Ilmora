<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) return true;
        return null;
    }

    public function grade(User $user, Assignment $assignment): bool
    {
        $lesson = $assignment->lesson;
        if ($user->school_id !== $lesson->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $lesson->teacher_id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        $lesson = $assignment->lesson;
        if ($user->school_id !== $lesson->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $lesson->teacher_id;
    }
}
