<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

    // Método para cambiar la contraseña del usuario
    public function changePassword(Request $request)
    {
        $user = $request->user();

        // Validar datos
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                'min:8', // puedes poner reglas más estrictas aquí
                'regex:/[A-Z]/',      // al menos una mayúscula
                'regex:/[0-9]/',      // al menos un número
                'regex:/[@$!%*?&#]/', // al menos un carácter especial
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta.'], 403);
        }

        // Actualizar contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.']);
    }

    // Método para cambiar la foto de perfil del usuario
    public function changeProfilePicture(Request $request)
    {
        $user = $request->user();

        // Validar que haya archivo
        if (!$request->hasFile('profile_picture')) {
            return response()->json(['message' => 'No se ha enviado ninguna imagen.'], 400);
        }

        $file = $request->file('profile_picture');

        // Validar tipo de imagen
        $request->validate([
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        // Guardar imagen
        $path = $file->store('profile_pictures', 'public');

        // Actualizar en el usuario
        $user->profile_picture = asset(Storage::url($path));

        $user->save();

        return response()->json([
            'message' => 'Foto de perfil actualizada.',
            'profile_picture' => asset(Storage::url($path)),
        ]);
    }
}