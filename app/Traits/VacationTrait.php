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
        $requestedStartDate = Carbon::parse($data['start_date'])->startOfDay();
        $requestedEndDate = Carbon::parse($data['end_date'])->startOfDay();
        $today = Carbon::today();

        if ($requestedStartDate->lt($today)) {
            throw ValidationException::withMessages([
                'start_date' => 'You cannot create a vacation in a previous period.',
            ]);
        }

        if ($this->vacationExistsInPeriod($data['employee_id'], $requestedStartDate, $requestedEndDate)) {
            throw ValidationException::withMessages([
                'start_date' => 'This employee already has a vacation in this period time.',
            ]);
        }

        $currentVacation = $this->currentActiveVacationForEmployee($data['employee_id']);

        if (! $currentVacation) {
            $data['status'] = 'active';

            return $data;
        }

        if ($requestedStartDate->lte($currentVacation->end_date)) {
            throw ValidationException::withMessages([
                'start_date' => 'The next vacation must start after the active vacation end date.',
            ]);
        }

        if ($requestedStartDate->gt($currentVacation->end_date)) {
            if ($this->scheduledVacationForEmployee($data['employee_id'])) {
                throw ValidationException::withMessages([
                    'start_date' => 'This employee already has a scheduled future vacation.',
                ]);
            }

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

    protected function vacationExistsInPeriod($employeeId, Carbon $startDate, Carbon $endDate): bool
    {
        return Vacation::where('employee_id', $employeeId)
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->exists();
    }

    protected function scheduledVacationForEmployee($employeeId): ?Vacation
    {
        return Vacation::where('employee_id', $employeeId)
            ->where('status', 'scedual')
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
