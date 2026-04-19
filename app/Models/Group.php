<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use BelongsToTenant;

    protected $fillable = ['school_id', 'name', 'description', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'group_student');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
