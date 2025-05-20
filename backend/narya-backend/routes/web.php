<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\MoodController;

Route::get('/', function () {
    return view('welcome');
});

// Canciones
Route::post('/songs', [SongController::class, 'store']);
Route::get('/songs/user/{userId}', [SongController::class, 'userSongs']);

// Seguidores
Route::post('/follow', [FollowController::class, 'follow']);
Route::post('/unfollow', [FollowController::class, 'unfollow']);
Route::get('/followers/{userId}', [FollowController::class, 'getFollowers']);
Route::get('/following/{userId}', [FollowController::class, 'getFollowing']);

// Emociones (moods)
Route::get('/moods', [MoodController::class, 'index']);