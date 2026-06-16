<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeVacationSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job_title' => $this->job_title,
            'phone' => $this->phone,
            'current_administration_order' => $this->whenLoaded('latestAdministrationOrder', function () {
                return $this->latestAdministrationOrder
                    ? new AdministrationOrderResource($this->latestAdministrationOrder)
                    : null;
            }),
            'current_active_vacation' => $this->whenLoaded('currentVacation', function () {
                return $this->currentVacation ? new VacationResource($this->currentVacation) : null;
            }),
            'scheduled_vacation' => $this->whenLoaded('scheduledVacation', function () {
                return $this->scheduledVacation ? new VacationResource($this->scheduledVacation) : null;
            }),
            'last_completed_vacation' => $this->whenLoaded('lastCompletedVacation', function () {
                return $this->lastCompletedVacation ? new VacationResource($this->lastCompletedVacation) : null;
            }),
        ];
    }
}
