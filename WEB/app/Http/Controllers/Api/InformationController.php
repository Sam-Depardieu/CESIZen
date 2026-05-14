<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index()
    {
        $informations = Information::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $informations
        ]);
    }

    public function show($id)
    {
        $information = Information::where('is_published', true)->find($id);

        if (!$information) {
            return response()->json([
                'status' => 'error',
                'message' => 'Information non trouvée'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $information
        ]);
    }
}
