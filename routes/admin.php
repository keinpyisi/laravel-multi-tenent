<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TenantController;

Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')->group(function () {
    Route::resource('tenants', TenantController::class);
});
