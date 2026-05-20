<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Services\BranchService;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
        $this->middleware('permission:branches,list_view')->only('index');
        $this->middleware('permission:branches,detailed_view')->only('show');
        $this->middleware('permission:branches,create')->only('store');
        $this->middleware('permission:branches,update')->only('update');
        $this->middleware('permission:branches,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $branches = $this->branchService->index($perPage);
        return response()->json([
            'status' => 'success',
            'data' => BranchResource::collection($branches)
        ]);
    }

    public function store(StoreBranchRequest $request): JsonResponse
    {
        $branch = $this->branchService->store($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Branch created successfully',
            'data' => new BranchResource($branch->load('departments'))
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $branch = $this->branchService->show($id);
        return response()->json([
            'status' => 'success',
            'data' => new BranchResource($branch)
        ]);
    }

    public function update(UpdateBranchRequest $request, $id): JsonResponse
    {
        $branch = $this->branchService->update($id, $request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Branch updated successfully',
            'data' => new BranchResource($branch->load('departments'))
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->branchService->delete($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Branch deleted successfully'
        ], 204);
    }
}
