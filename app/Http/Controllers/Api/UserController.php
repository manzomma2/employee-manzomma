<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('permission:users,list_view')->only('index');
        $this->middleware('permission:users,detailed_view')->only('show');
        $this->middleware('permission:users,create')->only('store');
        $this->middleware('permission:users,update')->only('update');
        $this->middleware('permission:users,delete')->only('destroy');
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $users = $this->userService->index($perPage);

        return response()->json([
            'status' => 'success',
            'data' => UserResource::collection($users),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->store($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->show($id);

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
        ]);
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        $user = $this->userService->update($id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->userService->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ], 204);
    }
}
