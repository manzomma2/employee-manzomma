<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationHospitalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vacation_id' => $this->vacation_id,
            'hospital_id' => $this->hospital_id,
            'diagnoses' => $this->diagnoses,
            'hospital' => $this->whenLoaded('hospital', function () {
                return new HospitalResource($this->hospital);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
