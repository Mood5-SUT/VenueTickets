<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\PricingTier;
use Illuminate\Support\Facades\DB;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');
        $location = $request->input('location');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        $events = Event::with('venue')
            ->where('status', 'published')
            ->when($query, function($q) use ($query) {
                $q->where(function($sq) use ($query) {
                    $sq->where('name', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->when($type, function($q) use ($type) {
                $q->where('event_type', $type);
            })
            ->when($location, function($q) use ($location) {
                $q->whereHas('venue', function($vq) use ($location) {
                    $vq->where('city', 'like', "%{$location}%")
                       ->orWhere('name', 'like', "%{$location}%");
                });
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                $q->whereDate('event_date', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $q->whereDate('event_date', '<=', $dateTo);
            })
            ->orderBy('event_date', 'asc')
            ->paginate(12);

        return view('user.browse_events', compact('events', 'query', 'type', 'location', 'dateFrom', 'dateTo'));
    }

    public function show($id)
    {
        $event = Event::with(['venue', 'pricingTiers' => function($q) {
            $q->where('is_active', true)->orderBy('price', 'asc');
        }])->findOrFail($id);
        
        $takenSeats = Ticket::where('event_id', $id)
            ->where('status', 'valid')
            ->get(['row', 'seat_number'])
            ->map(function($t) {
                return $t->row . '-' . $t->seat_number;
            })->toArray();

        $heldSeats = \App\Models\SeatHold::where('event_id', $id)
            ->active()
            ->where('user_id', '!=', auth()->id())
            ->where('session_id', '!=', session()->getId())
            ->get(['row', 'seat_number'])
            ->map(function($h) {
                return $h->row . '-' . $h->seat_number;
            })->toArray();
            
        return view('user.event_details', compact('event', 'takenSeats', 'heldSeats'));
    }
    
    public function checkout(Request $request, $id)
    {
        $request->validate([
            'selected_seats' => 'required|string',
            'tier_id' => 'required|exists:pricing_tiers,id'
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to proceed.');
        }

        $event = Event::with('venue')->findOrFail($id);
        $tier = PricingTier::findOrFail($request->tier_id);
        $seats = explode(',', $request->selected_seats);
        $promoCode = $request->promo_code;
        
        $subtotal = $tier->price * count($seats);
        $discount = 0;
        $total = $subtotal;

        if ($promoCode) {
            $promo = PromoCode::where('code', $promoCode)->where('is_active', true)->first();
            if ($promo) {
                if ($promo->scope === 'event_specific' && !in_array($id, $promo->applicable_events ?? [])) {
                    $promo = null;
                }
            }

            if ($promo) {
                if ($promo->type === 'percentage') {
                    $discount = ($subtotal * $promo->value) / 100;
                } else {
                    $discount = min($promo->value, $subtotal);
                }
                $total -= $discount;
            }
        }

        return view('user.checkout', compact('event', 'tier', 'seats', 'subtotal', 'discount', 'total', 'promoCode'));
    }

    public function purchase(Request $request, $id)
    {
        $request->validate([
            'selected_seats' => 'required|string',
            'tier_id' => 'required|exists:pricing_tiers,id',
            'promo_code' => 'nullable|string'
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to purchase tickets.');
        }

        $event = Event::findOrFail($id);
        $user = auth()->user();
        $seats = explode(',', $request->selected_seats);
        $tier = PricingTier::findOrFail($request->tier_id);

        try {
            return DB::transaction(function() use ($event, $user, $seats, $tier, $request) {
                $idempotencyKey = $request->input('idempotency_key');
                if ($idempotencyKey && cache()->has('order_processed_' . $idempotencyKey)) {
                    return redirect()->route('my_orders')->with('info', 'Order already processed.');
                }

                foreach ($seats as $seatKey) {
                    $row = 'GA'; $num = 0;
                    if (str_contains($seatKey, '-')) {
                        list($row, $num) = explode('-', $seatKey);
                    } else { $row = $seatKey; }
                    
                    $exists = Ticket::where('event_id', $event->id)
                        ->where('row', $row)
                        ->where('seat_number', $num)
                        ->where('status', 'valid')
                        ->lockForUpdate()
                        ->exists();
                    
                    if ($exists) {
                        throw new \Exception("Seat $row$num is already taken.");
                    }

                    $hold = \App\Models\SeatHold::where('event_id', $event->id)
                        ->where('row', $row)
                        ->where('seat_number', $num)
                        ->active()
                        ->first();
                    
                    if ($hold && $hold->user_id !== $user->id && $hold->session_id !== session()->getId()) {
                        throw new \Exception("Seat $row$num is currently held by another user.");
                    }
                }

                $totalAmount = $tier->price * count($seats);
                $discount = 0;
                $promoCodeId = null;

                if ($request->promo_code) {
                    $promo = PromoCode::where('code', $request->promo_code)
                        ->where('is_active', true)
                        ->where(function($q) {
                            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                        })
                        ->first();

                    if ($promo && $promo->scope === 'event_specific' && !in_array($event->id, $promo->applicable_events ?? [])) {
                        $promo = null;
                    }

                    if ($promo) {
                        if ($promo->type === 'percentage') {
                            $discount = ($totalAmount * $promo->value) / 100;
                        } else {
                            $discount = min($promo->value, $totalAmount);
                        }
                        $totalAmount -= $discount;
                        $promoCodeId = $promo->id;
                    }
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'promo_code_id' => $promoCodeId,
                    'discount_amount' => $discount,
                    'subtotal' => $tier->price * count($seats),
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'payment_method' => 'Stripe',
                    'order_number' => 'ORD-' . strtoupper(uniqid())
                ]);

                foreach ($seats as $seatKey) {
                    $row = 'GA'; $num = 0;
                    if (str_contains($seatKey, '-')) {
                        list($row, $num) = explode('-', $seatKey);
                    } else { $row = $seatKey; }

                    Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'pricing_tier_id' => $tier->id,
                        'row' => $row,
                        'seat_number' => $num,
                        'price' => $tier->price,
                        'status' => 'pending',
                        'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                        'qr_code' => hash_hmac('sha256', $order->id . $seatKey, config('app.key'))
                    ]);
                }

                foreach ($seats as $seatKey) {
                    $row = 'GA'; $num = 0;
                    if (str_contains($seatKey, '-')) {
                        list($row, $num) = explode('-', $seatKey);
                    } else { $row = $seatKey; }

                    \App\Models\SeatHold::where('event_id', $event->id)
                        ->where('row', $row)
                        ->where('seat_number', $num)
                        ->delete();
                }

                if ($idempotencyKey) cache()->put('order_processed_' . $idempotencyKey, $order->id, 3600);

                $order->update(['status' => 'completed', 'payment_status' => 'paid', 'paid_at' => now()]);
                Ticket::where('order_id', $order->id)->update(['status' => 'valid']);

                if ($promoCodeId) {
                    PromoCode::where('id', $promoCodeId)->increment('used_count');
                    PromoCodeUsage::create([
                        'promo_code_id' => $promoCodeId,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'discount_amount' => $discount,
                        'used_at' => now()
                    ]);
                }

                return redirect()->route('order.success', $order->id);
            });
        } catch (\Exception $e) {
            return redirect()->route('event.show', $event->id)->with('error', $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['event.venue', 'tickets.pricingTier'])->findOrFail($orderId);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.order_success', compact('order'));
    }

    public function myTickets()
    {
        $tickets = Ticket::with(['event.venue', 'pricingTier', 'order'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.my_tickets', compact('tickets'));
    }

    public function myOrders()
    {
        $orders = Order::with(['event.venue', 'tickets'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.my_orders', compact('orders'));
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

        return view('user.my_schedule', compact('tickets'));
    }

    public function transfer(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);
        
        if ($ticket->status !== 'valid') {
            return redirect()->back()->with('error', 'Only valid tickets can be transferred.');
        }

        $recipient = \App\Models\User::where('email', $request->email)->first();

        if ($recipient->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot transfer a ticket to yourself.');
        }

        DB::transaction(function() use ($ticket, $recipient) {
            $ticket->update([
                'user_id' => $recipient->id,
                'qr_code' => hash('sha256', $ticket->id . $recipient->id . time())
            ]);

            DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'ticket_transfer',
                'description' => "Transferred ticket #{$ticket->ticket_number} to {$recipient->email}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return redirect()->back()->with('success', 'Ticket transferred successfully to ' . $request->email);
    }

    public function validatePromo(Request $request)
    {
        $code = $request->input('code');
        $promo = PromoCode::where('code', $code)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired promo code.']);
        }

        $eventId = $request->input('event_id');
        if ($promo->scope === 'event_specific' && $eventId && !in_array($eventId, $promo->applicable_events ?? [])) {
            return response()->json(['valid' => false, 'message' => 'This code is not valid for this event.']);
        }

        return response()->json([
            'valid' => true,
            'type' => $promo->type,
            'reward' => $promo->value,
            'message' => 'Promo code applied!'
        ]);
    }

    public function holdSeat(Request $request, $id)
    {
        $request->validate([
            'seat_key' => 'required|string'
        ]);

        $eventId = $id;
        list($row, $num) = explode('-', $request->seat_key);
        
        $taken = Ticket::where('event_id', $eventId)->where('row', $row)->where('seat_number', $num)->where('status', 'valid')->exists();
        $held = \App\Models\SeatHold::where('event_id', $eventId)->where('row', $row)->where('seat_number', $num)->active()->where('user_id', '!=', auth()->id())->exists();

        if ($taken || $held) {
            return response()->json(['success' => false, 'message' => 'Seat is no longer available.']);
        }

        \App\Models\SeatHold::updateOrCreate(
            ['event_id' => $eventId, 'row' => $row, 'seat_number' => $num],
            ['user_id' => auth()->id(), 'session_id' => session()->getId(), 'expires_at' => now()->addMinutes(10)]
        );

        return response()->json(['success' => true, 'expires_at' => now()->addMinutes(10)->toIso8601String()]);
    }

    public function releaseSeat(Request $request, $id)
    {
        $request->validate([
            'seat_key' => 'required|string'
        ]);

        list($row, $num) = explode('-', $request->seat_key);
        
        \App\Models\SeatHold::where('event_id', $id)
            ->where('row', $row)
            ->where('seat_number', $num)
            ->where(function($q) {
                $q->where('user_id', auth()->id())->orWhere('session_id', session()->getId());
            })
            ->delete();

        return response()->json(['success' => true]);
    }

    public function downloadTicket($id)
    {
        $ticket = Ticket::with(['event.venue', 'user', 'pricingTier'])->findOrFail($id);
        
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate QR Code using API
        $qrData = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($ticket->qr_code) . '" alt="QR Code" style="width:120px;height:120px;">';

        $event = $ticket->event;
        $venue = $event->venue;

        return view('pdf.ticket', compact('ticket', 'qrData'));
    }

    public function downloadOrderTickets($id)
    {
        $order = Order::with(['event.venue', 'user', 'tickets.pricingTier'])->findOrFail($id);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pdf.order_tickets', compact('order'));
    }

    public function downloadReceipt($id)
    {
        $order = Order::with(['event.venue', 'user', 'tickets.pricingTier'])->findOrFail($id);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pdf.receipt', compact('order'));
    }
}