@extends('layouts.admin')

@section('title', 'Finance Overview')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <p class="mb-0 opacity-75">Total Revenue</p>
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <p class="mb-0 opacity-75">Service Fees</p>
                <h3>${{ number_format($totalServiceFees, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <p class="mb-0 opacity-75">Total Orders</p>
                <h3>{{ $totalOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <p class="mb-0 opacity-75">Pending Payouts</p>
                <h3>${{ number_format($pendingPayouts, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Revenue Chart</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin_finance_payouts') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-cash me-2"></i> Payout Queue
                    </a>
                    <a href="{{ route('admin_finance_refunds') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Refund Log
                    </a>
                    <a href="{{ route('admin_finance_failed_payments') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-exclamation-triangle me-2"></i> Failed Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Recent Transactions</h5>
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
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->event->name ?? 'N/A' }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No transactions</td>
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
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueChartData['labels'] ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueChartData['revenue'] ?? []) !!},
                backgroundColor: '#198754'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush