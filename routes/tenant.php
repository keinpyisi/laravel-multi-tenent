<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\UserController;
use App\Http\Controllers\Tenant\LoginController as TenantLoginController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;

Route::prefix('backend/{tenant}')->middleware('set.tenant')->name('tenant.')->group(function () {
    // Route::get('login', [TenantLoginController::class, 'showLoginForm'])->name('login');
    // Route::post('login', [TenantLoginController::class, 'login']);
    // Route::post('logout', [TenantLoginController::class, 'logout'])->name('logout');

    // Route::middleware('auth:tenant')->group(function () {
    //     Route::get('dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
    //     // Add more tenant routes here...
    // });
    Route::get('/users', [UserController::class, 'index'])->name('client.index');
});
