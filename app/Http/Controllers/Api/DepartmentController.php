<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Department::with('branch')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'branch_id' => 'required|exists:branches,id'
        ]);
        $department = Department::create($validated);
        return response()->json($department->load('branch'), 201);
    }

    public function show(Department $department): JsonResponse
    {
        return response()->json($department->load('branch'));
    }

    public function update(Request $request, Department $department): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'branch_id' => 'sometimes|required|exists:branches,id'
        ]);
        $department->update($validated);
        return response()->json($department->load('branch'));
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();
        return response()->json(null, 204);
    }
}
