<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait EmployeeFilterTrait
{
    protected function applyEmployeeFilters(Builder $query, array $filters, ?string $employeeRelation = null): Builder
    {
        if ($employeeRelation) {
            return $query->whereHas($employeeRelation, function (Builder $query) use ($filters) {
                $this->applyEmployeeFilters($query, $filters);
            });
        }

        $sectorIds = $this->filterValues($filters, 'sector_ids');
        if ($sectorIds) {
            $query->whereHas('latestAdministrationOrder', function (Builder $query) use ($sectorIds) {
                $query->whereIn('sector_id', $sectorIds);
            });
        }

        $departmentIds = $this->filterValues($filters, 'department_ids');
        if ($departmentIds) {
            $query->whereHas('latestAdministrationOrder', function (Builder $query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            });
        }

        $branchIds = $this->filterValues($filters, 'branch_ids');
        if ($branchIds) {
            $query->whereHas('latestAdministrationOrder.department', function (Builder $query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            });
        }

        $categoryGroupIds = $this->filterValues($filters, 'category_group_ids');
        if ($categoryGroupIds) {
            $query->whereIn('category_group_id', $categoryGroupIds);
        }

        $jobGroupIds = $this->filterValues($filters, 'job_group_ids');
        if ($jobGroupIds) {
            $query->whereHas('categoryGroup', function (Builder $query) use ($jobGroupIds) {
                $query->whereIn('job_group_id', $jobGroupIds);
            });
        }

        $religions = $this->filterValues($filters, 'religion');
        if ($religions) {
            $query->whereIn('religion', $religions);
        }

        $names = $this->filterValues($filters, 'name');
        if ($names) {
            $query->where(function (Builder $query) use ($names) {
                foreach ($names as $name) {
                    $query->orWhere(function (Builder $query) use ($name) {
                        $query->where('first_name', 'like', '%' . $name . '%')
                            ->orWhere('last_name', 'like', '%' . $name . '%');
                    });
                }
            });
        }

        return $query;
    }

    protected function filterValues(array $filters, string $key): array
    {
        if (! array_key_exists($key, $filters)) {
            return [];
        }

        $value = $filters[$key];

        if (is_array($value)) {
            $values = $value;
        } else {
            $values = explode(',', (string) $value);
        }

        return array_values(array_unique(array_filter(array_map('trim', $values), function ($value) {
            return $value !== '';
        })));
    }
}
