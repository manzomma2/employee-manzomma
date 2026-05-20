<?php

namespace App\Repositories;

use App\Interfaces\BranchRepositoryInterface;
use App\Models\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchRepository implements BranchRepositoryInterface
{
    protected $model;

    public function __construct(Branch $branch)
    {
        $this->model = $branch;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model->with('departments')->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with('departments')->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $branch = $this->model->findOrFail($id);
        $branch->update($data);
        return $branch->fresh();
    }

    public function delete($id): bool
    {
        $branch = $this->model->findOrFail($id);
        return $branch->delete();
    }
}
