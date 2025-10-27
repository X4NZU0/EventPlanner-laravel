<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LoginController;

//ito homepage
Route::get('/', function () {
    return view('welcome');
});


Route::view('/', 'welcome')->name('welcome');

//ito register route
Route::get('/register', [UserRegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserRegistrationController::class, 'register'])->name('register.submit');


//login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    $user = session('user');
    if (!$user) {
        return redirect()->route('login')->withErrors(['login' => 'Please login first.']);
    }
    return view('dashboard', compact('user'));
})->name('dashboard');
