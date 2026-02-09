<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // admin routes.
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors');
        Route::resource('doctor', DoctorsController::class)->except(['index']);
    });

    //doctor routes.
    Route::middleware(['auth', 'role:doctor'])->group(function () {

    });

    //user routes.
    Route::middleware(['auth', 'role:user'])->group(function () {

    });

    Route::resource('appointments', AppointmentController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
