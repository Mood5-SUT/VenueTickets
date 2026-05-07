<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'capacity',
        'phone',
        'email',
        'website',
        'description',
        'image_url',
        'amenities',
        'accessibility_info',
        'is_active'
    ];
    
    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'amenities' => 'array',
        'accessibility_info' => 'array'
    ];
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    public function seatMaps()
    {
        return $this->hasMany(SeatMap::class);
    }
}