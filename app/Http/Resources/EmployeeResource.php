<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'file_number' => $this->file_number,
            'job_title' => $this->job_title,
            'national_id' => $this->national_id,
            'insurance_number' => $this->insurance_number,
            'sector' => $this->whenLoaded('sector', function () {
                return new SectorResource($this->sector);
            }),
            'category_group' => $this->whenLoaded('categoryGroup', function () {
                return new CategoryGroupResource($this->categoryGroup);
            }),
            'photo' => $this->photo,
            'phone' => $this->phone,
            'hire_date' => $this->hire_date ? $this->hire_date->format('Y-m-d') : null,
            'contract_date' => $this->contract_date ? $this->contract_date->format('Y-m-d') : null,
            'joining_date' => $this->joining_date ? $this->joining_date->format('Y-m-d') : null,
            'marital_status' => $this->marital_status,
            'religion' => $this->religion,
            'address' => $this->address,
            'academic_qualification' => $this->academic_qualification,
            'academic_specialization' => $this->academic_specialization,
            'graduation_date' => $this->graduation_date ? $this->graduation_date->format('Y-m-d') : null,
            'birth_date' => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
            'appointment_decision_number' => $this->appointment_decision_number,
            'type' => $this->type,
            'current_grade' => $this->whenLoaded('careerProgressions', function () {
            $latest = $this->careerProgressions->sortByDesc('id')->first();
            return $latest ? new CareerProgressionResource($latest) : null;
            }),
            'career_history' => $this->whenLoaded('careerProgressions', function () {
                return CareerProgressionResource::collection(
                    $this->careerProgressions->sortByDesc('grade_effective_date')->values()
                );
            }),
            'current_training_courses' => $this->whenLoaded('trainingCourses', function () {
                $latest = $this->trainingCourses->sortByDesc('id')->first();
                return $latest ? new TrainingCourseResource($latest) : null;
            }),
            'training_cources_history' => $this->whenLoaded('trainingCourses', function () {
                return TrainingCourseResource::collection(
                    $this->trainingCourses->sortByDesc('created_at')->values()
                );
            }),
            'current_deduction' => $this->whenLoaded('deductions', function () {
                $latest = $this->deductions->sortByDesc('id')->first();
                return $latest ? new DeductionResource($latest) : null;
            }),
            'deductions' => $this->whenLoaded('deductions', function () {
                return DeductionResource::collection(
                    $this->deductions->sortByDesc('id')->values()
                );
            }),
            'current_performance_evaluation' => $this->whenLoaded('performanceEvaluations', function () {
                $latest = $this->performanceEvaluations->sortByDesc('id')->first();
                return $latest ? new PerformanceEvaluationResource($latest) : null;
            }),
            'performance_evaluations' => $this->whenLoaded('performanceEvaluations', function () {
                return PerformanceEvaluationResource::collection(
                    $this->performanceEvaluations->sortByDesc('id')->values()
                );
            }),
            'current_settlement' => $this->whenLoaded('settlements', function () {
                $latest = $this->settlements->sortByDesc('id')->first();
                return $latest ? new SettlementResource($latest) : null;
            }),
            'settlements' => $this->whenLoaded('settlements', function () {
                return SettlementResource::collection(
                    $this->settlements->sortByDesc('id')->values()
                );
            }),
            'current_bonus' => $this->whenLoaded('bonuses', function () {
                $latest = $this->bonuses->sortByDesc('id')->first();
                return $latest ? new BonusResource($latest) : null;
            }),
            'bonuses' => $this->whenLoaded('bonuses', function () {
                return BonusResource::collection(
                    $this->bonuses->sortByDesc('id')->values()
                );
            }),
            'current_incentive' => $this->whenLoaded('incentives', function () {
                $latest = $this->incentives->sortByDesc('id')->first();
                return $latest ? new IncentiveResource($latest) : null;
            }),
            'incentives' => $this->whenLoaded('incentives', function () {
                return IncentiveResource::collection(
                    $this->incentives->sortByDesc('id')->values()
                );
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at' => $this->when($this->deleted_at, function () {
                return $this->deleted_at->format('Y-m-d H:i:s');
            }),
        ];
    }
}
