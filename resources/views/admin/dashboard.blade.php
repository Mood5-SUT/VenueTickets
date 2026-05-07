{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard</h1>
        <div>
            <span class="text-muted">{{ Carbon\Carbon::now()->format('l, F d, Y') }}</span>
        </div>
    </div>
    
    <!-- KPI Widgets -->
    <div class="row g-3 mb-4">
        <!-- Sales Today -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Sales Today</p>
                            <h3 class="mb-0">${{ number_format($salesToday, 2) }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-cash text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 12.5%
                        </small>
                        <small class="text-muted ms-1">vs yesterday</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Events -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Active Events</p>
                            <h3 class="mb-0">{{ $activeEvents }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-calendar-check text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin_events_list') }}" class="small">View all events</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Refunds -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Pending Refunds</p>
                            <h3 class="mb-0">{{ $pendingRefunds }}</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin_orders_list', ['status' => 'refund_pending']) }}" class="small">Process refunds</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Queue Health -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Queue Health</p>
                            <h3 class="mb-0">
                                <span class="badge bg-{{ $queueHealth['status'] === 'healthy' ? 'success' : 'danger' }}">
                                    {{ ucfirst($queueHealth['status']) }}
                                </span>
                            </h3>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-diagram-3 text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            Pending: {{ $queueHealth['pending_jobs'] }} | Failed: {{ $queueHealth['failed_jobs'] }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Revenue Overview (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Total Users
                            <span class="badge bg-primary rounded-pill">{{ \App\Models\User::count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Total Events
                            <span class="badge bg-success rounded-pill">{{ \App\Models\Event::count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Orders Today
                            <span class="badge bg-info rounded-pill">{{ $recentOrders->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Resale Listings
                            <span class="badge bg-warning rounded-pill">{{ rand(10, 100) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Orders</h5>
            <a href="{{ route('admin_orders_list') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Event</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin_orders_view', $order->id) }}">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>{{ $order->event->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueChartData['labels'] ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueChartData['data'] ?? []) !!},
                borderColor: '#0d6efd',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush