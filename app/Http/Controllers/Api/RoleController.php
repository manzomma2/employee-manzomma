<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->middleware('permission:roles,list_view')->only('index');
        $this->middleware('permission:roles,detailed_view')->only('show');
        $this->middleware('permission:roles,create')->only('store');
        $this->middleware('permission:roles,update')->only('update');
        $this->middleware('permission:roles,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $roles = $this->roleService->index($perPage);

        return response()->json([
            'status' => 'success',
            'data' => RoleResource::collection($roles),
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'data' => new RoleResource($role),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $role = $this->roleService->show($id);

        return response()->json([
            'status' => 'success',
            'data' => new RoleResource($role),
        ]);
    }

    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        $role = $this->roleService->update($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' => new RoleResource($role),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->roleService->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully',
        ], 204);
    }
}
