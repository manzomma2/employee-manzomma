<?php

namespace App\Repositories;

use App\Interfaces\VacationTypeRepositoryInterface;
use App\Models\VacationType;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationTypeRepository implements VacationTypeRepositoryInterface
{
    protected $model;

    public function __construct(VacationType $vacationType)
    {
        $this->model = $vacationType;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $vacationType = $this->model->findOrFail($id);
        $vacationType->update($data);

        return $vacationType->fresh();
    }

    public function delete($id): bool
    {
        $vacationType = $this->model->findOrFail($id);

        return $vacationType->delete();
    }
}
