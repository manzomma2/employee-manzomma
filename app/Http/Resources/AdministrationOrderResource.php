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
            'work_job' => $this->work_job,
            'order_date' => $this->order_date,
            'inform_date' => $this->inform_date,
            'transfer_date' => $this->transfer_date,
            'combine_date' => $this->combine_date,
            'active' => $this->active,
            'employee' => $this->whenLoaded('employee', function () {
                return new EmployeeResource($this->employee);
            }),
            'sector' => $this->whenLoaded('sector', function () {
                return new SectorResource($this->sector);
            }),
            'department' => $this->whenLoaded('department', function () {
                return new DepartmentResource($this->department);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
