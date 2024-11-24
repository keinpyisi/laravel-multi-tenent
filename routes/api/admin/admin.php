<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')->group(function () {
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{id}/update', [UserController::class, 'edit'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});
