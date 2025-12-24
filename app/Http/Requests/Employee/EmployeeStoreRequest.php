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
            'national_id' => 'nullable|string|max:50|unique:employees,national_id',
            'insurance_number' => 'nullable|string|max:50|unique:employees,insurance_number',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'contract_date' => 'nullable|date|after_or_equal:hire_date',
            'joining_date' => 'nullable|date|after_or_equal:contract_date',
            'marital_status' => ['nullable', 'string', Rule::in(['عزباء', 'أعزب', 'متزوجة', 'متزوج', 'مطلقة', 'مطلق', 'أرملة', 'أرمل'])],
            'religion' => ['nullable', 'string', Rule::in(['مسلم', 'مسيحي'])],
            'address' => 'nullable|string|max:1000',
            'academic_qualification' => 'nullable|string|max:100',
            'academic_specialization' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date|before_or_equal:today',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'appointment_decision_number' => 'nullable|string|max:100',
            'type' => ['nullable', 'string', Rule::in(['ذكر', 'انثى'])],
            'job_grades' => 'array',
            'job_levels' => 'array',
            'grade_effective_dates' => 'array',
            'grade_decision_numbers' => 'array',
            'job_grades.*' => ['nullable', 'string', Rule::in(Grade::values())],
            'job_levels.*' => ['nullable', 'string', Rule::in(GradeLevel::values())],
            'grade_effective_dates.*' => 'nullable|date|before_or_equal:today',
            'grade_decision_numbers.*' => 'nullable|string|max:100',
            'training_courses_names' => 'array',
            'training_courses_start_dates' => 'array',
            'training_courses_end_dates' => 'array',
            'training_courses_names.*' => 'nullable|string|max:255',
            'training_courses_start_dates.*' => 'nullable|date|before_or_equal:today',
            'training_courses_end_dates.*' => 'nullable|date|after_or_equal:training_courses_start_dates.*',
        ];
    }
    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'email.email' => 'Please provide a valid email address.',
            'file_number.unique' => 'This file number is already in use.',
            'photo.mimes' => 'Photo must be a valid image file (jpeg, png, jpg, gif).',
            'photo.max' => 'Photo size must not exceed 2MB.',
            'sector_id.exists' => 'The selected sector is invalid.',
            'category_group_id.exists' => 'The selected category group is invalid.',
            'national_id.unique' => 'This national ID is already registered.',
            'insurance_number.unique' => 'This insurance number is already registered.',
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
            'type.in' => 'Please select a valid gender.',
            'job_grades.in' => 'Invalid job grade value',
            'job_levels.in' => 'Invalid job level value',
            'grade_effective_dates.before_or_equal' => 'Effective date cannot be in the future',
            'grade_decision_numbers.max' => 'Decision number must not exceed 100 characters',
            
        ];
    }
}
