<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacultyEventController;
use App\Http\Controllers\UniversityEventViewController;
use App\Http\Controllers\AuthController;


Route::get('/', [FacultyEventController::class, 'welcome']);


// Route Registrasi
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// Authentication Routes (jika diperlukan)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'check.role:admin,staff'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::resource('events', FacultyEventController::class)->except(['index', 'show']);
});


Route::middleware('auth')->group(function () {
    Route::prefix('faculty')->name('faculty.')->group(function () {
        Route::resource('events', FacultyEventController::class);
    });
    Route::prefix('university')->name('university.')->group(function () {
        Route::resource('events', UniversityEventViewController::class);
    });
});


// routes/web.php
// Tanpa login juga bisa lihat event
Route::prefix('faculty')->name('faculty.')->group(function () {
    Route::get('events', [FacultyEventController::class, 'index'])->name('events.index');
    Route::get('events/{id}', [FacultyEventController::class, 'show'])->name('events.show');
    Route::post('events/{id}/register', [FacultyEventController::class, 'register'])->name('events.register');
});


Route::prefix('university')->name('university.')->group(function () {
    Route::get('events', [UniversityEventViewController::class, 'index'])->name('events.index');
    Route::get('events/create', [UniversityEventViewController::class, 'create'])->name('events.create');
    Route::get('events/{id}', [UniversityEventViewController::class, 'show'])->name('events.show');
    Route::post('events/{id}/register', [UniversityEventViewController::class, 'register'])->name('events.register');
});