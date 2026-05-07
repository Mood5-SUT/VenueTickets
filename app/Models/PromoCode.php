<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'max_uses',
        'used_count',
        'max_uses_per_user',
        'starts_at',
        'expires_at',
        'is_active',
        'scope',
        'description',
        'applicable_events',
        'applicable_tiers'
    ];
    
    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'max_uses_per_user' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'applicable_events' => 'array',
        'applicable_tiers' => 'array'
    ];
    
    public function usage()
    {
        return $this->hasMany(PromoCodeUsage::class);
    }
    
    public function isValid()
    {
        $now = now();
        if (!$this->is_active) return false;
        if ($this->starts_at && $now < $this->starts_at) return false;
        if ($now > $this->expires_at) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        return true;
    }
}