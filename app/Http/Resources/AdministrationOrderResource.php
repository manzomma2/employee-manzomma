<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdministrationOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'sector_id' => $this->sector_id,
            'department_id' => $this->department_id,
            'work_job' => $this->work_job,
            'order_date' => $this->order_date,
            'inform_date' => $this->inform_date,
            'transfer_date' => $this->transfer_date,
            'combine_date' => $this->combine_date,
            'active' => $this->active,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'sector' => new SectorResource($this->whenLoaded('sector')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
