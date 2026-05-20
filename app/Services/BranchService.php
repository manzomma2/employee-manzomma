<?php

namespace App\Services;

use App\Interfaces\BranchRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchService
{
    protected $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->branchRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->branchRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->branchRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->branchRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->branchRepository->delete($id);
    }
}
