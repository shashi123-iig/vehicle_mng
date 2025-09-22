<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\FuelEntryController;
use App\Http\Controllers\VehicleController as ControllersVehicleController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/storeVehicles', [VehicleController::class,'store']);
Route::get('/getVehicles', [VehicleController::class,'index']);
Route::post('/storefuel-entries', [FuelEntryController::class,'store']);
Route::get('/getfuel-entries', [FuelEntryController::class,'index']);
