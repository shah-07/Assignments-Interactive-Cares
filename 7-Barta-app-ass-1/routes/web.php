<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('welcome', function () {
    return view('welcome');
});


// Show registration form
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Handle registration form submission
Route::post('register', [RegisterController::class, 'register']);

// Show login form
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login form submission
Route::post('login', [LoginController::class, 'login']);

// // Logout user
// Route::post('logout', [LoginController::class, 'logout'])->name('logout');
