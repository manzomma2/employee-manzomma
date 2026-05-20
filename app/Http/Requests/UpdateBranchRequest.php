<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:branches,name,' . $this->branch,
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
