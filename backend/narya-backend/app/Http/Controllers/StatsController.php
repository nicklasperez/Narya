<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\Mood;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    // Mood dominante por semana
    public function moodDominanteSemana(Request $request)
    {
        $userId = $request->user()->id;

        $entries = Entry::select(
            DB::raw("YEARWEEK(created_at, 3) as week"),
            'mood_id',
            DB::raw('COUNT(*) as count')
        )
            ->where('user_id', $userId)
            ->groupBy('week', 'mood_id')
            ->orderBy('week', 'desc')
            ->get();

        // Procesar para sacar el mood dominante de cada semana
        $dominantePorSemana = $entries
            ->groupBy('week')
            ->map(function ($group) {
                return $group->sortByDesc('count')->first();
            })
            ->values();

        // Adjuntar el nombre del mood
        $dominantePorSemana = $dominantePorSemana->map(function ($item) {
            $mood = Mood::find($item->mood_id);
            return [
                'week' => $item->week,
                'mood' => $mood ? $mood->name : 'Desconocido',
                'mood_id' => $item->mood_id,
                'count' => $item->count,
            ];
        });

        return response()->json($dominantePorSemana);
    }

    // Canciones más escuchadas
    public function cancionesMasEscuchadas(Request $request)
    {
        $userId = $request->user()->id;

        $canciones = Entry::select(
            'song_name',
            'artist_name',
            DB::raw('COUNT(*) as count')
        )
            ->where('user_id', $userId)
            ->groupBy('song_name', 'artist_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return response()->json($canciones);
    }

    // Artistas más frecuentes
    public function artistasMasFrecuentes(Request $request)
    {
        $userId = $request->user()->id;

        $artistas = Entry::select(
            'artist_name',
            DB::raw('COUNT(*) as count')
        )
            ->where('user_id', $userId)
            ->groupBy('artist_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return response()->json($artistas);
    }

    // Reparto total de moods
    public function moodsRepartoTotal(Request $request)
    {
        $userId = $request->user()->id;

        $moods = Entry::select('mood_id', DB::raw('COUNT(*) as count'))
            ->where('user_id', $userId)
            ->groupBy('mood_id')
            ->get();

        // Adjuntar el nombre del mood
        $moods = $moods->map(function ($item) {
            $mood = \App\Models\Mood::find($item->mood_id);
            return [
                'mood' => $mood ? $mood->name : 'Desconocido',
                'mood_id' => $item->mood_id,
                'count' => $item->count,
                'color' => $mood ? $mood->color : '#ffffff' // Esto es clave para el PieChart neon
            ];
        });

        return response()->json($moods);
    }

}
