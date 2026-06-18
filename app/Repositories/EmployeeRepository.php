<?php

namespace App\Repositories;

use App\Interfaces\EmployeeRepositoryInterface;
use App\Models\Employee;
use App\Traits\EmployeeFilterTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    use EmployeeFilterTrait;

    protected $model;
    protected array $relations = [
        'sector','categoryGroup','careerProgressions','trainingCourses',
        'deductions','performanceEvaluations','settlements','bonuses','incentives',
        'latestAdministrationOrder.sector','latestAdministrationOrder.department.branch',
        'administrationOrders.sector','administrationOrders.department.branch','currentVacation.vacationType',
        'currentVacation.vacationHospital.hospital'
    ];
    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    public function index($perPage, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with($this->relations);

        $this->applyEmployeeFilters($query, $filters);

        return $query->latest()->paginate($perPage);
    }

    public function show($id)
    {
        $query = $this->model->with($this->relations);
        $this->applyAuthenticatedUserSectorFilter($query);

        return $query->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $query = $this->model->newQuery();
        $this->applyAuthenticatedUserSectorFilter($query);

        $employee = $query->findOrFail($id);
        $employee->update($data);
        return $employee->fresh();
    }

    public function delete($id): bool
    {
        $query = $this->model->newQuery();
        $this->applyAuthenticatedUserSectorFilter($query);

        $employee = $query->findOrFail($id);
        return $employee->delete();
    }
}
