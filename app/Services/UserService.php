<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->userRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->userRepository->show($id);
    }

    public function store(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->store($data);
    }

    public function update($id, array $data)
    {
        if (array_key_exists('password', $data)) {
            if ($data['password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
        }

        return $this->userRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->userRepository->delete($id);
    }
}
