<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Venue;
use App\Models\SeatMap;
use App\Models\User;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $query = Event::with(['organizer', 'venue']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        
        return view('admin.events.list', compact('events'));
    }
    
    public function edit($id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $event = $id ? Event::findOrFail($id) : null;
        $venues = Venue::where('is_active', true)->get();
        $seatMaps = SeatMap::where('is_active', true)->get();
        $organizers = User::role('organizer')->get();
        
        return view('admin.events.edit', compact('event', 'venues', 'seatMaps', 'organizers'));
    }
    
    public function save(Request $request, $id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'organizer_id' => 'required|exists:users,id',
            'venue_id' => 'nullable|exists:venues,id',
            'seat_map_id' => 'nullable|exists:seat_maps,id',
            'event_date' => 'required|date',
            'status' => 'required|in:draft,published,cancelled',
            'event_type' => 'nullable|string|max:50'
        ]);
        
        $event = $id ? Event::findOrFail($id) : new Event();
        $event->fill($request->all());
        $event->save();
        
        return redirect()->route('admin_events_list')
            ->with('success', 'Event saved successfully.');
    }
    
    public function delete($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $event = Event::findOrFail($id);
        $event->delete();
        
        return redirect()->route('admin_events_list')
            ->with('success', 'Event deleted successfully.');
    }
    
    public function toggleStatus(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:draft,published,cancelled'
        ]);
        
        $event = Event::findOrFail($id);
        $event->status = $request->status;
        $event->save();
        
        return redirect()->back()->with('success', 'Event status updated.');
    }
    
    public function salesSummary($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $event = Event::with(['orders.user', 'tickets.user', 'pricingTiers', 'venue'])->findOrFail($id);
        
        $totalSales = $event->orders()->where('status', 'completed')->sum('total_amount');
        $totalTicketsSold = $event->tickets()->where('status', '!=', 'voided')->count();
        $totalCapacity = $event->seatMap ? $event->seatMap->total_seats : 0;
        
        return view('admin.events.sales', compact('event', 'totalSales', 'totalTicketsSold', 'totalCapacity'));
    }
}