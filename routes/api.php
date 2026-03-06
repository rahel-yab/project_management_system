<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

// 1. Route to get the current user (standard Laravel check)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 2. Protected Project Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Only Managers can create
    Route::post('/projects', [ProjectController::class, 'store'])
        ->middleware('role:manager');

    // Everyone can view list
    Route::get('/projects', [ProjectController::class, 'index']);
});
