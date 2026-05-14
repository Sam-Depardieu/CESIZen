<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RelaxationActivity;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelaxationController extends Controller
{
    public function index(Request $request)
    {
        $query = RelaxationActivity::query();

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $activities = $query->get();

        $categories = RelaxationActivity::distinct()->pluck('category');

        $favorites = []; // TODO: Implémenter la logique des favoris si nécessaire

        return view('relaxation.index', compact('activities', 'favorites', 'categories'));
    }

    public function toggleFavorite($id)
    {
        // Fonctionnalité désactivée pour le moment
        return response()->json(['status' => 'disabled']);
    }
}
