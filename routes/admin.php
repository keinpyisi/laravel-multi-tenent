<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')->group(function () {
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
});