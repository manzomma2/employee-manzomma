<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'vacation_type_id' => $this->vacation_type_id,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'pre_end_date' => $this->pre_end_date ? $this->pre_end_date->format('Y-m-d') : null,
            'color' => $this->color,
            'notes' => $this->notes,
            'extension_notes' => $this->extension_notes,
            'cut_note' => $this->cut_note,
            'returning' => $this->returning,
            'status' => $this->status,
            'employee' => $this->whenLoaded('employee', function () {
                return new EmployeeResource($this->employee);
            }),
            'vacation_type' => $this->whenLoaded('vacationType', function () {
                return new VacationTypeResource($this->vacationType);
            }),
            'vacation_hospital' => $this->whenLoaded('vacationHospital', function () {
                return new VacationHospitalResource($this->vacationHospital);
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
