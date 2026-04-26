<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RelaxationActivity;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelaxationController extends Controller
{
    public function index()
    {
        $activities = RelaxationActivity::all();
        $favorites = [];
        return view('relaxation.index', compact('activities', 'favorites'));
    }

    public function toggleFavorite($id)
    {
        // Fonctionnalité désactivée pour le moment
        return response()->json(['status' => 'disabled']);
    }
}
