<?php

namespace App\Repositories;

use App\Interfaces\DepartmentRepositoryInterface;
use App\Models\Department;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected $model;

    public function __construct(Department $department)
    {
        $this->model = $department;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model->with('branch')->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with('branch')->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $department = $this->model->findOrFail($id);
        $department->update($data);
        return $department->fresh();
    }

    public function delete($id): bool
    {
        $department = $this->model->findOrFail($id);
        return $department->delete();
    }
}
