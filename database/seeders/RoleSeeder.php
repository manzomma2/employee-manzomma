<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissionService = app(PermissionService::class);

        Role::updateOrCreate(
            ['name' => 'Super Admin'],
            ['permissions' => $permissionService->getSuperAdminPermissions()]
        );

        Role::updateOrCreate(
            ['name' => 'Sector Admin'],
            ['permissions' => $permissionService->getSectorAdminPermissions()]
        );
    }
}