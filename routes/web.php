<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('signup'); // or redirect to login
});

Route::get('/login', [CustomLoginController::class, 'showLogin'])->name('login');
Route::post('/login/process', [CustomLoginController::class, 'processInput'])->name('login.process');
Route::post('/login/verify', [CustomLoginController::class, 'verifySecondStep'])->name('login.verify');
Route::post('/login/password', [CustomLoginController::class, 'loginWithPassword'])->name('login.password');

// Admin Routes
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
