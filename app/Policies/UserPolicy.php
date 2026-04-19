<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) return true;
        return null;
    }

    public function createStudent(User $user): bool
    {
        return $user->hasRole('school_admin');
    }

    public function updateStudent(User $user, User $student): bool
    {
        if ($user->school_id !== $student->school_id) return false;
        return $user->hasRole('school_admin');
    }

    public function deleteStudent(User $user, User $student): bool
    {
        if ($user->school_id !== $student->school_id) return false;
        return $user->hasRole('school_admin');
    }

    public function createTeacher(User $user): bool
    {
        return $user->hasRole('school_admin');
    }

    public function updateTeacher(User $user, User $teacher): bool
    {
        if ($user->school_id !== $teacher->school_id) return false;
        return $user->hasRole('school_admin');
    }

    public function deleteTeacher(User $user, User $teacher): bool
    {
        if ($user->id === $teacher->id) return false;
        if ($user->school_id !== $teacher->school_id) return false;
        return $user->hasRole('school_admin');
    }
}
