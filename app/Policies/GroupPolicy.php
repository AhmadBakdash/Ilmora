<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) return true;
        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('school_admin');
    }

    public function update(User $user, Group $group): bool
    {
        if ($user->school_id !== $group->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $group->teacher_id;
    }

    public function delete(User $user, Group $group): bool
    {
        if ($user->school_id !== $group->school_id) return false;
        return $user->hasRole('school_admin');
    }

    public function manageStudents(User $user, Group $group): bool
    {
        if ($user->school_id !== $group->school_id) return false;
        return $user->hasRole('school_admin') || $user->id === $group->teacher_id;
    }
}
