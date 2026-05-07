<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerDetail extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'tax_id',
        'business_phone',
        'website',
        'description',
        'status',
        'rejection_reason',
        'suspension_reason',
        'approved_at',
        'approved_by',
        'stripe_connect_id',
        'payouts_enabled',
        'metadata'
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
        'payouts_enabled' => 'boolean',
        'metadata' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}