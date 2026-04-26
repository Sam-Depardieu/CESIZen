<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\EmotionController;
use App\Http\Controllers\Web\DiagnosticController;
use App\Http\Controllers\Web\RelaxationController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ProfileController;

// Public Routes
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/diagnostics', [DiagnosticController::class, 'index'])->name('diagnostics.index');
Route::get('/relaxation', [RelaxationController::class, 'index'])->name('relaxation.index');

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégé par authentifications
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tracker Émotionnel
    Route::get('/emotions', [EmotionController::class, 'index'])->name('emotions.index');
    Route::post('/emotions', [EmotionController::class, 'store'])->name('emotions.store');

    // Diagnostic Stress
    Route::get('/diagnostics/history', [DiagnosticController::class, 'history'])->name('diagnostics.history');
    Route::post('/diagnostics', [DiagnosticController::class, 'store'])->name('diagnostics.store');

    // Pages Relaxation
    Route::post('/relaxation/{id}/favorite', [RelaxationController::class, 'toggleFavorite'])->name('relaxation.favorite');
});
