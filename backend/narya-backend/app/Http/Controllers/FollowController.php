<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Seguir a un usuario.
     */
    public function follow(Request $request)
    {
        $request->validate([
            'follower_id' => 'required|exists:users,id',
            'followed_id' => 'required|exists:users,id',
        ]);

        // Evitar que un usuario se siga a sí mismo
        if ($request->follower_id == $request->followed_id) {
            return response()->json(['error' => 'No puedes seguirte a ti mismo.'], 422);
        }

        // Verificar si ya está siguiendo
        $existingFollow = Follow::where('follower_id', $request->follower_id)
                                ->where('followed_id', $request->followed_id)
                                ->first();

        if ($existingFollow) {
            return response()->json(['error' => 'Ya estás siguiendo a este usuario.'], 422);
        }

        $follow = Follow::create($request->all());

        return response()->json(['message' => 'Ahora sigues al usuario.', 'data' => $follow], 201);
    }

    /**
     * Dejar de seguir a un usuario.
     */
    public function unfollow(Request $request)
    {
        $request->validate([
            'follower_id' => 'required|exists:users,id',
            'followed_id' => 'required|exists:users,id',
        ]);

        $follow = Follow::where('follower_id', $request->follower_id)
                        ->where('followed_id', $request->followed_id)
                        ->first();

        if (!$follow) {
            return response()->json(['error' => 'No estás siguiendo a este usuario.'], 404);
        }

        $follow->delete();

        return response()->json(['message' => 'Has dejado de seguir al usuario.']);
    }

    /**
     * Obtener los seguidores de un usuario.
     */
    public function getFollowers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()->with('follower')->get();

        return response()->json($followers);
    }

    /**
     * Obtener los usuarios que un usuario sigue.
     */
    public function getFollowing($userId)
    {
        $user = User::findOrFail($userId);
        $following = $user->following()->with('followed')->get();

        return response()->json($following);
    }
}
