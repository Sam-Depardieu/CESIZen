<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StressEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnosticController extends Controller
{
    public function index()
    {
        $events = StressEvent::all();
        return view('diagnostics.index', compact('events'));
    }

    public function store(Request $request)
    {
        // Logique pour calculer le score Holmes-Rahe
        $totalPoints = 0;
        if ($request->has('events')) {
            $totalPoints = StressEvent::whereIn('id', $request->events)->sum('points');
        }

        // Ici on pourrait enregistrer le résultat dans une table 'diagnostic_results'
        // Pour l'instant on redirige avec le score
        return redirect()->back()->with('score', $totalPoints);
    }
}
