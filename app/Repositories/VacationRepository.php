<?php

namespace App\Repositories;

use App\Interfaces\VacationRepositoryInterface;
use App\Models\Employee;
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
    protected $employeeModel;
    protected array $relations = [
        'employee.latestAdministrationOrder.sector',
        'employee.latestAdministrationOrder.department.branch',
        'vacationType',
        'vacationHospital.hospital',
    ];

    protected array $employeeVacationSummaryRelations = [
        'latestAdministrationOrder.sector',
        'latestAdministrationOrder.department.branch',
        'currentVacation.vacationType',
        'currentVacation.vacationHospital.hospital',
        'scheduledVacation.vacationType',
        'scheduledVacation.vacationHospital.hospital',
        'lastCompletedVacation.vacationType',
        'lastCompletedVacation.vacationHospital.hospital',
    ];
    public function __construct(Vacation $vacation, Employee $employee)
    {
        $this->model = $vacation;
        $this->employeeModel = $employee;
    }

    public function index($perPage, array $filters = []): LengthAwarePaginator
    {
        $query = $this->employeeModel
            ->select(['id', 'first_name', 'last_name', 'job_title', 'phone'])
            ->with($this->employeeVacationSummaryRelations);

        $this->applyEmployeeFilters($query, $filters);
        // Apply vacation type filter if provided (get just the officer with the specific vacation type)
        $vacationTypeIds = $this->filterValues($filters, 'vacation_type_ids');
        if ($vacationTypeIds) {
            $query->whereHas('vacations', function ($query) use ($vacationTypeIds) {
                $query->whereIn('vacation_type_id', $vacationTypeIds);
                $query->where('status', 'active');
            });
        }
        return $query->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with($this->relations)->findOrFail($id);
    }

    public function stats(array $filters = []): array
    {
        $employeeQuery = $this->employeeModel->newQuery();
        $this->applyEmployeeFilters($employeeQuery, $filters);

        $total = (clone $employeeQuery)->count();

        $employeeIdsQuery = (clone $employeeQuery)->select('id');

        $today = Carbon::today()->toDateString();

        $activeVacationsQuery = $this->model
            ->where('status', 'active')
            ->whereIn('employee_id', $employeeIdsQuery)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);

        $activeVacationCounts = (clone $activeVacationsQuery)
            ->select(
                'vacation_type_id',
                DB::raw('COUNT(DISTINCT employee_id) as employees_count')
            )
            ->groupBy('vacation_type_id')
            ->pluck('employees_count', 'vacation_type_id');

        $activeEmployeesCount = (clone $activeVacationsQuery)
            ->distinct()
            ->count('employee_id');

        $present = max(0, $total - $activeEmployeesCount);
        return [
            'total' => $total,
            'present' => $present,
            'outside' => $activeEmployeesCount,
            'vacation_types' => VacationType::query()
                ->orderBy('id')
                ->get(['id', 'name', 'color'])
                ->map(fn (VacationType $vacationType) => [
                    'id' => $vacationType->id,
                    'name' => $vacationType->name,
                    'color' => $vacationType->color,
                    'count' => (int) ($activeVacationCounts[$vacationType->id] ?? 0),
                ])
                ->values(),
        ];
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
