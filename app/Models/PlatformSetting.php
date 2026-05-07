<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description'
    ];
    
    protected $casts = [
        'type' => 'string'
    ];
    
    public static function get($key, $default = null)
    {
        return Cache::remember('platform_setting_' . $key, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return $setting->castValue();
        });
    }
    
    public static function set($key, $value, $type = 'string')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type
            ]
        );
        
        Cache::forget('platform_setting_' . $key);
        
        return $setting;
    }
    
    public function castValue()
    {
        switch ($this->type) {
            case 'integer':
                return (int) $this->value;
            case 'boolean':
                return (bool) $this->value;
            case 'json':
                return json_decode($this->value, true);
            case 'float':
                return (float) $this->value;
            default:
                return $this->value;
        }
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($setting) {
            Cache::forget('platform_setting_' . $setting->key);
        });
        
        static::deleted(function ($setting) {
            Cache::forget('platform_setting_' . $setting->key);
        });
    }
    
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}