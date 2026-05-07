<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Mail\TicketEmail;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $query = Order::with(['user', 'event', 'tickets']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.orders.list', compact('orders'));
    }
    
    public function view($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $order = Order::with(['user', 'event', 'tickets.seat', 'tickets.pricingTier'])->findOrFail($id);
        
        return view('admin.orders.view', compact('order'));
    }
    
    public function refund(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $this->validate($request, [
            'reason' => 'required|string|min:10'
        ]);
        
        $order = Order::findOrFail($id);
        
        if (!in_array($order->status, ['completed', 'pending'])) {
            return redirect()->back()->with('error', 'Order cannot be refunded.');
        }
        
        // Process refund through payment gateway
        $order->status = 'refund_pending';
        $order->refund_reason = $request->reason;
        $order->save();
        
        // Void all tickets
        $order->tickets()->update([
            'status' => 'voided',
            'void_reason' => 'Order refunded: ' . $request->reason
        ]);
        
        return redirect()->back()->with('success', 'Refund initiated successfully.');
    }
    
    public function ticketsList(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $query = Ticket::with(['order.user', 'event', 'seat']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }
        
        if ($request->search) {
            $query->where('ticket_number', 'like', '%' . $request->search . '%');
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.tickets.list', compact('tickets'));
    }
    
    public function voidTicket(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $this->validate($request, [
            'reason' => 'required|string|min:10'
        ]);
        
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'voided';
        $ticket->void_reason = $request->reason;
        $ticket->save();
        
        // Free up the seat
        if ($ticket->seat_id) {
            $ticket->seat()->update(['status' => 'available']);
        }
        
        return redirect()->back()->with('success', 'Ticket voided successfully.');
    }
    
    public function resendTicket($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_orders')) {
            abort(403);
        }
        
        $ticket = Ticket::with('order.user')->findOrFail($id);
        
        Mail::to($ticket->order->user->email)->send(new TicketEmail($ticket));
        
        $ticket->email_sent = true;
        $ticket->email_sent_at = now();
        $ticket->save();
        
        return redirect()->back()->with('success', 'Ticket email resent successfully.');
    }
}