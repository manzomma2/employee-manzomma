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

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model->with(['sector', 'categoryGroup'])->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with(['sector', 'categoryGroup'])->findOrFail($id);
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