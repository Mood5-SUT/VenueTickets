<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payout;
use App\Models\FailedPayment;
use App\Models\PlatformSetting;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function overview(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $period = $request->period ?? 'month';
        
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
        }
        
        $totalRevenue = Order::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $totalServiceFees = Order::where('created_at', '>=', $startDate)
            ->where('status', 'completed')
            ->sum('service_fee');
            
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        
        $pendingPayouts = Payout::where('status', 'pending')->sum('amount');
        
        $recentTransactions = Order::where('created_at', '>=', $startDate)
            ->with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        $revenueChartData = $this->getRevenueChartData($startDate);
        
        return view('admin.finance.overview', compact(
            'totalRevenue',
            'totalServiceFees',
            'totalOrders',
            'pendingPayouts',
            'recentTransactions',
            'revenueChartData',
            'period'
        ));
    }
    
    private function getRevenueChartData($startDate)
    {
        $labels = [];
        $revenue = [];
        $fees = [];
        
        $currentDate = clone $startDate;
        while ($currentDate <= Carbon::now()) {
            $labels[] = $currentDate->format('M d');
            
            $revenue[] = Order::whereDate('created_at', $currentDate)
                ->where('status', 'completed')
                ->sum('total_amount');
                
            $fees[] = Order::whereDate('created_at', $currentDate)
                ->where('status', 'completed')
                ->sum('service_fee');
                
            $currentDate->addDay();
        }
        
        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'fees' => $fees
        ];
    }
    
    public function payouts(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $query = Payout::with('organizer');
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->organizer_id) {
            $query->where('organizer_id', $request->organizer_id);
        }
        
        $payouts = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.finance.payouts', compact('payouts'));
    }
    
    public function processPayout($id)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $payout = Payout::findOrFail($id);
        
        if ($payout->status !== 'pending') {
            return redirect()->back()->with('error', 'Payout cannot be processed.');
        }
        
        // Simulate Stripe payout processing
        $payout->status = 'completed';
        $payout->stripe_payout_id = 'po_' . uniqid();
        $payout->processed_at = now();
        $payout->save();
        
        return redirect()->back()->with('success', 'Payout processed successfully.');
    }
    
    public function refunds(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $query = Order::whereIn('status', ['refunded', 'refund_pending'])
            ->with(['user', 'event']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        $refunds = $query->orderBy('updated_at', 'desc')->paginate(20);
        
        $totalRefunded = Order::where('status', 'refunded')->sum('total_amount');
        $pendingRefunds = Order::where('status', 'refund_pending')->sum('total_amount');
        
        return view('admin.finance.refunds', compact('refunds', 'totalRefunded', 'pendingRefunds'));
    }
    
    public function failedPayments(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        $query = FailedPayment::with(['user', 'order']);
        
        if ($request->provider) {
            $query->where('payment_provider', $request->provider);
        }
        
        if ($request->error_code) {
            $query->where('error_code', $request->error_code);
        }
        
        $failedPayments = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $totalFailed = FailedPayment::count();
        $totalAmount = FailedPayment::sum('amount');
        
        return view('admin.finance.failed-payments', compact('failedPayments', 'totalFailed', 'totalAmount'));
    }
    
    public function stripeBalance()
    {
        if(!auth()->user()->hasPermissionTo('view_finance')) {
            abort(403);
        }
        
        // Simulated Stripe balance
        $balance = [
            'available' => rand(10000, 100000) / 100,
            'pending' => rand(1000, 50000) / 100,
            'currency' => 'usd'
        ];
        
        return view('admin.finance.stripe-balance', compact('balance'));
    }
}