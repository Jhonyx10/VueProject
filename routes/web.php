<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\RegisterDoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Broadcast;

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
    Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {
        return Broadcast::auth($request);
    });
    
   Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors');

    // Doctor Only Routes
    Route::middleware(['role:doctor'])->group(function () {
        Route::get('/diagnosis/form', [DiagnosisController::class, 'create'])->name('diagnosis.create');
    });

    Route::resource('diagnosis', DiagnosisController::class)->parameters(['diagnosis' => 'diagnosis' ])->except(['create']);
    Route::resource('chat', ChatController::class);
    
    Route::resource('appointments', AppointmentController::class);
    Route::get('/doctors/profile/{id}', [DoctorsController::class, 'show'])->name('doctors_profile');

    // Register other doctor-only routes here if any

    
    Route::middleware(['role:admin,doctor'])->group(function () {
        Route::resource('/register/doctor', RegisterDoctorController::class);
        Route::resource('doctor', DoctorsController::class)->except(['index', 'show']);
    });


    // User Only Routes
    Route::middleware(['role:user'])->group(function () {

    });

});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';