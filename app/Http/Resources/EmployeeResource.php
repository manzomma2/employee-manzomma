<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'file_number' => $this->file_number,
            'job_title' => $this->job_title,
            'national_id' => $this->national_id,
            'insurance_number' => $this->insurance_number,
            'sector' => $this->whenLoaded('sector', function () {
                return new SectorResource($this->sector);
            }),
            'category_group' => $this->whenLoaded('categoryGroup', function () {
                return new CategoryGroupResource($this->categoryGroup);
            }),
            'photo' => $this->photo,
            'phone' => $this->phone,
            'hire_date' => $this->hire_date ? $this->hire_date->format('Y-m-d') : null,
            'contract_date' => $this->contract_date ? $this->contract_date->format('Y-m-d') : null,
            'joining_date' => $this->joining_date ? $this->joining_date->format('Y-m-d') : null,
            'grade' => $this->grade,
            'grade_level' => $this->grade_level,
            'grade_date' => $this->grade_date ? $this->grade_date->format('Y-m-d') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at' => $this->when($this->deleted_at, function () {
                return $this->deleted_at->format('Y-m-d H:i:s');
            }),
        ];
    }
}
