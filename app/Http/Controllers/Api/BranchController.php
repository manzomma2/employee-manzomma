<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Branch::with('departments')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string']);
        $branch = Branch::create($validated);
        return response()->json($branch, 201);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json($branch->load('departments'));
    }

    public function update(Request $request, Branch $branch): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string']);
        $branch->update($validated);
        return response()->json($branch);
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $branch->delete();
        return response()->json(null, 204);
    }
}
