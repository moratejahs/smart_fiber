<?php

use \App\Http\Controllers\V1\API\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\API\Yolo9Controller;
use App\Http\Controllers\V1\API\DatasetController;
use App\Http\Controllers\V1\API\BarangayController;
use App\Http\Controllers\V1\API\RecentUserController;
use App\Http\Controllers\V1\API\RegistrationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/barangays', [BarangayController::class, 'index']);
Route::post('/register', [RegistrationController::class, 'store']);
Route::post('/yolo9', [Yolo9Controller::class, 'index']);
Route::post('/login', [LoginController::class, 'canLogin']);
Route::get('/recents/{userId}', [RecentUserController::class, 'index']);
Route::post('/dataset', [DatasetController::class, 'store']);
