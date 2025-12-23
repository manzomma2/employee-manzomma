<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $employees = $this->employeeService->index($perPage);
        return response()->json([
            'status' => 'success',
            'data' => EmployeeResource::collection($employees)
        ]);
    }

    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $employee = $this->employeeService->store($validated);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully',
            'data' => new EmployeeResource($employee)
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $employee = $this->employeeService->show($id);
        return response()->json([
            'status' => 'success',
            'data' => new EmployeeResource($employee)
        ]);
    }

    public function update(EmployeeUpdateRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $employee = $this->employeeService->update($id, $validated);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully',
            'data' => new EmployeeResource($employee)
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->employeeService->delete($id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully'
        ], 204);
    }
}