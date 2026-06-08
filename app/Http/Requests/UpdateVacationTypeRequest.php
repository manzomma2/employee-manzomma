<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVacationTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('vacation_types', 'name')->ignore($this->route('vacation_type')),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The vacation type name is required.',
            'name.unique' => 'A vacation type with this name already exists.',
        ];
    }
}
