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
            'marital_status' => ['sometimes', 'nullable', 'string', Rule::in(['عزباء', 'أعزب', 'متزوجة', 'متزوج', 'مطلقة', 'مطلق', 'أرملة', 'أرمل'])],
            'religion' => ['sometimes', 'nullable', 'string', Rule::in(['مسلم', 'مسيحي'])],
            'address' => 'sometimes|nullable|string|max:1000',
            'academic_qualification' => 'sometimes|nullable|string|max:100',
            'academic_specialization' => 'sometimes|nullable|string|max:255',
            'graduation_date' => 'sometimes|nullable|date|before_or_equal:today',
            'birth_date' => 'sometimes|nullable|date|before_or_equal:today',
            'appointment_decision_number' => 'sometimes|nullable|string|max:100',
            'type' => ['sometimes', 'nullable', 'string', Rule::in(['ذكر', 'انثى'])],
            'job_grades' => 'sometimes|array',
            'job_levels' => 'sometimes|array',
            'grade_effective_dates' => 'sometimes|array',
            'grade_decision_numbers' => 'sometimes|array',
            'job_grades.*' => ['sometimes', 'nullable', 'string', Rule::in(Grade::values())],
            'job_levels.*' => ['sometimes', 'nullable', 'string', Rule::in(GradeLevel::values())],
            'grade_effective_dates.*' => 'sometimes|nullable|date|before_or_equal:today',
            'grade_decision_numbers.*' => 'sometimes|nullable|string|max:100',
            'training_courses_names' => 'sometimes|array',
            'training_courses_start_dates' => 'sometimes|array',
            'training_courses_end_dates' => 'sometimes|array',
            'training_courses_ids' => 'sometimes|array',
            'training_courses_names.*' => 'sometimes|nullable|string|max:255',
            'training_courses_start_dates.*' => 'sometimes|nullable|date|before_or_equal:today',
            'training_courses_end_dates.*' => 'sometimes|nullable|date|after_or_equal:training_courses_start_dates.*',
            'training_courses_ids.*' => 'sometimes|nullable|exists:training_courses,id',
            
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
            'marital_status.in' => 'Please select a valid marital status.',
            'religion.in' => 'Please select a valid religion.',
            'address.max' => 'Address must not exceed 1000 characters.',
            'academic_qualification.max' => 'Academic qualification must not exceed 100 characters.',
            'academic_specialization.max' => 'Academic specialization must not exceed 255 characters.',
            'graduation_date.date' => 'Please enter a valid graduation date.',
            'graduation_date.before_or_equal' => 'Graduation date cannot be in the future.',
            'birth_date.date' => 'Please enter a valid birth date.',
            'birth_date.before_or_equal' => 'Birth date cannot be in the future.',
            'appointment_decision_number.max' => 'Appointment decision number must not exceed 100 characters.',
            'type.in' => 'Please select a valid gender.'
        ];
    }
}
