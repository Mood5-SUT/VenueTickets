<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'event_id',
        'subtotal',
        'service_fee',
        'discount_amount',
        'total_amount',
        'currency',
        'status',
        'payment_status',
        'payment_method',
        'payment_id',
        'promo_code',
        'resale_listing_id',
        'paid_at',
        'refunded_at',
        'refund_reason',
        'refund_id',
        'billing_info',
        'metadata'
    ];
    
    protected $casts = [
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'billing_info' => 'array',
        'metadata' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function resaleListing()
    {
        return $this->belongsTo(ResaleListing::class);
    }
    
    public function promoCodeUsage()
    {
        return $this->hasOne(PromoCodeUsage::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'VT-' . strtoupper(uniqid());
            }
        });
    }
}