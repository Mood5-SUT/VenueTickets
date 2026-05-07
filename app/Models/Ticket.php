<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'qr_code',
        'order_id',
        'event_id',
        'user_id',
        'pricing_tier_id',
        'seat_id',
        'section',
        'row',
        'seat_number',
        'price',
        'status',
        'checked_in_at',
        'checked_in_by',
        'transfer_code',
        'transferred_to',
        'transferred_at',
        'void_reason',
        'email_sent',
        'email_sent_at',
        'metadata'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'checked_in_at' => 'datetime',
        'transferred_at' => 'datetime',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
        'metadata' => 'array'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function pricingTier()
    {
        return $this->belongsTo(PricingTier::class);
    }
    
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    
    public function transferredTo()
    {
        return $this->belongsTo(User::class, 'transferred_to');
    }
    
    public function resaleListings()
    {
        return $this->hasMany(ResaleListing::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            if (!$ticket->ticket_number) {
                $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
            }
            if (!$ticket->qr_code) {
                $ticket->qr_code = hash('sha256', $ticket->ticket_number . time());
            }
        });
    }
}