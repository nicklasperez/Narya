<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class SpotifyAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $token = $request->query('token');

        $query = http_build_query([
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'), // ❌ sin token aquí
            'scope' => 'user-read-email user-read-private',
            'state' => $token, // ✅ usamos "state" para pasar el token
            'show_dialog' => 'true'
        ]);

        return redirect("https://accounts.spotify.com/authorize?$query");
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $token = $request->input('state'); // ✅ ahora viene aquí

        if (!$code || !$token) {
            return response()->json(['error' => 'Missing code or token'], 400);
        }

        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        $user = $accessToken?->tokenable;

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado con este token'], 401);
        }

        // Pedimos los tokens a Spotify
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'), // ✅ igual al registrado
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);

        if (!$response->ok()) {
            return response()->json(['error' => 'Error al obtener tokens de Spotify'], 500);
        }

        $data = $response->json();

        $user->spotify_id = $this->getSpotifyUserId($data['access_token']);
        $user->spotify_access_token = $data['access_token'];
        $user->spotify_refresh_token = $data['refresh_token'];
        $user->spotify_token_expires_at = now()->addSeconds($data['expires_in']);
        $user->save();

        return redirect('http://localhost:8100/tabs/ajustes?spotify=success');
    }



    private function getSpotifyUserId($accessToken)
    {
        $response = Http::withToken($accessToken)->get('https://api.spotify.com/v1/me');
        return $response->ok() ? $response->json()['id'] : null;
    }
}
