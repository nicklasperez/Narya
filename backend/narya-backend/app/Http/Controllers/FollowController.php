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
            'followed_id' => 'required|exists:users,id',
        ]);

        $followerId = $request->user()->id;
        $followedId = $request->followed_id;

        if ($followerId == $followedId) {
            return response()->json(['error' => 'No puedes seguirte a ti mismo.'], 422);
        }

        $existingFollow = Follow::where('follower_id', $followerId)
            ->where('followed_id', $followedId)
            ->first();

        if ($existingFollow) {
            return response()->json(['error' => 'Ya estás siguiendo a este usuario.'], 422);
        }

        $follow = Follow::create([
            'follower_id' => $followerId,
            'followed_id' => $followedId,
        ]);

        return response()->json(['message' => 'Ahora sigues al usuario.', 'data' => $follow], 201);
    }

    /**
     * Dejar de seguir a un usuario.
     */
    public function unfollow(Request $request)
    {
        $request->validate([
            'followed_id' => 'required|exists:users,id',
        ]);

        $followerId = $request->user()->id;
        $followedId = $request->followed_id;

        $follow = Follow::where('follower_id', $followerId)
            ->where('followed_id', $followedId)
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

        $followers = $user->followers()
            ->with('follower:id,username,profile_picture')
            ->get()
            ->pluck('follower');

        return response()->json($followers);
    }

    /**
     * Obtener los usuarios que un usuario sigue.
     */
    public function getFollowing($userId)
    {
        $user = User::findOrFail($userId);

        $following = $user->following()
            ->with('followed:id,username,profile_picture')
            ->get()
            ->pluck('followed');

        return response()->json($following);
    }
}
