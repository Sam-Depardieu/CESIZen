<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        // On retourne tous les utilisateurs avec leur rôle
        return response()->json(User::with('role')->get());
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'id_role' => 'sometimes|exists:roles,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'user' => $user->load('role')
        ]);
    }

    public function destroy(User $user)
    {
        // Empêcher de se supprimer soi-même
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'Impossible de se supprimer soi-même'], 403);
        }

        $user->delete();
        return response()->json(['status' => 'success']);
    }
}
