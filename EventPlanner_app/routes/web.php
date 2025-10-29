<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;

// =========================
// HOMEPAGE & AUTH
// =========================
Route::view('/', 'welcome')->name('welcome');

// Registration
Route::get('/register', [UserRegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserRegistrationController::class, 'register'])->name('register.submit');

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', function () {
    $user = session('user');
    if (!$user) {
        return redirect()->route('login')->withErrors(['login' => 'Please login first.']);
    }
    return view('dashboard', compact('user'));
})->name('dashboard');

// =========================
// EVENT ROUTES
// =========================

// Events accessible to all logged-in users

    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');


// Public event routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::post('/events/{id}/interested', [EventController::class, 'markInterested'])->name('events.interested');
Route::post('/events/{id}/comment', [EventController::class, 'addComment'])->name('events.comment');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// =========================
// USER ROUTES
// =========================
Route::get('/users', [UserRegistrationController::class, 'index'])->name('user.index');
Route::get('/users/{id}/edit', [UserRegistrationController::class, 'edit'])->name('user.edit');
Route::put('/users/{id}', [UserRegistrationController::class, 'update'])->name('user.update');
Route::delete('/users/{id}', [UserRegistrationController::class, 'destroy'])->name('user.destroy');
Route::get('/approve/{id}', [UserRegistrationController::class, 'approveUser'])->name('user.approve');

// =========================
// ADMIN DASHBOARD & MANAGEMENT
// =========================

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/update-role', [UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

