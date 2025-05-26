<?php

use App\Http\Controllers\SongController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpotifyAuthController;
use App\Http\Controllers\EntryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


// Registro y Login (Público)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Spotify Auth
Route::get('/spotify/redirect', [SpotifyAuthController::class, 'redirect']);
Route::get('/spotify/callback', [SpotifyAuthController::class, 'callback']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Moods
    Route::get('/moods', [MoodController::class, 'index']);

    // Songs
    Route::post('/songs', [SongController::class, 'store']);
    Route::get('/songs/user/{userId}', [SongController::class, 'userSongs']);

    // Follows
    Route::post('/follow', [FollowController::class, 'follow']);
    Route::post('/unfollow', [FollowController::class, 'unfollow']);
    Route::get('/followers/{userId}', [FollowController::class, 'getFollowers']);
    Route::get('/following/{userId}', [FollowController::class, 'getFollowing']);

    // Entradas
    Route::post('/entries', [EntryController::class, 'store']);
    Route::get('/entries', [EntryController::class, 'index']);
    Route::get('/feed', [EntryController::class, 'feed']);

    // Spotify — Desvincular cuenta
    Route::post('/spotify/unlink', function () {
        $user = auth()->user();
        $user->spotify_id = null;
        $user->spotify_access_token = null;
        $user->spotify_refresh_token = null;
        $user->spotify_token_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Cuenta de Spotify desvinculada']);
    });

    // Spotify - Buscar Canciones

    Route::get('/spotify/search', function (Request $request) {
        $query = $request->input('q');
        $user = auth()->user();

        if (!$user->spotify_access_token || !$user->spotify_refresh_token) {
            return response()->json(['error' => 'Usuario no tiene cuenta de Spotify vinculada.'], 400);
        }

        // Refrescar token si ha expirado
        if ($user->spotify_token_expires_at && now()->gte($user->spotify_token_expires_at)) {
            $refreshResponse = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->spotify_refresh_token,
                'client_id' => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
            ]);

            if (!$refreshResponse->ok()) {
                return response()->json(['error' => 'Error al refrescar token de Spotify', 'details' => $refreshResponse->json()], 500);
            }

            $newData = $refreshResponse->json();
            $user->spotify_access_token = $newData['access_token'];
            $user->spotify_token_expires_at = now()->addSeconds($newData['expires_in']);
            $user->save();
        }

        // Buscar en Spotify con token actualizado
        $response = Http::withToken($user->spotify_access_token)
            ->get("https://api.spotify.com/v1/search", [
                'q' => $query,
                'type' => 'track',
                'limit' => 10
            ]);

        if (!$response->ok()) {
            return response()->json(['error' => 'Error al buscar en Spotify', 'details' => $response->json()], $response->status());
        }

        return $response->json();
    });
});
