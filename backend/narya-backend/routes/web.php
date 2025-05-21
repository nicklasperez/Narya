<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;
use App\Http\Controllers\MoodController;

Route::get('/', function () {
    return view('welcome');
});
