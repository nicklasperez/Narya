<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entry;

class UserController extends Controller
{
    public function profile(Request $request, $id)
    {
        $authUser = $request->user();

        $user = User::withCount(['followers', 'following'])
            ->findOrFail($id);

        $entries = Entry::with('mood')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $isFollowing = false;
        if ($authUser->id !== $user->id) {
            $isFollowing = $authUser->followingUsers()
                ->where('users.id', $user->id)
                ->exists();
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'surname' => $user->surname,
                'profile_picture' => $user->profile_picture,
                'followers_count' => $user->followers_count,
                'following_count' => $user->following_count,
            ],
            'entries' => $entries,
            'is_following' => $isFollowing,
        ]);
    }
}