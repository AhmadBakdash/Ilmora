<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['school_id', 'group_id', 'teacher_id', 'title', 'day_of_week', 'start_time', 'end_time', 'room'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getDayNameAttribute(): string
    {
        return ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'][$this->day_of_week] ?? '';
    }
}
