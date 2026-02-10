<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\RegisterDoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
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

// Main Auth Group
Route::middleware(['auth', 'verified'])->group(function () {
    
   Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::resource('chat', ChatController::class);
    Route::resource('appointments', AppointmentController::class);
    // Admin & Doctor Shared Routes
    // Note: 'auth' is already applied by the outer group
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('/register/doctor', RegisterDoctorController::class);
        Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors');
        Route::resource('doctor', DoctorsController::class)->except(['index']);
    });

    // Doctor Only Routes
    Route::middleware(['role:doctor'])->group(function () {
        // Specific doctor routes here
    });

    // User Only Routes
    Route::middleware(['role:user'])->group(function () {
        // Specific user routes here
    });

});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';