<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedPayment extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'payment_provider',
        'error_code',
        'error_message',
        'amount',
        'currency',
        'raw_response'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function scopeByProvider($query, $provider)
    {
        return $query->where('payment_provider', $provider);
    }
}