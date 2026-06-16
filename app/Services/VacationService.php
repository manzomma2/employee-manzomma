<?php

namespace App\Services;

use App\Interfaces\VacationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationService
{
    protected $vacationRepository;

    public function __construct(VacationRepositoryInterface $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function index($perPage, array $filters = []): LengthAwarePaginator
    {
        return $this->vacationRepository->index($perPage, $filters);
    }

    public function show($id)
    {
        return $this->vacationRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->vacationRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->vacationRepository->update($id, $data);
    }

    public function cut($id, array $data)
    {
        return $this->vacationRepository->cut($id, $data);
    }

    public function extend($id, array $data)
    {
        return $this->vacationRepository->extend($id, $data);
    }

    public function complete($id)
    {
        return $this->vacationRepository->complete($id);
    }

    public function delete($id): bool
    {
        return $this->vacationRepository->delete($id);
    }
}
