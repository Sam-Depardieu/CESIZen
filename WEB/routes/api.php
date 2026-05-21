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
        return $request->user()->load('role');
    });

    Route::post('/update-profile', [AuthController::class, 'updateProfile']);

    Route::get('/stress-events', [StressDiagnosticController::class, 'index']);

    // Route pour enregistrer un diagnostic effectué sur mobile
    Route::post('/stress-diagnostics', [StressDiagnosticController::class, 'store']);

    Route::get('/emotions', [App\Http\Controllers\Api\EmotionController::class, 'index']);
    Route::post('/emotions', [App\Http\Controllers\Api\EmotionController::class, 'store']);

    // Routes pour les informations (accessibles aux utilisateurs connectés)
    Route::get('/informations', [App\Http\Controllers\Api\InformationController::class, 'index']);
    Route::get('/informations/{id}', [App\Http\Controllers\Api\InformationController::class, 'show']);

    // Administration (vérification du rôle admin)
    Route::middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/users', [App\Http\Controllers\Api\AdminUserController::class, 'index']);
        Route::put('/users/{user}', [App\Http\Controllers\Api\AdminUserController::class, 'update']);
        Route::delete('/users/{user}', [App\Http\Controllers\Api\AdminUserController::class, 'destroy']);
    });
});
