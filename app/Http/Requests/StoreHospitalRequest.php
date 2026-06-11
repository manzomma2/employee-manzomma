<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHospitalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:hospitals,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The hospital name is required.',
            'name.unique' => 'A hospital with this name already exists.',
        ];
    }
}
