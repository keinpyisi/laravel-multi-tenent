<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Json\UserJson;

Route::prefix('backend/admin')->name('admin.')->middleware('set.api.tenant')->group(function () {
    Route::get('user', [UserJson::class, 'get_all'])->name('users.all');
    Route::post('users', [UserJson::class, 'store'])->name('users.store');
    Route::put('users/{id}/update', [UserJson::class, 'update'])->name('users.update');
    Route::delete('users', [UserJson::class, 'destroy'])->name('users.destroy');
    Route::get('users/{id}', [UserJson::class, 'get_one'])->name('users.show');
});
