<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CutVacationRequest;
use App\Http\Requests\ExtendVacationRequest;
use App\Http\Requests\StoreVacationRequest;
use App\Http\Requests\UpdateVacationRequest;
use App\Http\Resources\VacationResource;
use App\Services\VacationService;
use Illuminate\Http\JsonResponse;

class VacationController extends Controller
{
    protected $vacationService;

    public function __construct(VacationService $vacationService)
    {
        $this->vacationService = $vacationService;
        $this->middleware('permission:vacations,list_view')->only('index');
        $this->middleware('permission:vacations,detailed_view')->only('show');
        $this->middleware('permission:vacations,create')->only('store');
        $this->middleware('permission:vacations,update')->only(['update', 'cut', 'extend', 'complete']);
        $this->middleware('permission:vacations,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $filters = request()->except(['page', 'per_page']);
        $vacations = $this->vacationService->index($perPage, $filters);

        return response()->json([
            'status' => 'success',
            'data' => VacationResource::collection($vacations),
        ]);
    }

    public function store(StoreVacationRequest $request): JsonResponse
    {
        $vacation = $this->vacationService->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation created successfully',
            'data' => new VacationResource($vacation),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $vacation = $this->vacationService->show($id);

        return response()->json([
            'status' => 'success',
            'data' => new VacationResource($vacation),
        ]);
    }

    public function update(UpdateVacationRequest $request, $id): JsonResponse
    {
        $vacation = $this->vacationService->update($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation updated successfully',
            'data' => new VacationResource($vacation),
        ]);
    }

    public function cut(CutVacationRequest $request, $id): JsonResponse
    {
        $vacation = $this->vacationService->cut($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation cut successfully',
            'data' => new VacationResource($vacation),
        ]);
    }

    public function extend(ExtendVacationRequest $request, $id): JsonResponse
    {
        $vacation = $this->vacationService->extend($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation extended successfully',
            'data' => new VacationResource($vacation),
        ]);
    }

    public function complete($id): JsonResponse
    {
        $vacation = $this->vacationService->complete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation completed successfully',
            'data' => new VacationResource($vacation),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->vacationService->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation deleted successfully',
        ], 204);
    }
}
