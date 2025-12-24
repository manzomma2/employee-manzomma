<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(): JsonResponse
    {
        $perPage = request()->get('per_page', 15);
        $employees = $this->employeeService->index($perPage);
        return response()->json([
            'status' => 'success',
            'data' => EmployeeResource::collection($employees)
        ]);
    }

    public function store(EmployeeStoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();
            $employee = $this->employeeService->store($validated);
            
            // If career progression data is present, create records
            if ($request->has('job_grades') && $request->has('job_levels')) {
                $this->processCareerProgressions($employee, $request);
            }
            
            // If training courses data is present, create records
            if ($request->has('training_courses_names')) {
                $this->processTrainingCourses($employee, $request);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
                'data' => new EmployeeResource($employee->load(['careerProgressions', 'trainingCourses']))
            ], 201);
        });
    }
    public function show($id): JsonResponse
    {
        $employee = $this->employeeService->show($id);
        return response()->json([
            'status' => 'success',
            'data' => new EmployeeResource($employee)
        ]);
    }

    public function update(EmployeeUpdateRequest $request, $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $validated = $request->validated();
            $employee = $this->employeeService->update($id, $validated);
            
            // If career progression data is present, create records
            if ($request->has('job_grades') && $request->has('job_levels')) {
                $this->processCareerProgressions($employee, $request);
            }
            if ($request->has('training_courses_names')) {
                $this->processTrainingCourses($employee, $request);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
                'data' => new EmployeeResource($employee->load(['careerProgressions', 'trainingCourses']))
            ]);
        });
    }

    public function destroy($id): JsonResponse
    {
        $this->employeeService->delete($id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully'
        ], 204);
    }
   
    private function processCareerProgressions($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing progression IDs to track what's being updated
            $existingIds = $employee->careerProgressions()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->job_grades as $key => $job_grade) {
                if (empty($job_grade)) {
                    continue;
                }

                $progression = $employee->careerProgressions()->updateOrCreate(
                    [
                        'id' => $request->progression_ids[$key] ?? null,
                    ],
                    [
                        'job_grade' => $job_grade,
                        'job_level' => $request->job_levels[$key] ?? null,
                        'grade_effective_date' => $request->grade_effective_dates[$key] ?? now()->toDateString(),
                        'grade_decision_number' => $request->grade_decision_numbers[$key] ?? null,
                    ]
                );

                $updatedIds[] = $progression->id;
            }

            // Delete any progressions that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->careerProgressions()->whereIn('id', $toDelete)->delete();
            }
        });
    }
    public function processTrainingCourses($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing training course IDs to track what's being updated
            $existingIds = $employee->trainingCourses()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->training_courses_names as $key => $name) {
                if (empty($name)) {
                    continue;
                }

                $trainingCourse = $employee->trainingCourses()->updateOrCreate(
                    [
                        'id' => $request->training_courses_ids[$key] ?? null,
                    ],
                    [
                        'name' => $name,
                        'start_date' => $request->training_courses_start_dates[$key] ?? now()->toDateString(),
                        'end_date' => $request->training_courses_end_dates[$key] ?? now()->toDateString(),
                    ]
                );

                $updatedIds[] = $trainingCourse->id;
            }

            // Delete any training courses that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->trainingCourses()->whereIn('id', $toDelete)->delete();
            }
        });
    }
}