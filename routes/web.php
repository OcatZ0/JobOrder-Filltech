<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobDetailController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jobs/all', [DashboardController::class, 'all'])->name('jobs.all');
    Route::get('/jobs/create', [DashboardController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [DashboardController::class, 'store'])->name('jobs.store');
    Route::post('/jobs/{job}/update-status', [DashboardController::class, 'updateStatus'])->name('jobs.updateStatus');

    Route::get('/job/{job}/details', [JobDetailController::class, 'jobDetails'])->name('job.details');
    Route::get('/job/{job}/add-documentation', [JobDetailController::class, 'create'])->name('job-documentation.create');
    Route::post('/job/{job}/documentation', [JobDetailController::class, 'store'])->name('job-documentation.store');
    Route::get('/documentation/{jobDetail}', [JobDetailController::class, 'show'])->name('job-documentation.show');
    Route::get('/documentation/{jobDetail}/edit-documentation', [JobDetailController::class, 'edit'])->name('job-documentation.edit');
    Route::put('/documentation/{jobDetail}', [JobDetailController::class, 'update'])->name('job-documentation.update');
    Route::delete('/documentation/{jobDetail}', [JobDetailController::class, 'destroy'])->name('job-documentation.destroy');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
