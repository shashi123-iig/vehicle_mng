<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\DriverAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\FuelEntryController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\VehicleController as ControllersVehicleController;

Route::get('/', function () {
    return response()->json(['message' => 'Vehicle Management API is running']);
});


// AUTH ROUTES
// ---------------------------

// Admin
Route::post('/admin/register', [AdminAuthController::class, 'register']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Driver
Route::post('/user/login', [UserAuthController::class, 'login']);


// PROTECTED ROUTES
// ---------------------------
Route::middleware('auth:sanctum')->group(function () {

    //  Admin Logout
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);

    //  Driver Logout
    Route::post('/user/logout', [UserAuthController::class, 'logout']);

    // VEHICLE MANAGEMENT
    // ---------------------------
    Route::post('/vehicles', [VehicleController::class, 'store']);           // Admin adds vehicle
    Route::get('/vehicles', [VehicleController::class, 'index']);            // List all vehicles
    Route::post('/assign-driver', [VehicleController::class, 'assignDriver']); // Assign driver to vehicle


    //  FUEL ENTRY
    // ---------------------------
    Route::post('/fuel-entry', [FuelEntryController::class, 'store']);       // Driver adds fuel entry
    Route::get('/fuel-report', [FuelEntryController::class, 'report']);      // Admin gets all fuel report
    Route::get('/fuel-report/{vehicleId}', [FuelEntryController::class, 'reportByVehicle']); // Report by vehicle
});
