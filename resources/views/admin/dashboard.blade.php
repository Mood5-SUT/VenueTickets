@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-0 opacity-75">Sales Today</p>
                        <h3>${{ number_format($salesToday, 2) }}</h3>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-0 opacity-75">Active Events</p>
                        <h3>{{ $activeEvents }}</h3>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-0 opacity-75">Pending Refunds</p>
                        <h3>{{ $pendingRefunds }}</h3>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-0 opacity-75">Queue Health</p>
                        <h3>{{ ucfirst($queueHealth['status']) }}</h3>
                    </div>
                    <i class="bi bi-diagram-3" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Revenue Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        Total Users
                        <span class="badge bg-primary">{{ \App\Models\User::count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Total Events
                        <span class="badge bg-success">{{ \App\Models\Event::count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Orders Today
                        <span class="badge bg-info">{{ $recentOrders->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Orders</h5>
        <a href="{{ route('admin_orders_list') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No orders yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueChartData['labels'] ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueChartData['data'] ?? []) !!},
                borderColor: '#0d6efd',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush