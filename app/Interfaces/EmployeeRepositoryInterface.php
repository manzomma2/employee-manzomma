<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface EmployeeRepositoryInterface
{
    public function index($perPage): LengthAwarePaginator;
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id): bool;
}