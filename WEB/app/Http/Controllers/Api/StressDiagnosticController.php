<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StressEvent;
use Illuminate\Http\Request;

class StressDiagnosticController extends Controller
{
    public function index()
    {
        // Retourne les évènements et points pour l'affichage Flutter [cite: 126]
        return response()->json(StressEvent::all(), 200);
    }

    public function getEvents()
    {
        // Retourne la liste des événements pour le module Diagnostic
        return response()->json(StressEvent::all());
    }
}
