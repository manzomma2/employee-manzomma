<?php

namespace App\Repositories;

use App\Interfaces\VacationRepositoryInterface;
use App\Models\Vacation;
use App\Models\VacationType;
use App\Traits\VacationTrait;
use Illuminate\Support\Carbon;
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

    public function cut($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $vacation = $this->model->findOrFail($id);
            $cutDate = Carbon::parse($data['cut_date']);
            $oldEndDate = Carbon::parse($vacation->end_date);

            $vacation->update([
                'pre_end_date' => $vacation->end_date,
                'end_date' => $cutDate->toDateString(),
                'cut_note' => 'قطع اجازة فى تاريخ ' . $cutDate->format('Y-m-d H:i') . ' بدلا من  ' . $oldEndDate->format('Y-m-d H:i:s'),
                'status' => 'completed',
            ]);

            return $vacation->fresh(['employee', 'vacationType', 'vacationHospital.hospital']);
        });
    }

    public function extend($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $vacation = $this->model->findOrFail($id);
            $extensionDate = Carbon::parse($data['extension_date']);
            $oldEndDate = Carbon::parse($vacation->end_date);

            $vacation->update([
                'pre_end_date' => $vacation->end_date,
                'end_date' => $extensionDate->toDateString(),
                'extension_notes' => 'امتداد من ' . $oldEndDate->format('Y-m-d') . ' الى ' . $extensionDate->format('Y-m-d'),
            ]);

            return $vacation->fresh(['employee', 'vacationType', 'vacationHospital.hospital']);
        });
    }

    public function delete($id): bool
    {
        $vacation = $this->model->findOrFail($id);

        return $vacation->delete();
    }
}
