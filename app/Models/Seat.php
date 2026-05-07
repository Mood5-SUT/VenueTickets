<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'zone_id',
        'seat_map_id',
        'seat_number',
        'row_label',
        'row_number',
        'column_number',
        'status',
        'price_override',
        'metadata'
    ];
    
    protected $casts = [
        'row_number' => 'integer',
        'column_number' => 'integer',
        'price_override' => 'decimal:2',
        'metadata' => 'array'
    ];
    
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    
    public function seatMap()
    {
        return $this->belongsTo(SeatMap::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}