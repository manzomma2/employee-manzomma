<?php

namespace App\Http\Requests;

use App\Enums\VacationTypeId;
use App\Models\Vacation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVacationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $vacation = null;
        $vacationTypeId = $this->input('vacation_type_id');

        if ($this->route('vacation')) {
            $vacation = Vacation::with('vacationHospital')->find($this->route('vacation'));
        }

        if ($vacationTypeId === null && $vacation) {
            $vacationTypeId = $vacation->vacation_type_id;
        }

        $isHospitalVacation = VacationTypeId::isHospital($vacationTypeId);
        $needsHospitalData = $isHospitalVacation && ! $vacation?->vacationHospital;

        return [
            'employee_id' => 'sometimes|required|exists:employees,id',
            'vacation_type_id' => 'sometimes|required|exists:vacation_types,id',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'pre_end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'extension_notes' => 'nullable|string',
            'cut_note' => 'nullable|string',
            'status' => ['sometimes', 'required', Rule::in(['active', 'scedual', 'completed'])],
            'hospital_id' => [
                Rule::requiredIf($needsHospitalData),
                'exists:hospitals,id',
            ],
            'diagnoses' => [
                Rule::requiredIf($needsHospitalData),
                'string',
            ],
        ];
    }
}
