<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    /** @use HasFactory<\Database\Factories\RaceFactory> */
    use HasFactory;

    protected $fillable = ['track_id', 'name', 'status', 'type', 'laps_limit', 'duration_seconds', 'user_email'];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function participants()
    {
        return $this->hasMany(RaceParticipant::class);
    }

    public function lapTimes()
    {
        return $this->hasMany(LapTime::class);
    }
}
