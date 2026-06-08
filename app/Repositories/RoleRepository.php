<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model
            ->with('users')
            ->withCount('users as users_count')
            ->latest('id')
            ->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model
            ->with('users')
            ->withCount('users as users_count')
            ->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data)->load('users')->loadCount('users as users_count');
    }

    public function update($id, array $data)
    {
        $role = $this->model->findOrFail($id);
        $role->update($data);

        return $role->fresh()->load('users')->loadCount('users as users_count');
    }

    public function delete($id): bool
    {
        $role = $this->model->findOrFail($id);

        return $role->delete();
    }
}
