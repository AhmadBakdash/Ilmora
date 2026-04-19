<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'group_id'    => 'required|exists:groups,id',
            'teacher_id'  => 'required|exists:users,id',
            'day_of_week' => 'required|integer|between:1,5',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'nullable|string|max:100',
            'status'      => 'required|in:scheduled,cancelled',
        ];
    }
}
