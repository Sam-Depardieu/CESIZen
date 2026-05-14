<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        $query = Information::where('is_published', true);

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $informations = $query->orderBy('created_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        $categories = Information::where('is_published', true)
            ->distinct()
            ->pluck('category');

        return view('informations.index', compact('informations', 'categories'));
    }

    public function show($id)
    {
        $information = Information::where('is_published', true)->findOrFail($id);

        return view('informations.show', compact('information'));
    }
}
