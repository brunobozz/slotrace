<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

    protected $fillable = ['name', 'brand', 'model', 'scale', 'driver_id', 'image', 'user_email'];

    protected $appends = ['image_url', 'image_size_formatted'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    public function getImageSizeFormattedAttribute()
    {
        if (empty($this->image) || filter_var($this->image, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
                $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($this->image);
                return round($bytes / 1024) . ' KB';
            }
        } catch (\Exception $e) {
            // fail silently
        }

        return null;
    }


    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function raceParticipants()
    {
        return $this->hasMany(RaceParticipant::class);
    }
}
