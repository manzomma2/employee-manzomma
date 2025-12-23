<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobGroupUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $jobGroupId = $this->route('job_group') ? $this->route('job_group')->id : $this->route('job-group');
        
        return [
            'name' => 'required|string|max:255|unique:job_groups,name,' . $jobGroupId,
            'description' => 'nullable|string'
        ];
    }
}
