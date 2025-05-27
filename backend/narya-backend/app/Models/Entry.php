<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'user_id',
        'mood_id',
        'song_name',
        'artist_name',
        'spotify_track_id',
        'album_image',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

}
