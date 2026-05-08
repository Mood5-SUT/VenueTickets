<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\ScanLog;
use App\Models\User;
use Carbon\Carbon;

class ScanController extends Controller
{
public function scanDashboard($eventId)
    {
        if(!auth()->user()->hasPermissionTo('scan_tickets')) {
            abort(403);
        }
        
        $event = Event::with(['venue', 'tickets'])->findOrFail($eventId);
        
        $stats = [
            'total_tickets' => $event->tickets()->where('status', '!=', 'voided')->count(),
            'checked_in' => $event->tickets()->whereNotNull('checked_in_at')->count(),
            'remaining' => $event->tickets()->whereNull('checked_in_at')->where('status', 'active')->count(),
            'scan_rate' => $event->tickets()->where('status', '!=', 'voided')->count() > 0
                ? round(($event->tickets()->whereNotNull('checked_in_at')->count() / 
                    $event->tickets()->where('status', '!=', 'voided')->count()) * 100, 2)
                : 0
        ];
        
        $recentScans = ScanLog::with(['ticket', 'scannedBy'])
            ->where('event_id', $eventId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        $scanStaff = User::role('staff')
            ->whereHas('permissions', function($q) {
                $q->where('name', 'scan_tickets');
            })
            ->get();
        
        return view('admin.scan.dashboard', compact('event', 'stats', 'recentScans', 'scanStaff'));
    }
    
    public function verify(Request $request, $eventId)
    {
        if(!auth()->user()->hasPermissionTo('scan_tickets')) {
            abort(403);
        }
        
        $this->validate($request, [
            'ticket_code' => 'required|string'
        ]);
        
        $ticket = Ticket::where('event_id', $eventId)
            ->where(function($q) use ($request) {
                $q->where('qr_code', $request->ticket_code)
                  ->orWhere('ticket_number', $request->ticket_code);
            })
            ->with(['user', 'seat'])
            ->first();
        
        if (!$ticket) {
            $this->logScan(null, $eventId, 'invalid', 'Ticket not found');
            return response()->json([
                'status' => 'invalid',
                'message' => 'Invalid ticket. Not found in system.'
            ]);
        }
        
        if ($ticket->status === 'voided') {
            $this->logScan($ticket->id, $eventId, 'invalid', 'Ticket has been voided');
            return response()->json([
                'status' => 'invalid',
                'message' => 'This ticket has been voided.',
                'ticket' => $ticket
            ]);
        }
        
        if ($ticket->checked_in_at) {
            $this->logScan($ticket->id, $eventId, 'already_used', 'Ticket already checked in');
            return response()->json([
                'status' => 'already_used',
                'message' => 'This ticket was already used at ' . $ticket->checked_in_at->format('H:i:s'),
                'ticket' => $ticket
            ]);
        }
        
        // Valid ticket - check in
        $ticket->checked_in_at = now();
        $ticket->checked_in_by = auth()->id();
        $ticket->save();
        
        $this->logScan($ticket->id, $eventId, 'valid', 'Ticket checked in successfully');
        
        return response()->json([
            'status' => 'valid',
            'message' => 'Valid ticket! Welcome!',
            'ticket' => $ticket
        ]);
    }
    
    private function logScan($ticketId, $eventId, $result, $notes = null)
    {
        return ScanLog::create([
            'ticket_id' => $ticketId,
            'event_id' => $eventId,
            'scanned_by' => auth()->id(),
            'scan_result' => $result,
            'device_info' => request()->userAgent(),
            'ip_address' => request()->ip(),
            'notes' => $notes
        ]);
    }
    
    public function manualCheckin(Request $request, $eventId)
    {
        if(!auth()->user()->hasPermissionTo('scan_tickets')) {
            abort(403);
        }
        
        $this->validate($request, [
            'ticket_id' => 'required|exists:tickets,id',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $ticket = Ticket::where('event_id', $eventId)->findOrFail($request->ticket_id);
        
        if ($ticket->checked_in_at) {
            return redirect()->back()->with('error', 'Ticket already checked in.');
        }
        
        $ticket->checked_in_at = now();
        $ticket->checked_in_by = auth()->id();
        $ticket->save();
        
        $this->logScan($ticket->id, $eventId, 'manual_override', $request->notes);
        
        return redirect()->back()->with('success', 'Manual check-in successful.');
    }
    
    public function checkinLog($eventId, Request $request)
    {
        if(!auth()->user()->hasPermissionTo('scan_tickets')) {
            abort(403);
        }
        
        $event = Event::findOrFail($eventId);
        
        $query = ScanLog::with(['ticket.user', 'scannedBy'])
            ->where('event_id', $eventId);
        
        if ($request->result) {
            $query->where('scan_result', $request->result);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(30);
        
        return view('admin.scan.log', compact('event', 'logs'));
    }
    
    public function assignStaff(Request $request, $eventId)
    {
        if(!auth()->user()->hasPermissionTo('manage_events')) {
            abort(403);
        }
        
        $this->validate($request, [
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:users,id'
        ]);
        
        // Store staff assignment in event metadata
        $event = Event::findOrFail($eventId);
        $metadata = $event->metadata ?? [];
        $metadata['scan_staff'] = $request->staff_ids;
        $event->metadata = $metadata;
        $event->save();
        
        return redirect()->back()->with('success', 'Scan staff assigned successfully.');
    }
}