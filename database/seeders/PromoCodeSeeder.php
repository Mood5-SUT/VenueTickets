<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromoCode;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::create([
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
            'description' => '10% discount on all tickets',
            'scope' => 'global',
            'expires_at' => now()->addMonths(1)
        ]);

        PromoCode::create([
            'code' => 'WELCOME20',
            'type' => 'fixed',
            'value' => 20,
            'is_active' => true,
            'description' => '$20 discount for new users',
            'scope' => 'global',
            'expires_at' => now()->addMonths(1)
        ]);

        PromoCode::create([
            'code' => 'FIFTY',
            'type' => 'percentage',
            'value' => 50,
            'is_active' => true,
            'description' => 'Limited 50% discount',
            'scope' => 'global',
            'max_uses' => 10,
            'expires_at' => now()->addDays(7)
        ]);
    }
}
