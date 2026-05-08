<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use PDF;

class AnalyticsController extends Controller
{
public function index(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $period = $request->period ?? '30days';
        
        switch ($period) {
            case '7days':
                $startDate = Carbon::now()->subDays(7);
                break;
            case '30days':
                $startDate = Carbon::now()->subDays(30);
                break;
            case '90days':
                $startDate = Carbon::now()->subDays(90);
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                break;
            default:
                $startDate = Carbon::now()->subDays(30);
        }
        
        $revenueData = $this->getRevenueData($startDate);
        $salesData = $this->getSalesData($startDate);
        $topEvents = $this->getTopEvents($startDate);
        $salesFunnel = $this->getSalesFunnel($startDate);
        $dailyStats = $this->getDailyStats($startDate);
        
        return view('admin.analytics.index', compact(
            'revenueData',
            'salesData',
            'topEvents',
            'salesFunnel',
            'dailyStats',
            'period'
        ));
    }
    
    private function getRevenueData($startDate)
    {
        $labels = [];
        $data = [];
        
        $currentDate = clone $startDate;
        while ($currentDate <= Carbon::now()) {
            $labels[] = $currentDate->format('M d');
            $data[] = Order::whereDate('created_at', $currentDate)
                ->where('status', 'completed')
                ->sum('total_amount');
            $currentDate->addDay();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getSalesData($startDate)
    {
        $labels = [];
        $data = [];
        
        $currentDate = clone $startDate;
        while ($currentDate <= Carbon::now()) {
            $labels[] = $currentDate->format('M d');
            $data[] = Ticket::whereDate('created_at', $currentDate)
                ->where('status', '!=', 'voided')
                ->count();
            $currentDate->addDay();
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getTopEvents($startDate)
    {
        return Event::whereHas('orders', function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate)
                  ->where('status', 'completed');
            })
            ->withCount(['orders as total_orders' => function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate)
                  ->where('status', 'completed');
            }])
            ->withSum(['orders as total_revenue' => function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate)
                  ->where('status', 'completed');
            }], 'total_amount')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }
    
    private function getSalesFunnel($startDate)
    {
        $totalVisitors = Order::where('created_at', '>=', $startDate)->count() * rand(3, 6);
        $startedCheckout = Order::where('created_at', '>=', $startDate)->count();
        $completedOrders = Order::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->count();
        $ticketsSold = Ticket::where('created_at', '>=', $startDate)
            ->where('status', '!=', 'voided')
            ->count();
        
        return [
            'visitors' => $totalVisitors,
            'checkout_started' => $startedCheckout,
            'orders_completed' => $completedOrders,
            'tickets_sold' => $ticketsSold,
            'conversion_rate' => $totalVisitors > 0 
                ? round(($completedOrders / $totalVisitors) * 100, 2) 
                : 0
        ];
    }
    
    private function getDailyStats($startDate)
    {
        return [
            'total_revenue' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'total_orders' => Order::where('created_at', '>=', $startDate)->count(),
            'total_tickets' => Ticket::where('created_at', '>=', $startDate)
                ->where('status', '!=', 'voided')
                ->count(),
            'avg_order_value' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'completed')
                ->avg('total_amount') ?? 0,
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'refund_rate' => Order::where('created_at', '>=', $startDate)->count() > 0
                ? round((Order::where('created_at', '>=', $startDate)
                    ->whereIn('status', ['refunded', 'refund_pending'])
                    ->count() / Order::where('created_at', '>=', $startDate)->count()) * 100, 2)
                : 0
        ];
    }
    
    public function export(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $this->validate($request, [
            'format' => 'required|in:csv,pdf',
            'period' => 'required|in:7days,30days,90days,year'
        ]);
        
        switch ($request->period) {
            case '7days':
                $startDate = Carbon::now()->subDays(7);
                break;
            case '30days':
                $startDate = Carbon::now()->subDays(30);
                break;
            case '90days':
                $startDate = Carbon::now()->subDays(90);
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                break;
            default:
                $startDate = Carbon::now()->subDays(30);
        }
        
        if ($request->format === 'csv') {
            return $this->exportCSV($startDate);
        }
        
        return $this->exportPDF($startDate);
    }
    
    private function exportCSV($startDate)
    {
        $topEvents = $this->getTopEvents($startDate);
        $dailyStats = $this->getDailyStats($startDate);
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="analytics_report_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($topEvents, $dailyStats) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Analytics Report - Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, ['']);
            fputcsv($file, ['Key Metrics']);
            fputcsv($file, ['Total Revenue', '$' . number_format($dailyStats['total_revenue'], 2)]);
            fputcsv($file, ['Total Orders', $dailyStats['total_orders']]);
            fputcsv($file, ['Total Tickets Sold', $dailyStats['total_tickets']]);
            fputcsv($file, ['Average Order Value', '$' . number_format($dailyStats['avg_order_value'], 2)]);
            fputcsv($file, ['New Users', $dailyStats['new_users']]);
            fputcsv($file, ['Refund Rate', $dailyStats['refund_rate'] . '%']);
            fputcsv($file, ['']);
            fputcsv($file, ['Top Events']);
            fputcsv($file, ['Event Name', 'Orders', 'Revenue']);
            
            foreach ($topEvents as $event) {
                fputcsv($file, [
                    $event->name,
                    $event->total_orders,
                    '$' . number_format($event->total_revenue, 2)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportPDF($startDate)
    {
        $topEvents = $this->getTopEvents($startDate);
        $dailyStats = $this->getDailyStats($startDate);
        
        $pdf = PDF::loadView('admin.analytics.pdf', compact('topEvents', 'dailyStats', 'startDate'));
        
        return $pdf->download('analytics_report_' . date('Y-m-d') . '.pdf');
    }
}