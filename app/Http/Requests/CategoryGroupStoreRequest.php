<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryGroupStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:category_groups,name',
            'job_group_id' => 'required|exists:job_groups,id',
            'description' => 'nullable|string'
        ];
    }
}
