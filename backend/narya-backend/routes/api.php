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
use App\Http\Controllers\UserController;


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
    Route::get('/users/{id}/followers', [FollowController::class, 'getFollowers']);
    Route::get('/users/{id}/following', [FollowController::class, 'getFollowing']);


    // Entradas
    Route::post('/entries', [EntryController::class, 'store']);
    Route::get('/entries', [EntryController::class, 'index']);
    Route::get('/feed', [EntryController::class, 'feed']);

    // Perfil de usuario
    Route::get('/users/{id}/profile', [UserController::class, 'profile']);

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

        if (!$query) {
            return response()->json(['error' => 'Missing query'], 400);
        }

        $user = $request->user();

        // ✅ Si el usuario tiene sesión Spotify → usar su token
        if ($user && $user->spotify_access_token && $user->spotify_token_expires_at > now()) {
            $accessToken = $user->spotify_access_token;
        } else {
            // 🔑 Token público temporal con client_credentials
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
                'client_id' => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
            ]);

            if (!$response->ok()) {
                return response()->json(['error' => 'No se pudo generar token público de Spotify'], 500);
            }

            $accessToken = $response->json()['access_token'];
        }

        // 🛰️ Realizamos la búsqueda
        $spotifyResponse = Http::withToken($accessToken)->get("https://api.spotify.com/v1/search", [
            'q' => $query,
            'type' => 'track',
            'limit' => 10
        ]);

        if (!$spotifyResponse->ok()) {
            return response()->json(['error' => 'Error al buscar en Spotify'], $spotifyResponse->status());
        }

        return $spotifyResponse->json();
    });
});
