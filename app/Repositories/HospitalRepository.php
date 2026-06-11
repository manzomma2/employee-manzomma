<?php

namespace App\Repositories;

use App\Interfaces\HospitalRepositoryInterface;
use App\Models\Hospital;
use Illuminate\Pagination\LengthAwarePaginator;

class HospitalRepository implements HospitalRepositoryInterface
{
    protected $model;

    public function __construct(Hospital $hospital)
    {
        $this->model = $hospital;
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
        $hospital = $this->model->findOrFail($id);
        $hospital->update($data);

        return $hospital->fresh();
    }

    public function delete($id): bool
    {
        $hospital = $this->model->findOrFail($id);

        return $hospital->delete();
    }
}
