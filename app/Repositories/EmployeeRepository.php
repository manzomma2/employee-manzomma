<?php

namespace App\Repositories;

use App\Interfaces\EmployeeRepositoryInterface;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected $model;

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    public function index($perPage, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with(['sector', 'categoryGroup','careerProgressions','trainingCourses','deductions','performanceEvaluations','settlements','bonuses','incentives','latestAdministrationOrder.sector','latestAdministrationOrder.department.branch','administrationOrders.sector','administrationOrders.department.branch','currentVacation.vacationType','currentVacation.vacationHospital.hospital']);

        if (! empty($filters['sector_id'])) {
            $query->whereHas('latestAdministrationOrder', function ($query) use ($filters) {
                $query->where('sector_id', $filters['sector_id']);
            });
        }

        if (! empty($filters['department_id'])) {
            $query->whereHas('latestAdministrationOrder', function ($query) use ($filters) {
                $query->where('department_id', $filters['department_id']);
            });
        }

        if (! empty($filters['branch_id'])) {
            $query->whereHas('latestAdministrationOrder.department', function ($query) use ($filters) {
                $query->where('branch_id', $filters['branch_id']);
            });
        }

        if (! empty($filters['category_group_id'])) {
            $query->whereHas('categoryGroup', function ($query) use ($filters) {
                $query->where('category_group_id', $filters['category_group_id']);
            });
        }

        if (! empty($filters['religion'])) {
            $query->where('religion', $filters['religion']);
        }

        if (! empty($filters['name'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('first_name', 'like', '%' . $filters['name'] . '%')
                    ->orWhere('last_name', 'like', '%' . $filters['name'] . '%');
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with(['sector', 'categoryGroup','careerProgressions','trainingCourses','deductions','performanceEvaluations','settlements','bonuses','incentives','latestAdministrationOrder.sector','latestAdministrationOrder.department.branch','administrationOrders.sector','administrationOrders.department.branch','currentVacation.vacationType','currentVacation.vacationHospital.hospital'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $employee = $this->model->findOrFail($id);
        $employee->update($data);
        return $employee->fresh();
    }

    public function delete($id): bool
    {
        $employee = $this->model->findOrFail($id);
        return $employee->delete();
    }
}
