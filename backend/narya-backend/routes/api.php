<?php

use App\Http\Controllers\SongController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AuthController;

// Registro y Login (Público)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
});
