<?php

use App\Http\Controllers\Admin\AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function (){
    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard.index');
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');

});
