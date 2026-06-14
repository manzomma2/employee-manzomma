<?php

namespace App\Traits;

use App\Enums\VacationTypeId;
use App\Models\Vacation;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

trait VacationTrait
{
    protected function prepareVacationDataForStore(array $data): array
    {
        $currentVacation = $this->currentActiveVacationForEmployee($data['employee_id']);

        if (! $currentVacation) {
            return $data;
        }

        $requestedStartDate = Carbon::parse($data['start_date']);

        if (
            $requestedStartDate->gte($currentVacation->start_date)
            && $requestedStartDate->lte($currentVacation->end_date)
        ) {
            throw ValidationException::withMessages([
                'start_date' => 'This employee has an active vacation in this period time.',
            ]);
        }

        if ($requestedStartDate->gt($currentVacation->end_date)) {
            $data['status'] = 'scedual';
        }

        return $data;
    }

    protected function currentActiveVacationForEmployee($employeeId): ?Vacation
    {
        return Vacation::where('employee_id', $employeeId)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();
    }
    protected function extractHospitalData(array &$data): array
    {
        $hospitalData = [
            'hospital_id' => $data['hospital_id'] ?? null,
            'diagnoses' => $data['diagnoses'] ?? null,
        ];

        unset($data['hospital_id'], $data['diagnoses']);

        return array_filter($hospitalData, fn ($value) => $value !== null);
    }

    protected function isHospitalVacation($vacationTypeId): bool
    {
        return VacationTypeId::isHospital($vacationTypeId);
    }
}
