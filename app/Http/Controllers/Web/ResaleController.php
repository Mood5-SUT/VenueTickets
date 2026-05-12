<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResaleListing;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;

class ResaleController extends Controller
{
public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $query = ResaleListing::with(['ticket.event', 'seller', 'buyer']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->flagged) {
            $query->where('is_flagged', true);
        }
        
        if ($request->exceeds_cap) {
            $query->where('exceeds_price_cap', true);
        }
        
        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }
        
        if ($request->search) {
            $query->whereHas('ticket', function($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%');
            });
        }
        
        $listings = $query->orderBy('created_at', 'desc')->paginate(20);
        $events = Event::where('resale_enabled', true)->get();
        
        $stats = [
            'total_active' => ResaleListing::where('status', 'active')->count(),
            'total_flagged' => ResaleListing::where('is_flagged', true)->count(),
            'above_cap' => ResaleListing::where('exceeds_price_cap', true)->count(),
            'total_volume' => ResaleListing::where('status', 'sold')->sum('asking_price')
        ];
        
        return view('admin.resale.list', compact('listings', 'events', 'stats'));
    }
    
    public function view($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $listing = ResaleListing::with([
            'ticket.event',
            'ticket.seat',
            'seller',
            'buyer'
        ])->findOrFail($id);
        
        return view('admin.resale.view', compact('listing'));
    }
    
    public function remove(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $this->validate($request, [
            'reason' => 'required|string|min:10|max:500'
        ]);
        
        $listing = ResaleListing::findOrFail($id);
        $listing->status = 'removed';
        $listing->is_flagged = true;
        $listing->flag_reason = $request->reason;
        $listing->save();
        
        // Reactivate the ticket
        $listing->ticket->update([
            'status' => 'active'
        ]);
        
        return redirect()->route('admin_resale_list')
            ->with('success', 'Listing removed successfully.');
    }
    
    public function setPriceCap(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $this->validate($request, [
            'event_id' => 'required|exists:events,id',
            'price_cap_percentage' => 'required|numeric|min:0|max:1000'
        ]);
        
        $event = Event::findOrFail($request->event_id);
        $event->resale_price_cap_percentage = $request->price_cap_percentage;
        $event->save();
        
        // Flag listings that exceed the new cap
        $listings = ResaleListing::where('event_id', $event->id)
            ->where('status', 'active')
            ->get();
        
        foreach ($listings as $listing) {
            $maxPrice = $listing->original_price * (1 + ($event->resale_price_cap_percentage / 100));
            $listing->exceeds_price_cap = $listing->asking_price > $maxPrice;
            $listing->price_cap_percentage = $event->resale_price_cap_percentage;
            $listing->save();
        }
        
        return redirect()->back()->with('success', 'Price cap set successfully.');
    }
    
    public function transactions(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $query = ResaleListing::where('status', 'sold')
            ->with(['ticket.event', 'seller', 'buyer']);
        
        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }
        
        if ($request->date_from) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }
        
        $transactions = $query->orderBy('sold_at', 'desc')->paginate(20);
        
        $totalVolume = ResaleListing::where('status', 'sold')->sum('asking_price');
        $avgMarkup = ResaleListing::where('status', 'sold')
            ->get()
            ->avg(function($listing) {
                // Mock markup logic if method missing
                return 0;
            });
        
        return view('admin.resale.transactions', compact('transactions', 'totalVolume', 'avgMarkup'));
    }

    // --- PUBLIC RESALE METHODS ---

    public function browse(Request $request)
    {
        $query = ResaleListing::with(['ticket.event', 'ticket.pricingTier'])
            ->where('status', 'active');

        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        $listings = $query->orderBy('asking_price', 'asc')->paginate(12);
        $events = Event::where('resale_enabled', true)->get();

        return view('resale.browse', compact('listings', 'events'));
    }

    public function storeListing(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);
        
        if ($ticket->status !== 'valid') {
            return redirect()->back()->with('error', 'Only valid tickets can be listed for resale.');
        }

        $event = $ticket->event;
        if (!$event->resale_enabled) {
            return redirect()->back()->with('error', 'Resale is not enabled for this event.');
        }

        $request->validate([
            'asking_price' => 'required|numeric|min:1'
        ]);

        // Check price cap
        $maxPrice = $ticket->price * (1 + (($event->resale_price_cap_percentage ?? 100) / 100));
        if ($request->asking_price > $maxPrice) {
            return redirect()->back()->with('error', 'Price exceeds the event price cap ($' . number_format($maxPrice, 2) . ').');
        }

        DB::transaction(function() use ($ticket, $request, $event) {
            ResaleListing::create([
                'ticket_id' => $ticket->id,
                'seller_id' => auth()->id(),
                'event_id' => $event->id,
                'original_price' => $ticket->price,
                'asking_price' => $request->asking_price,
                'status' => 'active'
            ]);

            $ticket->update(['status' => 'for_resale']);
        });

        return redirect()->back()->with('success', 'Ticket listed for resale successfully.');
    }

    public function purchaseListing(Request $request, $id)
    {
        $listing = ResaleListing::with(['ticket.event', 'seller'])->where('status', 'active')->findOrFail($id);
        
        if ($listing->seller_id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot buy your own ticket.');
        }

        DB::transaction(function() use ($listing) {
            $newOwner = auth()->user();
            $ticket = $listing->ticket;

            // 1. Mark listing as sold
            $listing->update([
                'status' => 'sold',
                'buyer_id' => $newOwner->id,
                'sold_at' => now()
            ]);

            // 2. Transfer Ticket Ownership
            $ticket->update([
                'user_id' => $newOwner->id,
                'status' => 'valid',
                'qr_code' => hash_hmac('sha256', $ticket->id . $newOwner->id, config('app.key'))
            ]);

            // 3. Log Transfer
            DB::table('audit_logs')->insert([
                'user_id' => $newOwner->id,
                'action' => 'resale_purchase',
                'description' => "Purchased ticket #{$ticket->ticket_number} from resale",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return redirect()->route('my_tickets')->with('success', 'Resale ticket purchased successfully!');
    }

    public function cancelListing(Request $request, $id)
    {
        $listing = ResaleListing::where('seller_id', auth()->id())
            ->where('status', 'active')
            ->findOrFail($id);

        DB::transaction(function() use ($listing) {
            $listing->update(['status' => 'cancelled']);
            $listing->ticket->update(['status' => 'valid']);
        });

        return redirect()->back()->with('success', 'Resale listing cancelled.');
    }
}