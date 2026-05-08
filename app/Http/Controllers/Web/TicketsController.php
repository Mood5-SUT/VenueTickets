<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Mail\TicketEmail;
use Illuminate\Support\Facades\Mail;

class TicketsController extends Controller
{
public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $query = Ticket::with(['order.user', 'event', 'seat.zone', 'user']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(25);
        $events = Event::orderBy('event_date', 'desc')->get(['id', 'name', 'event_date']);
        
        $stats = [
            'total' => Ticket::count(),
            'active' => Ticket::where('status', 'active')->count(),
            'used' => Ticket::whereNotNull('checked_in_at')->count(),
            'voided' => Ticket::where('status', 'voided')->count(),
            'transferred' => Ticket::where('status', 'transferred')->count()
        ];
        
        return view('admin.tickets.list', compact('tickets', 'events', 'stats'));
    }
    
    public function view($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $ticket = Ticket::with([
            'order.user',
            'order.event',
            'event.venue',
            'seat.zone',
            'user',
            'checkedInBy',
            'transferredTo',
            'resaleListings',
            'scanLogs.scannedBy'
        ])->findOrFail($id);
        
        return view('admin.tickets.view', compact('ticket'));
    }
    
    public function void(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $this->validate($request, [
            'reason' => 'required|string|min:10|max:500'
        ]);
        
        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->status === 'voided') {
            return redirect()->back()->with('error', 'Ticket is already voided.');
        }
        
        if ($ticket->checked_in_at) {
            return redirect()->back()->with('error', 'Cannot void a ticket that has been used.');
        }
        
        $ticket->status = 'voided';
        $ticket->void_reason = $request->reason;
        $ticket->save();
        
        // Free up the seat
        if ($ticket->seat_id) {
            $ticket->seat()->update(['status' => 'available']);
        }
        
        // Update pricing tier sold count
        if ($ticket->pricing_tier_id) {
            $tier = $ticket->pricingTier;
            if ($tier && $tier->sold_count > 0) {
                $tier->decrement('sold_count');
            }
        }
        
        return redirect()->back()->with('success', 'Ticket voided successfully.');
    }
    
    public function resend(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $ticket = Ticket::with('order.user', 'event', 'seat')->findOrFail($id);
        
        if ($ticket->status === 'voided') {
            return redirect()->back()->with('error', 'Cannot resend voided ticket.');
        }
        
        try {
            Mail::to($ticket->user->email)->send(new TicketEmail($ticket));
            
            $ticket->email_sent = true;
            $ticket->email_sent_at = now();
            $ticket->save();
            
            return redirect()->back()->with('success', 'Ticket email sent successfully to ' . $ticket->user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
    
    public function bulkAction(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $this->validate($request, [
            'action' => 'required|in:void,resend,export',
            'ticket_ids' => 'required|array|min:1',
            'ticket_ids.*' => 'exists:tickets,id',
            'reason' => 'required_if:action,void|string|min:10|max:500'
        ]);
        
        $tickets = Ticket::whereIn('id', $request->ticket_ids)->get();
        
        switch ($request->action) {
            case 'void':
                $count = 0;
                foreach ($tickets as $ticket) {
                    if ($ticket->status !== 'voided' && !$ticket->checked_in_at) {
                        $ticket->status = 'voided';
                        $ticket->void_reason = $request->reason;
                        $ticket->save();
                        
                        if ($ticket->seat_id) {
                            $ticket->seat()->update(['status' => 'available']);
                        }
                        
                        $count++;
                    }
                }
                return redirect()->back()->with('success', $count . ' tickets voided successfully.');
                
            case 'resend':
                $count = 0;
                foreach ($tickets as $ticket) {
                    if ($ticket->status !== 'voided') {
                        try {
                            Mail::to($ticket->user->email)->send(new TicketEmail($ticket));
                            $ticket->email_sent = true;
                            $ticket->email_sent_at = now();
                            $ticket->save();
                            $count++;
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
                return redirect()->back()->with('success', $count . ' ticket emails sent.');
                
            case 'export':
                return $this->exportTickets($tickets);
        }
        
        return redirect()->back();
    }
    
    private function exportTickets($tickets)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tickets_export_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Ticket Number',
                'Event',
                'Buyer',
                'Email',
                'Section',
                'Row',
                'Seat',
                'Price',
                'Status',
                'Checked In',
                'Purchase Date'
            ]);
            
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->event->name ?? 'N/A',
                    $ticket->user->name ?? 'N/A',
                    $ticket->user->email ?? 'N/A',
                    $ticket->section ?? 'N/A',
                    $ticket->row ?? 'N/A',
                    $ticket->seat_number ?? 'N/A',
                    '$' . number_format($ticket->price, 2),
                    ucfirst($ticket->status),
                    $ticket->checked_in_at ? $ticket->checked_in_at->format('Y-m-d H:i:s') : 'Not checked in',
                    $ticket->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}