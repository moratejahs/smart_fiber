<?php

use App\Http\Controllers\Admin\AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\V1\API\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.admin-layout');
});
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('logout');

Route::prefix('admin')->group(function (){
    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard.index');
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
});
