<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $fillable = [
        'ticket_id',
        'event_id',
        'scanned_by',
        'scan_result',
        'device_info',
        'ip_address',
        'notes'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
    
    public function scopeValid($query)
    {
        return $query->where('scan_result', 'valid');
    }
    
    public function scopeAlreadyUsed($query)
    {
        return $query->where('scan_result', 'already_used');
    }
    
    public function scopeInvalid($query)
    {
        return $query->where('scan_result', 'invalid');
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }
}