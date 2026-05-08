<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\SeatMap;
use App\Models\Zone;
use App\Models\Seat;

class VenuesController extends Controller
{
    public function list(Request $request)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $query = Venue::query();
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $venues = $query->orderBy('name')->paginate(20);
        
        return view('admin.venues.list', compact('venues'));
    }
    
    public function edit($id = null)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $venue = $id ? Venue::findOrFail($id) : null;
        
        return view('admin.venues.edit', compact('venue'));
    }
    
    public function save(Request $request, $id = null)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1'
        ]);
        
        $venue = $id ? Venue::findOrFail($id) : new Venue();
        $venue->fill($request->all());
        $venue->save();
        
        return redirect()->route('admin_venues_list')
            ->with('success', 'Venue saved successfully.');
    }
    
    public function seatMapsList()
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $seatMaps = SeatMap::with('venue')->paginate(20);
        
        return view('admin.seat-maps.list', compact('seatMaps'));
    }
    
    public function seatMapEdit($id = null)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $seatMap = $id ? SeatMap::with('zones')->findOrFail($id) : null;
        $venues = Venue::where('is_active', true)->get();
        
        return view('admin.seat-maps.edit', compact('seatMap', 'venues'));
    }
    
    public function seatMapSave(Request $request, $id = null)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:venues,id',
            'description' => 'nullable|string',
            'total_seats' => 'required|integer|min:1'
        ]);
        
        $seatMap = $id ? SeatMap::findOrFail($id) : new SeatMap();
        $seatMap->name = $request->name;
        $seatMap->venue_id = $request->venue_id;
        $seatMap->description = $request->description;
        $seatMap->total_seats = $request->total_seats;
        $seatMap->layout_data = $request->layout_data ?? ['zones' => []];
        $seatMap->is_active = true;
        $seatMap->save();
        
        return redirect()->route('admin_seat_maps_list')
            ->with('success', 'Seat map saved successfully.');
    }
    
    public function seatToggle(Request $request, $id)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $request->validate([
            'seat_ids' => 'required|array',
            'status' => 'required|in:available,locked'
        ]);
        
        Seat::whereIn('id', $request->seat_ids)
            ->where('seat_map_id', $id)
            ->update(['status' => $request->status]);
        
        return response()->json(['success' => true]);
    }
}