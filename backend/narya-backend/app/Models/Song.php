<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'spotify_song_id',
        'mood_id',
    ];

    /**
     * Relación con el usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el estado emocional (mood).
     */
    public function mood(): BelongsTo
    {
        return $this->belongsTo(Mood::class);
    }
}
