<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    /** @use HasFactory<\Database\Factories\TrackFactory> */
    use HasFactory;

    protected $fillable = ['name', 'lanes_count', 'length_meters', 'best_lap_time', 'best_lap_driver_id', 'image', 'user_email'];

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


    public function bestLapDriver()
    {
        return $this->belongsTo(Driver::class, 'best_lap_driver_id');
    }

    public function races()
    {
        return $this->hasMany(Race::class);
    }
}
