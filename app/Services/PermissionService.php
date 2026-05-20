<?php

namespace App\Services;

class PermissionService
{
    public function getPermissionMap(): array
    {
        return [
            'admins' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'users' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'roles' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'attachments' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'attachment_structures' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'sectors' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'employees' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'job_groups' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'category_groups' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'career_progressions' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'training_courses' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'deductions' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'performance_evaluations' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'settlements' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'bonuses' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'incentives' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'branches' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'departments' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
            'administration_orders' => ['list_view', 'detailed_view', 'create', 'update', 'delete'],
        ];
    }

    public function getSuperAdminPermissions(): array
    {
        return $this->getPermissionMap();
    }

    public function getSectorAdminPermissions(): array
    {
        return [
            'employees' => ['list_view', 'detailed_view', 'create', 'update'],
            'sectors' => ['list_view', 'detailed_view'],
            'attachment_structures' => ['list_view', 'detailed_view'],
            'job_groups' => ['list_view', 'detailed_view'],
            'category_groups' => ['list_view', 'detailed_view'],
            'career_progressions' => ['list_view', 'detailed_view', 'create', 'update'],
            'training_courses' => ['list_view', 'detailed_view', 'create', 'update'],
            'deductions' => ['list_view', 'detailed_view', 'create', 'update'],
            'performance_evaluations' => ['list_view', 'detailed_view', 'create', 'update'],
            'settlements' => ['list_view', 'detailed_view'],
            'bonuses' => ['list_view', 'detailed_view', 'create', 'update'],
            'incentives' => ['list_view', 'detailed_view', 'create', 'update'],
            'branches' => ['list_view', 'detailed_view'],
            'departments' => ['list_view', 'detailed_view'],
            'administration_orders' => ['list_view', 'detailed_view', 'create', 'update'],
        ];
    }

    public function getAllowedPermissions(): array
    {
        return $this->getPermissionMap();
    }
}