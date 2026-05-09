<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\PricingTier;
use Illuminate\Support\Facades\DB;

class PublicEventController extends Controller
{
    public function show($id)
    {
        // Fetch the event with its active pricing tiers and venue
        $event = Event::with(['venue', 'pricingTiers' => function($q) {
            $q->where('is_active', true)->orderBy('price', 'asc');
        }])->findOrFail($id);
        
        // Fetch already taken seats for this event
        $takenSeats = Ticket::where('event_id', $id)
            ->where('status', 'valid')
            ->get(['row', 'seat_number'])
            ->map(function($t) {
                return $t->row . '-' . $t->seat_number;
            })->toArray();

        // Fallback: Ensure there's always at least a Standard tier
        if ($event->pricingTiers->isEmpty()) {
            $defaultTier = new \App\Models\PricingTier();
            $defaultTier->id = 9999; // Mock ID
            $defaultTier->name = 'Standard Entry';
            $defaultTier->price = $event->metadata['base_price'] ?? 50;
            $defaultTier->description = 'General admission access';
            $event->setRelation('pricingTiers', collect([$defaultTier]));
        }
            
        return view('event_details', compact('event', 'takenSeats'));
    }
    
    public function checkout(Request $request, $id)
    {
        $request->validate([
            'selected_seats' => 'required|string', // Comma-separated string like "A-1,A-2"
            'tier_id' => 'required|exists:pricing_tiers,id'
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to purchase tickets.');
        }

        $event = Event::findOrFail($id);
        $user = auth()->user();
        $seats = explode(',', $request->selected_seats);
        $tier = PricingTier::findOrFail($request->tier_id);

        try {
            return DB::transaction(function() use ($event, $user, $seats, $tier) {
                // 1. Double check availability (Race condition handling)
                foreach ($seats as $seatKey) {
                    list($row, $num) = explode('-', $seatKey);
                    $exists = Ticket::where('event_id', $event->id)
                        ->where('row', $row)
                        ->where('seat_number', $num)
                        ->where('status', 'valid')
                        ->lockForUpdate() // Database lock for security
                        ->exists();
                    
                    if ($exists) {
                        throw new \Exception("Seat $row$num is already taken.");
                    }
                }

                $totalAmount = $tier->price * count($seats);

                // 2. Create Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'subtotal' => $totalAmount,
                    'total_amount' => $totalAmount,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'Credit Card (Mock)',
                    'paid_at' => now(),
                ]);

                // 3. Create Tickets
                foreach ($seats as $seatKey) {
                    list($row, $num) = explode('-', $seatKey);
                    Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'pricing_tier_id' => $tier->id,
                        'row' => $row,
                        'seat_number' => $num,
                        'price' => $tier->price,
                        'status' => 'valid',
                        'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                        'qr_code' => hash('sha256', $order->id . $seatKey . time())
                    ]);
                }

                return redirect()->route('order.success', $order->id);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['event.venue', 'tickets.pricingTier'])->findOrFail($orderId);
        
        // Security check
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('order_success', compact('order'));
    }

    public function myTickets()
    {
        $tickets = Ticket::with(['event.venue', 'pricingTier', 'order'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('my_tickets', compact('tickets'));
    }

    public function mySchedule()
    {
        $tickets = Ticket::with(['event.venue', 'pricingTier'])
            ->where('user_id', auth()->id())
            ->whereHas('event', function($q) {
                $q->where('event_date', '>=', now()->startOfDay());
            })
            ->get()
            ->sortBy('event.event_date')
            ->groupBy(function($t) {
                return \Carbon\Carbon::parse($t->event->event_date)->format('Y-m-d');
            });

        return view('my_schedule', compact('tickets'));
    }
}
