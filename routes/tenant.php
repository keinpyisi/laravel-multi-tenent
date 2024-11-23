<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\UserController;
use App\Http\Controllers\Tenant\LoginController as TenantLoginController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;

Route::prefix('backend/{tenant}')
    ->middleware('set.tenant')  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        // Public routes
        Route::get('/users', [UserController::class, 'index'])->name('client.index');

        // // Protected routes requiring tenant authentication
        // Route::middleware('auth:tenant')->group(function () {
        //     Route::get('dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
        //     // More tenant-specific routes...
        // });
    });
