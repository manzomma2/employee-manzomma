<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryGroupResource;
use App\Http\Requests\CategoryGroupStoreRequest;
use App\Http\Requests\CategoryGroupUpdateRequest;
use App\Models\CategoryGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:category_groups,list_view')->only('index');
        $this->middleware('permission:category_groups,detailed_view')->only('show');
        $this->middleware('permission:category_groups,create')->only('store');
        $this->middleware('permission:category_groups,update')->only('update');
        $this->middleware('permission:category_groups,delete')->only('destroy');
    }
    public function index(): AnonymousResourceCollection
    {
        return CategoryGroupResource::collection(
            CategoryGroup::with(['jobGroup', 'employees'])
                ->withCount('employees')
                ->latest()
                ->get()
        );
    }

    public function store(CategoryGroupStoreRequest $request): JsonResponse
    {
        $categoryGroup = CategoryGroup::create($request->validated());
        return response()->json([
            'message' => 'Category group created successfully',
            'data' => new CategoryGroupResource($categoryGroup)
        ], 201);
    }

    public function show(CategoryGroup $categoryGroup): CategoryGroupResource
    {
        $categoryGroup->load(['jobGroup', 'employees'])
                     ->loadCount('employees');
        
        return new CategoryGroupResource($categoryGroup);
    }

    public function update(CategoryGroupUpdateRequest $request, CategoryGroup $categoryGroup): JsonResponse
    {
        $categoryGroup->update($request->validated());
        return response()->json([
            'message' => 'Category group updated successfully',
            'data' => new CategoryGroupResource($categoryGroup)
        ]);
    }

    public function destroy(CategoryGroup $categoryGroup): JsonResponse
    {
        $categoryGroup->delete();
        return response()->json(['message' => 'Category group deleted successfully']);
    }
}
