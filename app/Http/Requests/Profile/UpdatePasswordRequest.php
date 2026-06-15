<?php

namespace App\Http\Requests\Profile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'The current password you entered is incorrect.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
