<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SpotifyAuthController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            'scope' => 'user-read-email user-read-private',
        ]);

        return redirect("https://accounts.spotify.com/authorize?$query");
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return response()->json(['error' => 'No code provided'], 400);
        }

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);

        if (!$response->ok()) {
            return response()->json(['error' => 'Failed to get tokens'], 500);
        }

        $data = $response->json();

        $user = Auth::user();
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
