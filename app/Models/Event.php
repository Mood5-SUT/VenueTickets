<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'organizer_id',
        'venue_id',
        'seat_map_id',
        'event_date',
        'end_date',
        'doors_open',
        'status',
        'event_type',
        'image_url',
        'banner_url',
        'age_restriction',
        'is_featured',
        'resale_enabled',
        'resale_price_cap_percentage',
        'metadata'
    ];
    
    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'doors_open' => 'datetime',
        'is_featured' => 'boolean',
        'resale_enabled' => 'boolean',
        'metadata' => 'array'
    ];
    
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    
    public function seatMap()
    {
        return $this->belongsTo(SeatMap::class);
    }
    
    public function pricingTiers()
    {
        return $this->hasMany(PricingTier::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function resaleListings()
    {
        return $this->hasMany(ResaleListing::class);
    }
    
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }
    
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }
}