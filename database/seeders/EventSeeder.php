<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Venue;
use App\Models\PricingTier;
use App\Models\PromoCode;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Venue
        $venue = Venue::create([
            'name' => 'Grand Millennium Arena',
            'location' => 'Downtown Metropolis',
            'capacity' => 5000,
            'metadata' => ['parking' => true, 'accessibility' => true]
        ]);

        // 2. Create an Event
        $event = Event::create([
            'venue_id' => $venue->id,
            'name' => 'Midnight Symphony: Neon Nights',
            'description' => 'An immersive electronic music experience with world-class lighting and sound.',
            'event_date' => Carbon::now()->addDays(30),
            'event_type' => 'concert',
            'status' => 'published',
            'image_url' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            'metadata' => ['base_price' => 75]
        ]);

        // 3. Create Pricing Tiers
        PricingTier::create([
            'event_id' => $event->id,
            'name' => 'Early Bird',
            'price' => 50.00,
            'description' => 'Limited time offer for early supporters.',
            'is_active' => true
        ]);

        PricingTier::create([
            'event_id' => $event->id,
            'name' => 'Regular',
            'price' => 75.00,
            'description' => 'General admission to the main arena.',
            'is_active' => true
        ]);

        PricingTier::create([
            'event_id' => $event->id,
            'name' => 'VIP',
            'price' => 150.00,
            'description' => 'Priority entry and access to the VIP lounge.',
            'is_active' => true
        ]);

        // 4. Create another Event (Sports)
        $sportsEvent = Event::create([
            'venue_id' => $venue->id,
            'name' => 'Champions Cup Final',
            'description' => 'The ultimate showdown between the world\'s best athletes.',
            'event_date' => Carbon::now()->addDays(45),
            'event_type' => 'sports',
            'status' => 'published',
            'image_url' => 'https://images.unsplash.com/photo-1504450758481-7338eba7524a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            'metadata' => ['base_price' => 100]
        ]);

        PricingTier::create([
            'event_id' => $sportsEvent->id,
            'name' => 'Regular',
            'price' => 100.00,
            'description' => 'Standard seating.',
            'is_active' => true
        ]);

        PricingTier::create([
            'event_id' => $sportsEvent->id,
            'name' => 'VIP Box',
            'price' => 500.00,
            'description' => 'Luxury box experience.',
            'is_active' => true
        ]);

        // 5. Create a Promo Code
        PromoCode::create([
            'code' => 'WELCOME20',
            'type' => 'percentage',
            'value' => 20,
            'is_active' => true,
            'expires_at' => Carbon::now()->addMonths(3)
        ]);
    }
}
