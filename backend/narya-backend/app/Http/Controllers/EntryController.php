<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = Entry::where('user_id', $request->user()->id)
            ->with('mood')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'entries' => $entries
        ]);
    }

    public function feed(Request $request)
    {
        $user = $request->user();
        $followedIds = $user->followingUsers()->pluck('users.id')->push($user->id); // asumiendo que tienes belongsToMany

        $entries = Entry::whereIn('user_id', $followedIds)
            ->with(['mood', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['feed' => $entries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood_id' => 'required|exists:moods,id',
            'song_name' => 'required|string|max:255',
            'artist_name' => 'nullable|string|max:255',
            'spotify_track_id' => 'nullable|string|max:255',
            'album_image' => 'nullable|string|max:1024', // ✅ añadir esto
        ]);

        $entry = Entry::create([
            'user_id' => $request->user()->id,
            'mood_id' => $validated['mood_id'],
            'song_name' => $validated['song_name'],
            'artist_name' => $validated['artist_name'] ?? null,
            'spotify_track_id' => $validated['spotify_track_id'] ?? null,
            'album_image' => $validated['album_image'] ?? null, // ✅ guardar imagen
        ]);

        return response()->json([
            'message' => 'Entrada creada con éxito',
            'entry' => $entry->load('mood'),
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entry $entry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entry $entry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entry $entry)
    {
        //
    }
}
