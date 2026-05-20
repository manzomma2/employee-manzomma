<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobGroupStoreRequest;
use App\Http\Requests\JobGroupUpdateRequest;
use App\Http\Resources\JobGroupResource;
use App\Models\JobGroup;
use Illuminate\Http\JsonResponse;

class JobGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:job_groups,list_view')->only('index');
        $this->middleware('permission:job_groups,detailed_view')->only('show');
        $this->middleware('permission:job_groups,create')->only('store');
        $this->middleware('permission:job_groups,update')->only('update');
        $this->middleware('permission:job_groups,delete')->only('destroy');
    }
    public function index(): JsonResponse
    {
        $jobGroups = JobGroup::with(['categoryGroups' => function($query) {
            $query->withCount('employees');
        }])->latest()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => JobGroupResource::collection($jobGroups)
        ]);
    }

    public function store(JobGroupStoreRequest $request): JsonResponse
    {
        $jobGroup = JobGroup::create($request->validated());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Job group created successfully',
            'data' => new JobGroupResource($jobGroup)
        ], 201);
    }

    public function show(JobGroup $jobGroup): JsonResponse
    {
        $jobGroup->load(['categoryGroups' => function($query) {
            $query->withCount('employees');
        }]);
        
        return response()->json([
            'status' => 'success',
            'data' => new JobGroupResource($jobGroup)
        ]);
    }

    public function update(JobGroupUpdateRequest $request, JobGroup $jobGroup): JsonResponse
    {
        $jobGroup->update($request->validated());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Job group updated successfully',
            'data' => new JobGroupResource($jobGroup)
        ]);
    }

    public function destroy(JobGroup $jobGroup): JsonResponse
    {
        $jobGroup->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Job group deleted successfully'
        ]);
    }
}
