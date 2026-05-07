<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description'
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
    
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}