<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('access_admin')) {
            abort(403);
        }
        
        $salesToday = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $activeEvents = Event::where('status', 'published')
            ->where('event_date', '>=', Carbon::now())
            ->count();
            
        $pendingRefunds = Order::where('status', 'refund_pending')->count();
        
        $queueHealth = [
            'pending_jobs' => rand(0, 50),
            'failed_jobs' => rand(0, 5),
            'status' => 'healthy'
        ];
        
        $recentOrders = Order::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $revenueChartData = $this->getRevenueChartData();
        
        return view('admin.dashboard', compact(
            'salesToday',
            'activeEvents',
            'pendingRefunds',
            'queueHealth',
            'recentOrders',
            'revenueChartData'
        ));
    }
    
    private function getRevenueChartData()
    {
        $labels = [];
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    public function search(Request $request)
    {
        if(!auth()->user() || !auth()->user()->hasPermissionTo('access_admin')) {
            abort(403);
        }
        
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->query;
        
        $events = Event::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'event_date']);
            
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'email']);
            
        $orders = Order::where('order_number', 'like', "%{$query}%")
            ->with('user:id,name')
            ->limit(5)
            ->get(['id', 'order_number', 'user_id', 'total_amount']);
        
        return response()->json([
            'events' => $events,
            'users' => $users,
            'orders' => $orders
        ]);
    }
    
    public function themeToggle(Request $request)
    {
        session(['theme' => $request->theme]);
        return response()->json(['success' => true]);
    }
}