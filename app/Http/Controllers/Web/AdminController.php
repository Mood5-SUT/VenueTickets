<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function dashboard()
    {
        if(!auth()->user()->hasPermissionTo('access_admin')) {
            abort(403);
        }
        
        // KPI Widgets Data
        $salesToday = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $activeEvents = Event::where('status', 'published')
            ->where('event_date', '>=', Carbon::now())
            ->count();
            
        $pendingRefunds = Order::where('status', 'refund_pending')->count();
        
        $queueHealth = $this->getQueueHealth();
        
        // Recent orders for dashboard
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'salesToday',
            'activeEvents',
            'pendingRefunds',
            'queueHealth',
            'recentOrders'
        ));
    }
    
    public function search(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('access_admin')) {
            abort(403);
        }
        
        $this->validate($request, [
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->query;
        
        // Search across multiple models
        $events = Event::where('name', 'like', "%{$query}%")
            ->orWhere('venue', 'like', "%{$query}%")
            ->limit(5)
            ->get();
            
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get();
            
        $orders = Order::where('order_number', 'like', "%{$query}%")
            ->with('user')
            ->limit(5)
            ->get();
        
        return response()->json([
            'events' => $events,
            'users' => $users,
            'orders' => $orders
        ]);
    }
    
    private function getQueueHealth()
    {
        // Simulated queue health metrics
        return [
            'pending_jobs' => rand(0, 50),
            'failed_jobs' => rand(0, 5),
            'status' => 'healthy'
        ];
    }
}