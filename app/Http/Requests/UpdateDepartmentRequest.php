<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:departments,name,' . $this->department,
            'branch_id' => 'sometimes|required|exists:branches,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The department name is required.',
            'name.unique' => 'A department with this name already exists.',
            'branch_id.required' => 'The branch is required.',
            'branch_id.exists' => 'The selected branch is invalid.',
        ];
    }
}
