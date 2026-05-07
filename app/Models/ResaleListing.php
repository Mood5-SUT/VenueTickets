<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResaleListing extends Model
{
    protected $fillable = [
        'ticket_id',
        'seller_id',
        'event_id',
        'original_price',
        'asking_price',
        'status',
        'is_flagged',
        'flag_reason',
        'price_cap_percentage',
        'exceeds_price_cap',
        'sold_at',
        'buyer_id'
    ];
    
    protected $casts = [
        'original_price' => 'decimal:2',
        'asking_price' => 'decimal:2',
        'is_flagged' => 'boolean',
        'exceeds_price_cap' => 'boolean',
        'sold_at' => 'datetime'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}