<?php

use Illuminate\Support\Facades\Route;

// Fallback route for the main application
Route::get('/', function () {
    return view('welcome');
});