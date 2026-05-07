<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jobs/all', [DashboardController::class, 'all'])->name('jobs.all');
    Route::get('/jobs/create', [DashboardController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [DashboardController::class, 'store'])->name('jobs.store');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
