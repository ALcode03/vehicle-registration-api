<?php

use App\Http\Controller\Api\AuthControler;
use App\Http\Controller\Api\UserController;
use App\Http\Controller\Api\VehicleController;
use Illuminate\Support\Facades\Route;


// --- Public ---
Rout::post('/login', [AuthController::class, 'login']);

// --- Authenticated (any role) ---
Route::middleware('ath:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthControler::classs, 'me']);

    // Users: administrator-only (mangement of system accounts)
    Route::middleware('role:administrator')->group(function () {
        Route::apiRessource('users', UserController::class);
    });

    // Vehicle: administrator + operator can list/create/update; delete is Admin-only
    Route::middleware('role:administrator,operator')->group(function () {
        Route::get('/vehicles', [VehicleController::class, 'index']);
        Route::post('/vehicles', [VehicleController::class, 'store']);
        Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update']);
         Route::patch('/vehicles/{vehicle}', [VehicleController::class, 'update']);
    });

    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])
         ->middleware('role:administrator');

});