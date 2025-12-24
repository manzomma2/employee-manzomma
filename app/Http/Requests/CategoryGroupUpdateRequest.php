<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryGroupUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryGroup = $this->route('category_group') ;     
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category_groups', 'name')->ignore($categoryGroup->id)
            ],
            'job_group_id' => 'required|exists:job_groups,id',
            'description' => 'nullable|string'
        ];
    }
}
