<?php

namespace App\Http\Requests\Employee;

use App\Enums\Grade;
use App\Enums\GradeLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeStoreRequest extends FormRequest
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
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:employees,email',
            'file_number' => 'nullable|string|max:50|unique:employees,file_number',
            'job_title' => 'nullable|string|max:255',
            'sector_id' => 'nullable|exists:sectors,id',
            'category_group_id' => 'required|exists:category_groups,id',
            'grade' => ['nullable', 'string', Rule::in(Grade::values())],
            'grade_level' => [
                'nullable',
                'string',
                Rule::in(GradeLevel::values()),
                function ($attribute, $value, $fail) {
                    $grade = $this->input('grade');
                    if ($value === GradeLevel::C->value && $grade !== Grade::THIRD->value) {
                        $fail('يمكن اختيار المستوى (ج) فقط مع الدرجة الثالثة.');
                    }
                },
            ],
            'grade_date' => 'nullable|date',
            'national_id' => 'nullable|string|max:50|unique:employees,national_id',
            'insurance_number' => 'nullable|string|max:50|unique:employees,insurance_number',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'contract_date' => 'nullable|date|after_or_equal:hire_date',
            'joining_date' => 'nullable|date|after_or_equal:contract_date',
        ];
    }
    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'email.email' => 'Please provide a valid email address.',
            'file_number.unique' => 'This file number is already in use.',
            'grade.rule' => 'Please select a valid grade.',
            'grade_level.rule' => 'Please select a valid grade level.',
            'photo.mimes' => 'Photo must be a valid image file (jpeg, png, jpg, gif).',
            'photo.max' => 'Photo size must not exceed 2MB.',
            'sector_id.exists' => 'The selected sector is invalid.',
            'category_group_id.exists' => 'The selected category group is invalid.',
            'national_id.unique' => 'This national ID is already registered.',
            'insurance_number.unique' => 'This insurance number is already registered.',
            'contract_date.after_or_equal' => 'Contract date must be on or after hire date.',
            'joining_date.after_or_equal' => 'Joining date must be on or after contract date.'
        ];
    }
}
