<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdministrationOrderRequest;
use App\Http\Requests\UpdateAdministrationOrderRequest;
use App\Http\Resources\AdministrationOrderResource;
use App\Services\AdministrationOrderService;
use Illuminate\Http\JsonResponse;

class AdministrationOrderController extends Controller
{
    protected $administrationOrderService;

    public function __construct(AdministrationOrderService $administrationOrderService)
    {
        $this->administrationOrderService = $administrationOrderService;
        $this->middleware('permission:administration_orders,list_view')->only('index');
        $this->middleware('permission:administration_orders,detailed_view')->only('show');
        $this->middleware('permission:administration_orders,create')->only('store');
        $this->middleware('permission:administration_orders,update')->only('update');
        $this->middleware('permission:administration_orders,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $orders = $this->administrationOrderService->index($perPage);
        return response()->json([
            'status' => 'success',
            'data' => AdministrationOrderResource::collection($orders)
        ]);
    }

    public function store(StoreAdministrationOrderRequest $request): JsonResponse
    {
        $order = $this->administrationOrderService->store($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Administration order created successfully',
            'data' => new AdministrationOrderResource($order->load(['employee', 'sector', 'department']))
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $order = $this->administrationOrderService->show($id);
        return response()->json([
            'status' => 'success',
            'data' => new AdministrationOrderResource($order)
        ]);
    }

    public function update(UpdateAdministrationOrderRequest $request, $id): JsonResponse
    {
        $order = $this->administrationOrderService->update($id, $request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Administration order updated successfully',
            'data' => new AdministrationOrderResource($order->load(['employee', 'sector', 'department']))
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->administrationOrderService->delete($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Administration order deleted successfully'
        ], 204);
    }
}
