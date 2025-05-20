<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\JsonResponse;

class MoodController extends Controller
{
    /**
     * Devolver todos los moods disponibles.
     */
    public function index(): JsonResponse
    {
        $moods = Mood::orderBy('name')->get();

        return response()->json($moods);
    }
}
