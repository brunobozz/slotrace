<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapTime extends Model
{
    /** @use HasFactory<\Database\Factories\LapTimeFactory> */
    use HasFactory;

    protected $fillable = ['race_id', 'driver_id', 'lane_number', 'lap_number', 'lap_time_seconds'];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
