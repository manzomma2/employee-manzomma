<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerProgressionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_grade' => $this->job_grade,
            'job_level' => $this->job_level,
            'grade_effective_date' => $this->grade_effective_date->format('Y-m-d'),
            'grade_decision_number' => $this->grade_decision_number,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
