<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignmentType'        => 'required|in:hifz,murajaah,tilawah',
            'assignmentSurahNumber' => 'nullable|integer|exists:surahs,id',
            'assignmentStartAyah'   => 'nullable|integer|min:1',
            'assignmentEndAyah'     => 'nullable|integer|min:1|gte:assignmentStartAyah',
            'assignmentTitle'       => 'required|string|max:255',
            'assignmentDueDate'     => 'nullable|date',
        ];
    }
}
