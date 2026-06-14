<?php

namespace App\Http\Requests;

use App\Enums\VacationTypeId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVacationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'vacation_type_id' => 'required|exists:vacation_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pre_end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'extension_notes' => 'nullable|string',
            'cut_note' => 'nullable|string',
            'returning' => 'nullable|integer|between:0,1',
            'status' => ['required', Rule::in(['active', 'scedual', 'completed'])],
            'hospital_id' => [
                Rule::requiredIf(VacationTypeId::isHospital($this->input('vacation_type_id'))),
                'exists:hospitals,id',
            ],
            'diagnoses' => [
                Rule::requiredIf(VacationTypeId::isHospital($this->input('vacation_type_id'))),
                'string',
            ],
        ];
    }
}
