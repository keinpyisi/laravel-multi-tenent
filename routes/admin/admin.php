<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')->group(function () {
    
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{domain}/reset', [TenantController::class, 'reset_basic'])->name('tenants.reset');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
});
