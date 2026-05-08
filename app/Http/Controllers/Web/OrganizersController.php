<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrganizerDetail;
use App\Models\Event;

class OrganizersController extends Controller
{
public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_organizers')) {
            abort(403);
        }
        
        $query = OrganizerDetail::with('user');
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $organizers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.organizers.list', compact('organizers'));
    }
    
    public function approve($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_organizers')) {
            abort(403);
        }
        
        $organizerDetail = OrganizerDetail::findOrFail($id);
        $organizerDetail->status = 'approved';
        $organizerDetail->approved_at = now();
        $organizerDetail->approved_by = auth()->id();
        $organizerDetail->save();
        
        // Assign organizer role
        $organizerDetail->user->assignRole('organizer');
        
        return redirect()->back()->with('success', 'Organizer approved successfully.');
    }
    
    public function suspend(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_organizers')) {
            abort(403);
        }
        
        $this->validate($request, [
            'reason' => 'required|string|min:10'
        ]);
        
        $organizerDetail = OrganizerDetail::findOrFail($id);
        $organizerDetail->status = 'suspended';
        $organizerDetail->suspension_reason = $request->reason;
        $organizerDetail->save();
        
        return redirect()->back()->with('success', 'Organizer suspended successfully.');
    }
    
    public function events($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_organizers')) {
            abort(403);
        }
        
        $organizerDetail = OrganizerDetail::with('user')->findOrFail($id);
        $events = Event::where('organizer_id', $organizerDetail->user_id)
            ->orderBy('event_date', 'desc')
            ->paginate(20);
        
        return view('admin.organizers.events', compact('organizerDetail', 'events'));
    }
    
    public function revenue($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_organizers')) {
            abort(403);
        }
        
        $organizerDetail = OrganizerDetail::with('user')->findOrFail($id);
        
        $totalRevenue = Event::where('organizer_id', $organizerDetail->user_id)
            ->whereHas('orders', function($q) {
                $q->where('status', 'completed');
            })
            ->withSum(['orders' => function($q) {
                $q->where('status', 'completed');
            }], 'total_amount')
            ->get()
            ->sum('orders_sum_total_amount');
        
        return view('admin.organizers.revenue', compact('organizerDetail', 'totalRevenue'));
    }
}