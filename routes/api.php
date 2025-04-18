<?php

use App\Http\Controllers\V1\API\BarangayController;
use App\Http\Controllers\V1\API\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/barangays', [BarangayController::class, 'index']);
Route::get('/registrations', [RegistrationController::class, 'store']);
