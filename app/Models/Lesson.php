<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use BelongsToTenant;

    protected $fillable = ['school_id', 'group_id', 'teacher_id', 'title', 'day_of_week', 'start_time', 'end_time', 'room', 'status'];

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
