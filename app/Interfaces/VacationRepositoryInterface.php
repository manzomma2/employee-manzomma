<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface VacationRepositoryInterface
{
    public function index($perPage, array $filters = []): LengthAwarePaginator;
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function cut($id, array $data);
    public function extend($id, array $data);
    public function complete($id);
    public function delete($id): bool;
}
