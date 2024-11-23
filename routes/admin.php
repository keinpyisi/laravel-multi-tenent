<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TenantController;

Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')->group(function () {
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{domain}/reset', [TenantController::class, 'reset_basic'])->name('tenants.reset');
});
