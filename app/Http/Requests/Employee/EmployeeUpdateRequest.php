<?php

namespace App\Http\Requests\Employee;

use App\Enums\Grade;
use App\Enums\GradeLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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
        $employeeId = $this->route('employee');
        
        return [
            'first_name' => 'sometimes|nullable|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'email' => [
                'sometimes',
                'nullable',
                'email',
                Rule::unique('employees')->ignore($employeeId)
            ],
            'file_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
                Rule::unique('employees', 'file_number')->ignore($employeeId)
            ],
            'job_title' => 'sometimes|nullable|string|max:255',
            'sector_id' => 'sometimes|nullable|exists:sectors,id',
            'category_group_id' => 'sometimes|nullable|exists:category_groups,id',
            'national_id' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
                Rule::unique('employees', 'national_id')->ignore($employeeId)
            ],
            'insurance_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
                Rule::unique('employees', 'insurance_number')->ignore($employeeId)
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'sometimes|nullable|string|max:20',
            'hire_date' => 'sometimes|nullable|date',
            'contract_date' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:hire_date'
            ],
            'joining_date' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:contract_date'
            ],
            'grade' => [
                'sometimes',
                'nullable',
                'string',
                Rule::in(Grade::values())
            ],
            'grade_level' => [
                'sometimes',
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
            'grade_date' => 'sometimes|nullable|date',

        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'email.email' => 'Please provide a valid email address.',
            'file_number.unique' => 'This file number is already in use.',
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'national_id.unique' => 'This national ID is already registered.',
            'insurance_number.unique' => 'This insurance number is already in use.',
            'sector_id.exists' => 'The selected sector is invalid.',
            'category_group_id.exists' => 'The selected category group is invalid.',
            'contract_date.after_or_equal' => 'Contract date must be on or after hire date.',
            'joining_date.after_or_equal' => 'Joining date must be on or after contract date.',
            'grade.in' => 'Please select a valid grade.',
            'grade_level.in' => 'Please select a valid grade level.'
        ];
    }
}
