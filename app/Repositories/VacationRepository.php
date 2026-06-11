<?php

namespace App\Repositories;

use App\Interfaces\VacationRepositoryInterface;
use App\Models\Vacation;
use App\Models\VacationType;
use App\Traits\VacationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationRepository implements VacationRepositoryInterface
{
    use VacationTrait;

    protected $model;

    public function __construct(Vacation $vacation)
    {
        $this->model = $vacation;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model
            ->with(['employee', 'vacationType', 'vacationHospital.hospital'])
            ->latest()
            ->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with(['employee', 'vacationType', 'vacationHospital.hospital'])->findOrFail($id);
    }

    public function store(array $data)
    {
        $data = $this->prepareVacationDataForStore($data);
        $hospitalData = $this->extractHospitalData($data);

        return DB::transaction(function () use ($data, $hospitalData) {
            $vacation = $this->model->create($data);

            if ($this->isHospitalVacation($vacation->vacation_type_id)) {
                $vacation->vacationHospital()->create($hospitalData);
            }

            return $vacation->fresh(['employee', 'vacationType', 'vacationHospital.hospital']);
        });
    }

    public function update($id, array $data)
    {
        $hospitalData = $this->extractHospitalData($data);

        return DB::transaction(function () use ($id, $data, $hospitalData) {
            $vacation = $this->model->with('vacationHospital')->findOrFail($id);
            $vacation->update($data);

            if ($this->isHospitalVacation($vacation->vacation_type_id)) {
                $vacation->vacationHospital()->updateOrCreate(
                    ['vacation_id' => $vacation->id],
                    $hospitalData
                );
            } else {
                $vacation->vacationHospital?->delete();
            }

            return $vacation->fresh(['employee', 'vacationType', 'vacationHospital.hospital']);
        });
    }

    public function delete($id): bool
    {
        $vacation = $this->model->findOrFail($id);

        return $vacation->delete();
    }
}
