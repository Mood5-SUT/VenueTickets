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
        'is_active',
        'email_verified_at'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
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
}