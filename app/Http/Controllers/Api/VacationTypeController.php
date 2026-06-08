<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVacationTypeRequest;
use App\Http\Requests\UpdateVacationTypeRequest;
use App\Http\Resources\VacationTypeResource;
use App\Services\VacationTypeService;
use Illuminate\Http\JsonResponse;

class VacationTypeController extends Controller
{
    protected $vacationTypeService;

    public function __construct(VacationTypeService $vacationTypeService)
    {
        $this->vacationTypeService = $vacationTypeService;
        $this->middleware('permission:vacation_types,list_view')->only('index');
        $this->middleware('permission:vacation_types,detailed_view')->only('show');
        $this->middleware('permission:vacation_types,create')->only('store');
        $this->middleware('permission:vacation_types,update')->only('update');
        $this->middleware('permission:vacation_types,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $vacationTypes = $this->vacationTypeService->index($perPage);

        return response()->json([
            'status' => 'success',
            'data' => VacationTypeResource::collection($vacationTypes),
        ]);
    }

    public function store(StoreVacationTypeRequest $request): JsonResponse
    {
        $vacationType = $this->vacationTypeService->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation type created successfully',
            'data' => new VacationTypeResource($vacationType),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $vacationType = $this->vacationTypeService->show($id);

        return response()->json([
            'status' => 'success',
            'data' => new VacationTypeResource($vacationType),
        ]);
    }

    public function update(UpdateVacationTypeRequest $request, $id): JsonResponse
    {
        $vacationType = $this->vacationTypeService->update($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation type updated successfully',
            'data' => new VacationTypeResource($vacationType),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->vacationTypeService->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Vacation type deleted successfully',
        ], 204);
    }
}
