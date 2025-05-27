<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversityEventController;
use App\Http\Controllers\AuthController;

Route::get('/', [UniversityEventController::class, 'welcome']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// University Events Routes
Route::prefix('university')->name('university.')->group(function () {
    // Admin/Staff only routes - HARUS DIDEFINISIKAN DULUAN SEBELUM ROUTE DENGAN PARAMETER
    Route::middleware(['auth', 'check.role:admin,staff'])->group(function () {
        Route::get('events/create', [UniversityEventController::class, 'create'])->name('events.create');
        Route::post('events', [UniversityEventController::class, 'store'])->name('events.store');
        Route::get('events/{id}/edit', [UniversityEventController::class, 'edit'])->name('events.edit');
        Route::put('events/{id}', [UniversityEventController::class, 'update'])->name('events.update');
        Route::delete('events/{id}', [UniversityEventController::class, 'destroy'])->name('events.destroy');
    });
    
    Route::middleware('auth')->group(function () {
        Route::resource('events', UniversityEventController::class);
        Route::get('events/{id}', [UniversityEventController::class, 'show'])->name('events.show');
    Route::post('events/{id}/register', [UniversityEventController::class, 'register'])->name('events.register');
    });
    
    // Public routes - bisa diakses semua user (tanpa login)
    Route::get('events', [UniversityEventController::class, 'index'])->name('events.index');
    // Route::get('events/{id}', [UniversityEventController::class, 'show'])->name('events.show');
    // Route::post('events/{id}/register', [UniversityEventController::class, 'register'])->name('events.register');
});

// Route untuk test API status
Route::get('/api-status', [UniversityEventController::class, 'apiStatus'])->name('api.status');