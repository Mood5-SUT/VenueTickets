<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatHold extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'session_id',
        'row',
        'seat_number',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
