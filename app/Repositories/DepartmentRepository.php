<?php

namespace App\Repositories;

use App\Interfaces\AdministrationOrderRepositoryInterface;
use App\Models\AdministrationOrder;
use Illuminate\Pagination\LengthAwarePaginator;

class AdministrationOrderRepository implements AdministrationOrderRepositoryInterface
{
    protected $model;

    public function __construct(AdministrationOrder $administrationOrder)
    {
        $this->model = $administrationOrder;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model->with(['officer', 'sector', 'department'])->latest()->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model->with(['officer', 'sector', 'department'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $order = $this->model->findOrFail($id);
        $order->update($data);
        return $order->fresh();
    }

    public function delete($id): bool
    {
        $order = $this->model->findOrFail($id);
        return $order->delete();
    }
}
