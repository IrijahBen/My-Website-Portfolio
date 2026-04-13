<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// The default route (you can leave this here)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ---------------------------------------------------------
// PUBLIC API ROUTES (For viewing data on the frontend)
// ---------------------------------------------------------
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);

// ---------------------------------------------------------
// ADMIN API ROUTES (For modifying data in the admin panel)
// ---------------------------------------------------------
Route::post('/projects', [ProjectController::class, 'store']);
Route::put('/projects/{id}', [ProjectController::class, 'update']);
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
