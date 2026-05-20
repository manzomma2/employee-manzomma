<?php

namespace App\Services;

use App\Interfaces\DepartmentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->departmentRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->departmentRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->departmentRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->departmentRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->departmentRepository->delete($id);
    }
}
