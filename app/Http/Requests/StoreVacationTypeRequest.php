<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVacationTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:vacation_types,name',
            'color' => 'nullable|string|max:50'
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
