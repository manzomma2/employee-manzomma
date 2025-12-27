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
            'training_courses_start_dates.*' => 'sometimes|nullable|date',
            'training_courses_end_dates.*' => 'sometimes|nullable|date',
            'training_courses_ids.*' => 'sometimes|nullable|exists:training_courses,id',
            'deduction_values' => 'sometimes|array',
            'deduction_reasons' => 'sometimes|array',
            'deduction_dates' => 'sometimes|array',
            'deduction_ids' => 'sometimes|array',
            'deduction_values.*' => 'sometimes|nullable|numeric|min:0|max:999999.99',
            'deduction_reasons.*' => 'sometimes|nullable|string|max:255',
            'deduction_dates.*' => 'sometimes|nullable|date',
            'deduction_ids.*' => 'sometimes|nullable|exists:deductions,id',
            'evaluation_from_dates' => 'sometimes|array',
            'evaluation_to_dates' => 'sometimes|array',
            'evaluation_degrees' => 'sometimes|array',
            'evaluation_ratings' => 'sometimes|array',
            'evaluation_ids' => 'sometimes|array',
            'evaluation_from_dates.*' => 'sometimes|nullable|date',
            'evaluation_to_dates.*' => 'sometimes|nullable|date|after_or_equal:evaluation_from_dates.*',
            'evaluation_degrees.*' => 'sometimes|nullable|string|max:255',
            'evaluation_ratings.*' => 'sometimes|nullable|string|max:255',
            'evaluation_ids.*' => 'sometimes|nullable|exists:performance_evaluations,id',
            'settlement_decisions' => 'sometimes|array',
            'settlement_dates' => 'sometimes|array',
            'settlement_ids' => 'sometimes|array',
            'settlement_decisions.*' => 'sometimes|nullable|string|max:255',
            'settlement_dates.*' => 'sometimes|nullable|date',
            'settlement_ids.*' => 'sometimes|nullable|exists:settlements,id',
            'bonus_numbers' => 'sometimes|array',
            'bonus_values' => 'sometimes|array',
            'bonus_dates' => 'sometimes|array',
            'bonus_decisions' => 'sometimes|array',
            'bonus_ids' => 'sometimes|array',
            'bonus_numbers.*' => 'sometimes|nullable|string|max:255',
            'bonus_values.*' => 'sometimes|nullable|numeric|min:0|max:999999.99',
            'bonus_dates.*' => 'sometimes|nullable|date',
            'bonus_decisions.*' => 'sometimes|nullable|string|max:255',
            'bonus_ids.*' => 'sometimes|nullable|exists:bonuses,id',
            'incentive_numbers' => 'sometimes|array',
            'incentive_decisions' => 'sometimes|array',
            'incentive_values' => 'sometimes|array',
            'incentive_dates' => 'sometimes|array',
            'incentive_ids' => 'sometimes|array',
            'incentive_numbers.*' => 'sometimes|nullable|string|max:255',
            'incentive_decisions.*' => 'sometimes|nullable|string|max:255',
            'incentive_values.*' => 'sometimes|nullable|numeric|min:0|max:999999.99',
            'incentive_dates.*' => 'sometimes|nullable|date',
            'incentive_ids.*' => 'sometimes|nullable|exists:incentives,id',
            
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
            'type.in' => 'Please select a valid gender.',
            'deduction_values.numeric' => 'Deduction value must be a number',
            'deduction_values.min' => 'Deduction value cannot be negative',
            'deduction_values.max' => 'Deduction value cannot exceed 999,999.99',
            'deduction_reasons.max' => 'Deduction reason must not exceed 255 characters',
            'deduction_ids.exists' => 'Invalid deduction ID selected',
            'evaluation_degrees.max' => 'Evaluation degree must not exceed 255 characters',
            'evaluation_ratings.max' => 'Evaluation rating must not exceed 255 characters',
            'evaluation_ids.exists' => 'Invalid evaluation ID selected',
            'settlement_decisions.max' => 'Settlement decision must not exceed 255 characters',
            'settlement_ids.exists' => 'Invalid settlement ID selected',
            'bonus_numbers.max' => 'Bonus number must not exceed 255 characters',
            'bonus_values.numeric' => 'Bonus value must be a number',
            'bonus_values.min' => 'Bonus value cannot be negative',
            'bonus_values.max' => 'Bonus value cannot exceed 999,999.99',
            'bonus_decisions.max' => 'Bonus decision must not exceed 255 characters',
            'bonus_ids.exists' => 'Invalid bonus ID selected',
            'incentive_numbers.max' => 'Incentive number must not exceed 255 characters',
            'incentive_decisions.max' => 'Incentive decision must not exceed 255 characters',
            'incentive_values.numeric' => 'Incentive value must be a number',
            'incentive_values.min' => 'Incentive value cannot be negative',
            'incentive_values.max' => 'Incentive value cannot exceed 999,999.99',
            'incentive_ids.exists' => 'Invalid incentive ID selected'
        ];
    }
}
