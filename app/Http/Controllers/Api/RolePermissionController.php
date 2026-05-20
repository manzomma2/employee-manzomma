<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:roles,update')->only('update');
        $this->middleware('permission:roles,list_view')->only('available');
    }

    public function update(UpdateRolePermissionsRequest $request, Role $role): JsonResponse
    {
        $role->update([
            'permissions' => $request->validated('permissions'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Role permissions updated successfully',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions,
            ],
        ]);
    }

    public function available(PermissionService $permissionService): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $permissionService->getAllowedPermissions(),
        ]);
    }
}
