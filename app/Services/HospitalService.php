<?php

namespace App\Services;

use App\Interfaces\HospitalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class HospitalService
{
    protected $hospitalRepository;

    public function __construct(HospitalRepositoryInterface $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->hospitalRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->hospitalRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->hospitalRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->hospitalRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->hospitalRepository->delete($id);
    }
}
