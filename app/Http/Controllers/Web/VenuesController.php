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
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function list()
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $venues = Venue::withCount('events')->paginate(20);
        return view('admin.venues.list', compact('venues'));
    }
    
    public function edit($id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $venue = $id ? Venue::findOrFail($id) : null;
        return view('admin.venues.edit', compact('venue'));
    }
    
    public function save(Request $request, $id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'capacity' => 'required|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $venue = $id ? Venue::findOrFail($id) : new Venue();
        $venue->fill($request->all());
        $venue->save();
        
        return redirect()->route('admin_venues_edit', $venue->id)
            ->with('success', 'Venue saved successfully.');
    }
    
    // Seat Maps Methods
    public function seatMapsList()
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $seatMaps = SeatMap::with(['venue', 'zones'])->paginate(20);
        return view('admin.seat-maps.list', compact('seatMaps'));
    }
    
    public function seatMapEdit($id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $seatMap = $id ? SeatMap::with(['zones', 'seats'])->findOrFail($id) : null;
        $venues = Venue::where('is_active', true)->get();
        
        return view('admin.seat-maps.edit', compact('seatMap', 'venues'));
    }
    
    public function seatMapSave(Request $request, $id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:venues,id',
            'description' => 'nullable|string',
            'layout_data' => 'required|json',
            'zones' => 'required|array',
            'zones.*.name' => 'required|string',
            'zones.*.color' => 'required|string',
            'zones.*.default_price' => 'required|numeric|min:0',
            'zones.*.capacity' => 'required|integer|min:1',
            'zones.*.rows' => 'required|integer|min:1',
            'zones.*.columns' => 'required|integer|min:1'
        ]);
        
        $seatMap = $id ? SeatMap::findOrFail($id) : new SeatMap();
        $seatMap->fill($request->only(['name', 'venue_id', 'description']));
        $seatMap->layout_data = json_decode($request->layout_data, true);
        $seatMap->total_seats = collect($request->zones)->sum('capacity');
        $seatMap->save();
        
        // Sync zones
        $existingZoneIds = $seatMap->zones()->pluck('id')->toArray();
        $updatedZoneIds = [];
        
        foreach ($request->zones as $zoneData) {
            $zone = isset($zoneData['id']) ? Zone::find($zoneData['id']) : new Zone();
            $zone->fill($zoneData);
            $zone->seat_map_id = $seatMap->id;
            $zone->save();
            $updatedZoneIds[] = $zone->id;
            
            // Generate seats for this zone if new
            if (!isset($zoneData['id'])) {
                $this->generateSeatsForZone($zone);
            }
        }
        
        // Delete removed zones
        $zonesToDelete = array_diff($existingZoneIds, $updatedZoneIds);
        Zone::whereIn('id', $zonesToDelete)->delete();
        
        return redirect()->route('admin_seat_maps_edit', $seatMap->id)
            ->with('success', 'Seat map saved successfully.');
    }
    
    private function generateSeatsForZone(Zone $zone)
    {
        $seats = [];
        for ($row = 1; $row <= $zone->rows; $row++) {
            $rowLabel = chr(64 + $row); // A, B, C, etc.
            for ($col = 1; $col <= $zone->columns; $col++) {
                $seatNumber = $rowLabel . $col;
                $seats[] = [
                    'zone_id' => $zone->id,
                    'seat_map_id' => $zone->seat_map_id,
                    'seat_number' => $seatNumber,
                    'row_label' => $rowLabel,
                    'row_number' => $row,
                    'column_number' => $col,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        Seat::insert($seats);
    }
    
    public function seatToggle(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_venues')) {
            abort(403);
        }
        
        $this->validate($request, [
            'seat_ids' => 'required|array',
            'status' => 'required|in:available,locked'
        ]);
        
        Seat::whereIn('id', $request->seat_ids)
            ->where('seat_map_id', $id)
            ->update(['status' => $request->status]);
        
        return response()->json(['success' => true]);
    }
}