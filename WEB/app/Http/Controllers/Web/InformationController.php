<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index()
    {
        $informations = Information::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('informations.index', compact('informations'));
    }

    public function show($id)
    {
        $information = Information::where('is_published', true)->findOrFail($id);

        return view('informations.show', compact('information'));
    }
}
