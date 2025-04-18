<?php

use App\Http\Controllers\V1\API\DatasetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\API\BarangayController;
use App\Http\Controllers\V1\API\RecentUserController;
use App\Http\Controllers\V1\API\RegistrationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/barangays', [BarangayController::class, 'index']);
Route::post('/registrations', [RegistrationController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/recent-datasets', [RecentUserController::class, 'index']);
    Route::post('/dataset', [DatasetController::class, 'store']);
});
