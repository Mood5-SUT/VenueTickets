<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'provider',
        'provider_id',
        'is_active',
        'is_verified',
        'otp_code',
        'otp_expires_at',
        'email_verified_at'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_verified' => 'boolean'
    ];
    
    public function organizerDetail()
    {
        return $this->hasOne(OrganizerDetail::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function resaleListings()
    {
        return $this->hasMany(ResaleListing::class, 'seller_id');
    }
    
    public function bans()
    {
        return $this->hasMany(UserBan::class);
    }
    
    public function isBanned()
    {
        return $this->bans()->where('is_active', true)->exists();
    }
    
    public function isOrganizer()
    {
        return $this->hasRole('organizer');
    }
    
    public function isAdmin()
    {
        return $this->hasRole('super-admin') || $this->hasRole('admin');
    }
    
    public function isOtpExpired()
    {
        return $this->otp_expires_at && $this->otp_expires_at->isPast();
    }
    
    public function generateOtp()
    {
        $this->otp_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();
        
        return $this->otp_code;
    }
}