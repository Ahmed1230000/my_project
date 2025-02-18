<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UnitAmenityController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitFavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;








Route::post('/register', RegisterController::class);

Route::post('/login', LoginController::class);


Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/units', UnitController::class);
    Route::apiResource('/locations', LocationController::class);
    Route::apiResource('/developers', DeveloperController::class);
    Route::apiResource('/unit_favorite', UnitFavoriteController::class);
    Route::apiResource('/amenity', AmenityController::class);

    Route::prefix('units/{unit}/amenities')->group(function () {
        Route::post('/', [UnitAmenityController::class, 'store']); // Attach amenities to a unit
        Route::get('/', [UnitAmenityController::class, 'show']); // List amenities of a unit
        Route::put('/', [UnitAmenityController::class, 'update']); // Update amenities for a unit
        Route::delete('/', [UnitAmenityController::class, 'destroy']); // Detach amenities from a unit
    });
    Route::get('/amenities', [UnitAmenityController::class, 'index']); // List amenities of a unit
    Route::apiResource('reservations', ReservationController::class);
});
