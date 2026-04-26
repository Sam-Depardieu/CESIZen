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
        $history = [];
        if (Auth::check()) {
            $history = \App\Models\ResultatDiag::where('id_user', Auth::id())
                ->orderBy('date_passage', 'desc')
                ->take(5)
                ->get();
        }
        return view('diagnostics.index', compact('events', 'history'));
    }

    public function history()
    {
        $history = \App\Models\ResultatDiag::where('id_user', Auth::id())
            ->orderBy('date_passage', 'desc')
            ->get();
        return view('diagnostics.history', compact('history'));
    }

    public function store(Request $request)
    {
        // Logique pour calculer le score Holmes-Rahe
        $totalPoints = 0;
        if ($request->has('events')) {
            $totalPoints = StressEvent::whereIn('id', $request->events)->sum('points');
        }

        $niveauStress = 'Faible';
        if ($totalPoints >= 300) {
            $niveauStress = 'Élevé';
        } elseif ($totalPoints >= 150) {
            $niveauStress = 'Modéré';
        }

        // Enregistrement si l'utilisateur est connecté
        if (Auth::check()) {
            \App\Models\ResultatDiag::create([
                'id_user' => Auth::id(),
                'score_total' => $totalPoints,
                'niveau_stress' => $niveauStress,
                'event_ids' => $request->has('events') ? implode(';', $request->events) : null,
                'date_passage' => now(),
            ]);
        }

        return redirect()->back()->with('score', $totalPoints);
    }
}
