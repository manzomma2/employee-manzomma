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
            
            // If deductions data is present, create records
            if ($request->has('deduction_values')) {
                $this->processDeductions($employee, $request);
            }
            
            // If performance evaluations data is present, create records
            if ($request->has('evaluation_from_dates')) {
                $this->processPerformanceEvaluations($employee, $request);
            }
            
            // If settlements data is present, create records
            if ($request->has('settlement_decisions')) {
                $this->processSettlements($employee, $request);
            }
            
            // If bonuses data is present, create records
            if ($request->has('bonus_numbers')) {
                $this->processBonuses($employee, $request);
            }
            
            // If incentives data is present, create records
            if ($request->has('incentive_numbers')) {
                $this->processIncentives($employee, $request);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
                'data' => new EmployeeResource($employee->load(['careerProgressions', 'trainingCourses', 'deductions', 'performanceEvaluations', 'settlements', 'bonuses', 'incentives']))
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
            if ($request->has('deduction_values')) {
                $this->processDeductions($employee, $request);
            }
            if ($request->has('evaluation_from_dates')) {
                $this->processPerformanceEvaluations($employee, $request);
            }
            if ($request->has('settlement_decisions')) {
                $this->processSettlements($employee, $request);
            }
            if ($request->has('bonus_numbers')) {
                $this->processBonuses($employee, $request);
            }
            if ($request->has('incentive_numbers')) {
                $this->processIncentives($employee, $request);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
                'data' => new EmployeeResource($employee->load(['careerProgressions', 'trainingCourses', 'deductions', 'performanceEvaluations', 'settlements', 'bonuses', 'incentives']))
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
    
    public function processDeductions($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing deduction IDs to track what's being updated
            $existingIds = $employee->deductions()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->deduction_values as $key => $value) {
                if (empty($value)) {
                    continue;
                }

                $deduction = $employee->deductions()->updateOrCreate(
                    [
                        'id' => $request->deduction_ids[$key] ?? null,
                    ],
                    [
                        'value' => $value,
                        'reason' => $request->deduction_reasons[$key] ?? null,
                        'date' => $request->deduction_dates[$key] ?? now()->toDateString(),
                    ]
                );

                $updatedIds[] = $deduction->id;
            }

            // Delete any deductions that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->deductions()->whereIn('id', $toDelete)->delete();
            }
        });
    }
    
    public function processPerformanceEvaluations($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing evaluation IDs to track what's being updated
            $existingIds = $employee->performanceEvaluations()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->evaluation_from_dates as $key => $fromDate) {
                if (empty($fromDate)) {
                    continue;
                }

                $evaluation = $employee->performanceEvaluations()->updateOrCreate(
                    [
                        'id' => $request->evaluation_ids[$key] ?? null,
                    ],
                    [
                        'from_date' => $fromDate,
                        'to_date' => $request->evaluation_to_dates[$key] ?? now()->toDateString(),
                        'degree' => $request->evaluation_degrees[$key] ?? null,
                        'rating' => $request->evaluation_ratings[$key] ?? null,
                    ]
                );

                $updatedIds[] = $evaluation->id;
            }

            // Delete any evaluations that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->performanceEvaluations()->whereIn('id', $toDelete)->delete();
            }
        });
    }
    
    public function processSettlements($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing settlement IDs to track what's being updated
            $existingIds = $employee->settlements()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->settlement_decisions as $key => $decision) {
                if (empty($decision)) {
                    continue;
                }

                $settlement = $employee->settlements()->updateOrCreate(
                    [
                        'id' => $request->settlement_ids[$key] ?? null,
                    ],
                    [
                        'decision' => $decision,
                        'date' => $request->settlement_dates[$key] ?? now()->toDateString(),
                    ]
                );

                $updatedIds[] = $settlement->id;
            }

            // Delete any settlements that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->settlements()->whereIn('id', $toDelete)->delete();
            }
        });
    }
    
    public function processBonuses($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing bonus IDs to track what's being updated
            $existingIds = $employee->bonuses()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->bonus_numbers as $key => $number) {
                if (empty($number)) {
                    continue;
                }

                $bonus = $employee->bonuses()->updateOrCreate(
                    [
                        'id' => $request->bonus_ids[$key] ?? null,
                    ],
                    [
                        'number' => $number,
                        'value' => $request->bonus_values[$key] ?? 0,
                        'date' => $request->bonus_dates[$key] ?? now()->toDateString(),
                        'decision' => $request->bonus_decisions[$key] ?? null,
                    ]
                );

                $updatedIds[] = $bonus->id;
            }

            // Delete any bonuses that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->bonuses()->whereIn('id', $toDelete)->delete();
            }
        });
    }
    
    public function processIncentives($employee, $request): void
    {
        DB::transaction(function () use ($employee, $request) {
            // Get all existing incentive IDs to track what's being updated
            $existingIds = $employee->incentives()->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->incentive_numbers as $key => $number) {
                if (empty($number)) {
                    continue;
                }

                $incentive = $employee->incentives()->updateOrCreate(
                    [
                        'id' => $request->incentive_ids[$key] ?? null,
                    ],
                    [
                        'number' => $number,
                        'decision' => $request->incentive_decisions[$key] ?? null,
                        'value' => $request->incentive_values[$key] ?? 0,
                        'date' => $request->incentive_dates[$key] ?? now()->toDateString(),
                    ]
                );

                $updatedIds[] = $incentive->id;
            }

            // Delete any incentives that weren't included in the update
            $toDelete = array_diff($existingIds, $updatedIds);
            if (!empty($toDelete)) {
                $employee->incentives()->whereIn('id', $toDelete)->delete();
            }
        });
    }
}