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
    }

    public function boot()
    {
        $this->app->when(\App\Repositories\EmployeeRepository::class)
            ->needs(\App\Models\Employee::class)
            ->give(function () {
                return new \App\Models\Employee();
            });
    }
}
