<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ignoreId = $this->route('ignoreId');
        return static::rulesFor($ignoreId);
    }

    public static function rulesFor(?int $ignoreId): array
    {
        return [
            'name'          => 'required|string|max:255',
            'age'           => 'nullable|integer|min:3|max:99',
            'phone'         => 'required|string|max:30',
            'guardian_name' => 'nullable|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . ($ignoreId ?? 'NULL'),
            'password'      => 'nullable|min:8',
            'siblingIds'    => 'nullable|array',
            'siblingIds.*'  => 'integer|exists:users,id',
        ];
    }
}
