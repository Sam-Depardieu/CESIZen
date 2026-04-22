<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // On récupère l'utilisateur connecté ou des données de démo si non connecté
        $user = Auth::user();

        // Données pour le dashboard (normalement récupérées en BDD)
        $stats = [
            'relaxation_time' => '45 min',
            'stress_status' => 'Stable',
            'last_diagnostic' => 'il y a 3 jours'
        ];

        return view('welcome', compact('user', 'stats'));
    }
}
