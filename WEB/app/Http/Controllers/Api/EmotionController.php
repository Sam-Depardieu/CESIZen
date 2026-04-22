<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmotionRecord;
use Illuminate\Support\Facades\Log;

class EmotionController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->emotionRecords()->latest()->get();
    }

    public function store(Request $request)
    {
        Log::info('Tentative d\'enregistrement d\'émotion', [
            'user' => $request->user()->id,
            'data' => $request->all()
        ]);

        try {
            $validated = $request->validate([
                'emotion_name' => 'required|string',
                'note' => 'nullable|string',
            ]);

            $record = $request->user()->emotionRecords()->create([
                'emotion' => $validated['emotion_name'],
                'intensity' => 3,
                'note' => $validated['note'] ?? 'Saisie depuis CESIZen',
            ]);

            return response()->json($record, 201);
        } catch (\Exception $e) {
            Log::error('Erreur enregistrement émotion : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }
}
