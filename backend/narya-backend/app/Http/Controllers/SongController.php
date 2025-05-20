<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Mood;
use App\Models\User;

class SongController extends Controller
{
    /**
     * Guardar una nueva entrada emocional con canción.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'spotify_song_id' => 'required|string',
            'mood_id' => 'required|exists:moods,id',
        ]);

        $song = Song::create($validated);

        return response()->json([
            'message' => 'Entrada guardada con éxito.',
            'data' => $song->load(['mood', 'user']),
        ], 201);
    }

    /**
     * Obtener todas las entradas de un usuario.
     */
    public function userSongs($userId)
    {
        $songs = Song::with('mood')
                     ->where('user_id', $userId)
                     ->orderByDesc('created_at')
                     ->get();

        return response()->json($songs);
    }
}
