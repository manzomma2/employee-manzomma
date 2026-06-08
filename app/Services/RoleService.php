<?php

namespace App\Services;

use App\Interfaces\RoleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->roleRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->roleRepository->show($id);
    }

    public function store(array $data)
    {
        $data['permissions'] = $data['permissions'] ?? [];

        return $this->roleRepository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->roleRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->roleRepository->delete($id);
    }
}
