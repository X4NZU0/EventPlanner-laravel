<?php

use App\Http\Controllers\LoginController;

Route::view('/', 'welcome')->name('welcome');

// Registration
Route::get('/register', [UserRegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserRegistrationController::class, 'register'])->name('register.submit');

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard (for users)
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = session('user');
    return view('dashboard', compact('user'));
})->name('dashboard');

// Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/users/{id}/update-role', [UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});



