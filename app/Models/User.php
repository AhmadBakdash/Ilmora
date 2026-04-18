<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'phone', 'guardian_name', 'age', 'password', 'school_id', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teachingGroups()
    {
        return $this->hasMany(Group::class, 'teacher_id');
    }

    public function studentGroups()
    {
        return $this->belongsToMany(Group::class, 'group_student');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    public function siblings(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_siblings', 'student_id', 'sibling_id');
    }

    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isStudent(): bool { return $this->role === 'student'; }
    public function isSchoolAdmin(): bool { return $this->role === 'school_admin'; }
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
}
