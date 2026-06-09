<?php

namespace App\Providers;

use App\Services\PermissionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        require_once app_path('Support/helpers.php');

        $this->app->singleton('permission.service', PermissionService::class);

        $this->app->bind(
            \App\Interfaces\EmployeeRepositoryInterface::class,
            \App\Repositories\EmployeeRepository::class
        );

        $this->app->bind(
            \App\Interfaces\BranchRepositoryInterface::class,
            \App\Repositories\BranchRepository::class
        );

        $this->app->bind(
            \App\Interfaces\DepartmentRepositoryInterface::class,
            \App\Repositories\DepartmentRepository::class
        );

        $this->app->bind(
            \App\Interfaces\AdministrationOrderRepositoryInterface::class,
            \App\Repositories\AdministrationOrderRepository::class
        );

        $this->app->bind(
            \App\Interfaces\RoleRepositoryInterface::class,
            \App\Repositories\RoleRepository::class
        );

        $this->app->bind(
            \App\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        $this->app->bind(
            \App\Interfaces\VacationTypeRepositoryInterface::class,
            \App\Repositories\VacationTypeRepository::class
        );

        $this->app->bind(
            \App\Interfaces\VacationRepositoryInterface::class,
            \App\Repositories\VacationRepository::class
        );
    }

    public function boot()
    {
        // Register model observers
        \App\Models\AdministrationOrder::observe(\App\Observers\AdministrationOrderObserver::class);

        $this->app->when(\App\Repositories\EmployeeRepository::class)
            ->needs(\App\Models\Employee::class)
            ->give(function () {
                return new \App\Models\Employee();
            });

        $this->app->when(\App\Repositories\BranchRepository::class)
            ->needs(\App\Models\Branch::class)
            ->give(function () {
                return new \App\Models\Branch();
            });

        $this->app->when(\App\Repositories\DepartmentRepository::class)
            ->needs(\App\Models\Department::class)
            ->give(function () {
                return new \App\Models\Department();
            });

        $this->app->when(\App\Repositories\AdministrationOrderRepository::class)
            ->needs(\App\Models\AdministrationOrder::class)
            ->give(function () {
                return new \App\Models\AdministrationOrder();
            });

        $this->app->when(\App\Repositories\RoleRepository::class)
            ->needs(\App\Models\Role::class)
            ->give(function () {
                return new \App\Models\Role();
            });

        $this->app->when(\App\Repositories\UserRepository::class)
            ->needs(\App\Models\User::class)
            ->give(function () {
                return new \App\Models\User();
            });

        $this->app->when(\App\Repositories\VacationTypeRepository::class)
            ->needs(\App\Models\VacationType::class)
            ->give(function () {
                return new \App\Models\VacationType();
            });

        $this->app->when(\App\Repositories\VacationRepository::class)
            ->needs(\App\Models\Vacation::class)
            ->give(function () {
                return new \App\Models\Vacation();
            });
    }
}
