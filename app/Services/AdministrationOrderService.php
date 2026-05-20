<?php

namespace App\Services;

use App\Interfaces\AdministrationOrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AdministrationOrderService
{
    protected $administrationOrderRepository;

    public function __construct(AdministrationOrderRepositoryInterface $administrationOrderRepository)
    {
        $this->administrationOrderRepository = $administrationOrderRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->administrationOrderRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->administrationOrderRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->administrationOrderRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->administrationOrderRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->administrationOrderRepository->delete($id);
    }
}
