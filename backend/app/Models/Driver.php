<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

    protected $fillable = ['name', 'nickname', 'avatar', 'user_email'];

    protected $appends = ['avatar_url', 'avatar_size_formatted'];

    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            return null;
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    public function getAvatarSizeFormattedAttribute()
    {
        if (empty($this->avatar) || filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
                $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($this->avatar);
                return round($bytes / 1024) . ' KB';
            }
        } catch (\Exception $e) {
            // fail silently
        }

        return null;
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function raceParticipants()
    {
        return $this->hasMany(RaceParticipant::class);
    }

    public function lapTimes()
    {
        return $this->hasMany(LapTime::class);
    }
}
