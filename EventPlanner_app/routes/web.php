<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes example
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('checkRole:admin');

Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->middleware('checkRole:user');
