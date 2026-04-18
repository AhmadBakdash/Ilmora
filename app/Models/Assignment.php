<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'lesson_id', 'type', 'surah_number', 'start_ayah', 'end_ayah',
        'juz_number', 'title', 'description', 'due_date', 'status', 'grade',
    ];

    protected $casts = ['due_date' => 'date'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_number');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'assignment_student', 'assignment_id', 'student_id')
            ->withPivot('status', 'note')
            ->withTimestamps();
    }
}
