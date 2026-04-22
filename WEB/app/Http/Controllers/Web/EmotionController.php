<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmotionRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmotionController extends Controller
{
    public function index()
    {
        $records = EmotionRecord::where('user_id', Auth::id())->latest()->get();
        return view('emotions.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'emotion' => 'required|string',
            'intensity' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string'
        ]);

        EmotionRecord::create([
            'user_id' => Auth::id(),
            'emotion' => $request->emotion,
            'intensity' => $request->intensity,
            'note' => $request->note
        ]);

        return redirect()->back()->with('success', 'Émotion enregistrée');
    }
}
