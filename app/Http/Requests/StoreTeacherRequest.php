<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ignoreId = $this->route('ignoreId');
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . ($ignoreId ?? 'NULL'),
            'password' => $ignoreId ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    public static function rulesFor(?int $ignoreId): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . ($ignoreId ?? 'NULL'),
            'password' => $ignoreId ? 'nullable|min:8' : 'required|min:8',
        ];
    }
}
