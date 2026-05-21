<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // AuthController.php
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Log::info('Tentative de connexion', $request->all());

        $user = User::where('email', $fields['email'])->first();

        // Vérification du mot de passe haché (Sécurité)
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'status' => 'error',
                'message' => 'Identifiants invalides'
            ], 401);
        }

        // Vérification si le compte est actif
        if (!$user->is_active) {
            return response([
                'status' => 'error',
                'message' => 'Votre compte a été désactivé par un administrateur.'
            ], 403);
        }

        // Log::info('Email reçu: ' . $request->email);
        // Log::info('Password saisi: ' . $request->password);
        // Log::info('Hachage en BDD: ' . $user->password);

        // Création du Token pour le mobile
        $token = $user->createToken('android_token')->plainTextToken;

        return response([
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function register(Request $request)
    {
        // 1. Validation des données (Standard Qualité)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        Log::info('Tentative de création', $request->all());

        if ($validator->fails()) {
            Log::info( $validator->errors());
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Création de l'utilisateur (Respect du MCD)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // 3. Génération du token pour le mobile
        $token = $user->createToken('android_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Compte créé avec succès',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($user->id) // Empêche les doublons en BDD
            ],
        ]);

        // 2. Vérification de sécurité (RGPD/Confidentialité)
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Le mot de passe actuel est incorrect'
            ], 403);
        }

        // 3. Mise à jour dynamique (Evolutivité)
        $data = $request->except(['current_password', 'password', 'id']);
        $user->fill($data);
        $user->save();

        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }
}
