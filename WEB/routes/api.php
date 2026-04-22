<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StressDiagnosticController;

// Route pour le login (accessible par le visiteur anonyme)
Route::post('/login', [AuthController::class, 'login']);
// Route publique pour l'inscription
Route::post('/register', [AuthController::class, 'register']);

// Routes protégées (accessibles seulement si l'utilisateur mobile a un token valide)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

    Route::post('/update-profile', [AuthController::class, 'updateProfile']);

    Route::get('/stress-events', [StressDiagnosticController::class, 'index']);

    // Route pour enregistrer un diagnostic effectué sur mobile
    Route::post('/stress-diagnostics', [StressDiagnosticController::class, 'store']);

    Route::get('/emotions', [App\Http\Controllers\Api\EmotionController::class, 'index']);
    Route::post('/emotions', [App\Http\Controllers\Api\EmotionController::class, 'store']);
});
