<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $fillable = [
        'user_id',
        'banned_by',
        'reason',
        'banned_at',
        'unbanned_at',
        'unbanned_by',
        'is_active'
    ];
    
    protected $casts = [
        'banned_at' => 'datetime',
        'unbanned_at' => 'datetime',
        'is_active' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
    
    public function unbannedBy()
    {
        return $this->belongsTo(User::class, 'unbanned_by');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}