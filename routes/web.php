<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// This loads your main frontend (e.g., http://localhost/)
Route::get('/', function () {
    return view('home'); 
});

// This loads your admin dashboard (e.g., http://localhost/admin)
Route::get('/admin', function () {
    return view('admin.index'); // It's best practice to put admin views in their own folder: resources/views/admin/index.blade.php
});
// Admin Authentication Endpoints
Route::post('/admin-api/login', [AuthController::class, 'login'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
Route::post('/admin-api/logout', [AuthController::class, 'logout'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
Route::post('/admin-api/check', [AuthController::class, 'check'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
Route::get('/debug-users', function() {
    return \App\Models\AdminUser::all();
});
