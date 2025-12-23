<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   // app/Providers/AppServiceProvider.php
    public function register()
    {
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
