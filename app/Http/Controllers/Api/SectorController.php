<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectorStoreRequest;
use App\Http\Requests\SectorUpdateRequest;
use App\Http\Resources\SectorResource;
use App\Models\Sector;
use Illuminate\Http\JsonResponse;

class SectorController extends Controller
{
    public function index(): JsonResponse
    {
        $sectors = Sector::with('employees')->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => SectorResource::collection($sectors)
        ]);
    }

    public function store(SectorStoreRequest $request): JsonResponse
    {
        $sector = Sector::create($request->validated());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Sector created successfully',
            'data' => new SectorResource($sector)
        ], 201);
    }

    public function show(Sector $sector): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new SectorResource($sector)
        ]);
    }

    public function update(SectorUpdateRequest $request, Sector $sector): JsonResponse
    {
        $sector->update($request->validated());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Sector updated successfully',
            'data' => new SectorResource($sector)
        ]);
    }

    public function destroy(Sector $sector): JsonResponse
    {
        $sector->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Sector deleted successfully'
        ]);
    }
}
