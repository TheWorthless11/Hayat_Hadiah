<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's saved Qibla locations
     */
    public function savedQiblaLocations()
    {
        return $this->hasMany(SavedQiblaLocation::class);
    }

    /**
     * Get the user's favorite Qibla locations
     */
    public function favoriteQiblaLocations()
    {
        return $this->hasMany(SavedQiblaLocation::class)->where('is_favorite', true);
    }

    /**
     * Get the user's Qibla compass usage logs
     */
    public function qiblaCompassLogs()
    {
        return $this->hasMany(QiblaCompassLog::class);
    }

    /**
     * Get the user's prayer preferences
     */
    public function prayerPreferences()
    {
        return $this->hasOne(UserPrayerPreference::class);
    }
}
