<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
        $this->middleware('permission:departments,list_view')->only('index');
        $this->middleware('permission:departments,detailed_view')->only('show');
        $this->middleware('permission:departments,create')->only('store');
        $this->middleware('permission:departments,update')->only('update');
        $this->middleware('permission:departments,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $departments = $this->departmentService->index($perPage);
        return response()->json([
            'status' => 'success',
            'data' => DepartmentResource::collection($departments)
        ]);
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->departmentService->store($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Department created successfully',
            'data' => new DepartmentResource($department->load('branch'))
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $department = $this->departmentService->show($id);
        return response()->json([
            'status' => 'success',
            'data' => new DepartmentResource($department)
        ]);
    }

    public function update(UpdateDepartmentRequest $request, $id): JsonResponse
    {
        $department = $this->departmentService->update($id, $request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Department updated successfully',
            'data' => new DepartmentResource($department->load('branch'))
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->departmentService->delete($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Department deleted successfully'
        ], 204);
    }
}
