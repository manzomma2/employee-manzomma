<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\SectorController;
use App\Http\Controllers\Api\JobGroupController;
use App\Http\Controllers\Api\CategoryGroupController;
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
        
        // Employee resource routes
        Route::resource('employees', EmployeeController::class);
        Route::resource('sectors', SectorController::class);
        Route::resource('job-groups', JobGroupController::class);
        Route::resource('category-groups', CategoryGroupController::class);
        
    });
