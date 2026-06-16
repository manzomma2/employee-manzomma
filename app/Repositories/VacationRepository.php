<?php

namespace App\Repositories;

use App\Interfaces\VacationRepositoryInterface;
use App\Models\Vacation;
use App\Models\VacationType;
use App\Traits\EmployeeFilterTrait;
use App\Traits\VacationTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class VacationRepository implements VacationRepositoryInterface
{
    use EmployeeFilterTrait;
    use VacationTrait;

    protected $model;
    protected array $relations = [
        'employee.latestAdministrationOrder.sector',
        'employee.latestAdministrationOrder.department.branch',
        'vacationType',
        'vacationHospital.hospital',
    ];

    public function __construct(Vacation $vacation)
    {
        $this->model = $vacation;
    }

    public function index($perPage, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with($this->relations);

        $this->applyEmployeeFilters($query, $filters, 'employee');

        return $query->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with($this->relations)->findOrFail($id);
    }

    public function employeePeriod(array $data)
    {
        return $this->model
            ->with($this->relations)
            ->where('employee_id', $data['employee_id'])
            ->whereDate('start_date', '<=', $data['to_date'])
            ->whereDate('end_date', '>=', $data['from_date'])
            ->latest('start_date')
            ->get();
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

            return $vacation->fresh($this->relations);
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

            return $vacation->fresh($this->relations);
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

            return $vacation->fresh($this->relations);
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

            return $vacation->fresh($this->relations);
        });
    }

    public function complete($id)
    {
        return DB::transaction(function () use ($id) {
            $vacation = $this->model->findOrFail($id);
            $endDate = Carbon::parse($vacation->end_date);

            if (! $endDate->isToday()) {
                throw ValidationException::withMessages([
                    'end_date' => 'Vacation can only be completed when the end date is today.',
                ]);
            }

            $vacation->update([
                'status' => 'completed',
            ]);

            return $vacation->fresh($this->relations);
        });
    }

    public function delete($id): bool
    {
        $vacation = $this->model->findOrFail($id);

        return $vacation->delete();
    }
}
