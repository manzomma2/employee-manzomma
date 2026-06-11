<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHospitalRequest;
use App\Http\Requests\UpdateHospitalRequest;
use App\Http\Resources\HospitalResource;
use App\Services\HospitalService;
use Illuminate\Http\JsonResponse;

class HospitalController extends Controller
{
    protected $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
        $this->middleware('permission:hospitals,list_view')->only('index');
        $this->middleware('permission:hospitals,detailed_view')->only('show');
        $this->middleware('permission:hospitals,create')->only('store');
        $this->middleware('permission:hospitals,update')->only('update');
        $this->middleware('permission:hospitals,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $hospitals = $this->hospitalService->index($perPage);

        return response()->json([
            'status' => 'success',
            'data' => HospitalResource::collection($hospitals),
        ]);
    }

    public function store(StoreHospitalRequest $request): JsonResponse
    {
        $hospital = $this->hospitalService->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Hospital created successfully',
            'data' => new HospitalResource($hospital),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $hospital = $this->hospitalService->show($id);

        return response()->json([
            'status' => 'success',
            'data' => new HospitalResource($hospital),
        ]);
    }

    public function update(UpdateHospitalRequest $request, $id): JsonResponse
    {
        $hospital = $this->hospitalService->update($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Hospital updated successfully',
            'data' => new HospitalResource($hospital),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->hospitalService->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Hospital deleted successfully',
        ], 204);
    }
}
