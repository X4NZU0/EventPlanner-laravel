<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
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
Route::post('/events/{event}/interested', [EventController::class, 'interested'])->name('events.interested');
Route::post('/events/{event}/comment', [EventController::class, 'comment'])->name('events.comment');
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
Route::group(['middleware' => function ($request, $next) {
    $account = session('account');

    if (!$account || $account['role'] !== 'admin') {
        return redirect()->route('login')->withErrors(['login' => 'Access denied. Admins only.']);
    }

    return $next($request);
}], function () {
    Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/update-role', [\App\Http\Controllers\UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});




Route::middleware([])->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');

});

Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::post('/events/{event}/comment/{comment}/like', [EventController::class, 'likeComment'])->name('events.comment.like');
Route::post('/events/{event}/comment/{comment}/dislike', [EventController::class, 'dislikeComment'])->name('events.comment.dislike');
Route::delete('/events/{event}/comment/{comment}', [EventController::class, 'deleteComment'])
    ->name('events.comment.delete');