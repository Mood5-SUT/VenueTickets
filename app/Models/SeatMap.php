<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatMap extends Model
{
    protected $fillable = [
        'name',
        'venue_id',
        'description',
        'layout_data',
        'total_seats',
        'is_active'
    ];
    
    protected $casts = [
        'layout_data' => 'array',
        'total_seats' => 'integer',
        'is_active' => 'boolean'
    ];
    
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
    
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}