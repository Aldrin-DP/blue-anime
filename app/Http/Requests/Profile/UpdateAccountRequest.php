<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'username' => [
                'required', 'string', 'min:2', 'max:100', 'alpha_num',
                Rule::unique('users')->ignore($request->user()->id)
            ],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($request->user()->id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'username.min' => 'Username must be at least 2 characters.',
            'username.max' => 'Username must not exceed 100 characters.',
            'username.alpha_num' => 'Username must only contain letters and numbers.'
        ];
    }
}
