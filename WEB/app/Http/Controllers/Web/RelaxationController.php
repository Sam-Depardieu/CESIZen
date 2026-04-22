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
        $favorites = Auth::check() ? Auth::user()->favoritedBy->pluck('id')->toArray() : [];
        return view('relaxation.index', compact('activities', 'favorites'));
    }

    public function toggleFavorite($id)
    {
        $userId = Auth::id();
        $favorite = Favorite::where('user_id', $userId)
                            ->where('relaxation_activity_id', $id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'relaxation_activity_id' => $id
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
