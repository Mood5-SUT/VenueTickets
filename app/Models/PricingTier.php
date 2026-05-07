<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quantity',
        'sold_count',
        'starts_at',
        'ends_at',
        'is_active',
        'description',
        'max_per_order',
        'min_per_order'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'sold_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'max_per_order' => 'integer',
        'min_per_order' => 'integer'
    ];
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function isAvailable()
    {
        $now = now();
        if (!$this->is_active) return false;
        if ($now < $this->starts_at) return false;
        if ($now > $this->ends_at) return false;
        if ($this->quantity !== null && $this->sold_count >= $this->quantity) return false;
        return true;
    }
}