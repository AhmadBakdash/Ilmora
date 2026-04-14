<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['lesson_id', 'title', 'description', 'due_date'];
    protected $casts = ['due_date' => 'date'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'assignment_student', 'assignment_id', 'student_id')
            ->withPivot('status', 'note')
            ->withTimestamps();
    }
}
