<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'organizer_id',
        'amount',
        'currency',
        'status',
        'stripe_payout_id',
        'stripe_transfer_id',
        'period_start',
        'period_end',
        'description',
        'order_ids',
        'processed_at',
        'failure_reason'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'processed_at' => 'datetime',
        'order_ids' => 'array'
    ];
    
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}