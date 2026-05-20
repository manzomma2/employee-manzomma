<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:branches,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The branch name is required.',
            'name.unique' => 'A branch with this name already exists.',
        ];
    }
}
