<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->model
            ->with(['role', 'sector'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function show($id)
    {
        return $this->model
            ->with(['role', 'sector'])
            ->findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->model->create($data)->load(['role', 'sector']);
    }

    public function update($id, array $data)
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);

        return $user->fresh()->load(['role', 'sector']);
    }

    public function delete($id): bool
    {
        $user = $this->model->findOrFail($id);

        return $user->delete();
    }
}
