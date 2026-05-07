<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'seat_map_id',
        'name',
        'color',
        'default_price',
        'capacity',
        'rows',
        'columns',
        'seat_numbers',
        'sort_order'
    ];
    
    protected $casts = [
        'default_price' => 'decimal:2',
        'capacity' => 'integer',
        'rows' => 'integer',
        'columns' => 'integer',
        'seat_numbers' => 'array',
        'sort_order' => 'integer'
    ];
    
    public function seatMap()
    {
        return $this->belongsTo(SeatMap::class);
    }
    
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}