<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniversityEventController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes (jika diperlukan)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Registrasi
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth', 'check.role:admin,staff'])->prefix('university')->name('university.')->group(function () {
    Route::resource('events', UniversityEventController::class)->except(['index', 'show']);
});

// routes/web.php
Route::middleware(['auth'])->prefix('university')->name('university.')->group(function () {
    Route::resource('events', UniversityEventController::class);
});

Route::prefix('university')->name('university.')->group(function () {
    Route::get('events', [UniversityEventController::class, 'index'])->name('events.index');
    Route::get('events/{id}', [UniversityEventController::class, 'show'])->name('events.show');
    Route::post('events/{id}/register', [UniversityEventController::class, 'register'])->name('events.register');
});

Route::prefix('university')->name('university.')->group(function () {
    // Resource route untuk CRUD event universitas
    Route::resource('events', UniversityEventController::class);
    
    // Pendaftaran event
    Route::post('events/{id}/register', [UniversityEventController::class, 'register'])->name('events.register');
});