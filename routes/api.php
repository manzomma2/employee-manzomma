<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\SectorController;
use App\Http\Controllers\Api\JobGroupController;
use App\Http\Controllers\Api\CategoryGroupController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\AdministrationOrderController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VacationController;
use App\Http\Controllers\Api\VacationTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/  
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // User routes
        Route::get('/user', [AuthController::class, 'user']);
        Route::apiResource('users', UserController::class);
        
        // Employee resource routes
        Route::resource('employees', EmployeeController::class);
        Route::resource('sectors', SectorController::class);
        Route::resource('job-groups', JobGroupController::class);
        Route::resource('category-groups', CategoryGroupController::class);
        Route::resource('branches', BranchController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('administration-orders', AdministrationOrderController::class);
        Route::apiResource('hospitals', HospitalController::class);
        Route::patch('vacations/{vacation}/cut', [VacationController::class, 'cut']);
        Route::patch('vacations/{vacation}/extend', [VacationController::class, 'extend']);
        Route::patch('vacations/{vacation}/complete', [VacationController::class, 'complete']);
        Route::get('vacations/employee-period', [VacationController::class, 'employeePeriod']);
        Route::get('vacations/stats', [VacationController::class, 'stats']);
        Route::apiResource('vacations', VacationController::class);
        Route::apiResource('vacation-types', VacationTypeController::class);
        Route::get('roles/permissions-map', [RolePermissionController::class, 'available']);
        Route::put('roles/{role}/permissions', [RolePermissionController::class, 'update']);
        Route::apiResource('roles', RoleController::class);
        
    });
