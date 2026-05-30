<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaceParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\RaceParticipantFactory> */
    use HasFactory;

    protected $fillable = ['race_id', 'driver_id', 'car_id', 'lane_number', 'status'];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
