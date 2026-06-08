<?php

namespace App\Services;

use App\Interfaces\VacationTypeRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationTypeService
{
    protected $vacationTypeRepository;

    public function __construct(VacationTypeRepositoryInterface $vacationTypeRepository)
    {
        $this->vacationTypeRepository = $vacationTypeRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->vacationTypeRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->vacationTypeRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->vacationTypeRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->vacationTypeRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->vacationTypeRepository->delete($id);
    }
}
