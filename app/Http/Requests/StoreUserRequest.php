<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'sector_id' => 'nullable|exists:sectors,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The user name is required.',
            'email.required' => 'The user email is required.',
            'email.email' => 'The user email must be a valid email address.',
            'email.unique' => 'A user with this email already exists.',
            'password.required' => 'The user password is required.',
            'role_id.exists' => 'The selected role does not exist.',
            'sector_id.exists' => 'The selected sector does not exist.',
        ];
    }
}
