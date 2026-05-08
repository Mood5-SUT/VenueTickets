<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\PricingTier;
use Carbon\Carbon;

class PricingTiersController extends Controller
{
public function list($eventId)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $event = Event::with(['pricingTiers' => function($q) {
            $q->orderBy('starts_at', 'asc');
        }])->findOrFail($eventId);
        
        return view('admin.pricing.list', compact('event'));
    }
    
    public function save(Request $request, $eventId)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'quantity' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'description' => 'nullable|string|max:500',
            'max_per_order' => 'nullable|integer|min:1',
            'min_per_order' => 'required|integer|min:1|max:10'
        ]);
        
        $event = Event::findOrFail($eventId);
        
        $tier = new PricingTier();
        $tier->fill($request->all());
        $tier->event_id = $event->id;
        $tier->is_active = true;
        $tier->sold_count = 0;
        $tier->save();
        
        return redirect()->back()->with('success', 'Pricing tier "' . $tier->name . '" created successfully.');
    }
    
    public function edit(Request $request, $eventId, $tierId)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'quantity' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'description' => 'nullable|string|max:500',
            'max_per_order' => 'nullable|integer|min:1',
            'min_per_order' => 'required|integer|min:1|max:10'
        ]);
        
        $tier = PricingTier::where('event_id', $eventId)->findOrFail($tierId);
        $tier->update($request->all());
        
        return redirect()->back()->with('success', 'Pricing tier updated successfully.');
    }
    
    public function toggle($eventId, $tierId)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $tier = PricingTier::where('event_id', $eventId)->findOrFail($tierId);
        $tier->is_active = !$tier->is_active;
        $tier->save();
        
        $status = $tier->is_active ? 'activated' : 'paused';
        
        return redirect()->back()->with('success', "Pricing tier {$status} successfully.");
    }
    
    public function delete($eventId, $tierId)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $tier = PricingTier::where('event_id', $eventId)->findOrFail($tierId);
        
        if ($tier->sold_count > 0) {
            return redirect()->back()->with('error', 'Cannot delete tier with existing ticket sales.');
        }
        
        $tier->delete();
        
        return redirect()->back()->with('success', 'Pricing tier deleted successfully.');
    }
}