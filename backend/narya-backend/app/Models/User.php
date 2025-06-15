<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'password',
        'birthdate',
        'profile_picture',
        'spotify_id',
        'spotify_access_token',
        'spotify_refresh_token',
        'spotify_token_expires_at',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'spotify_access_token',
        'spotify_refresh_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'spotify_token_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the songs associated with the user.
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    /**
     * Get the followers of the user.
     */
    public function followers(): HasMany
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }

    /**
     * Get the users the user is following.
     */
    public function following(): HasMany
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function followersUsers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function followingUsers()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->profile_picture)) {
                $user->profile_picture = config('app.url') . '/assets/default-avatar.png';
            }
        });
    }


}
