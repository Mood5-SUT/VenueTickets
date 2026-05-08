@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>${{ number_format($dailyStats['total_revenue'] ?? 0, 2) }}</h3>
                <small>Total Revenue</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $dailyStats['total_orders'] ?? 0 }}</h3>
                <small>Total Orders</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>${{ number_format($dailyStats['avg_order_value'] ?? 0, 2) }}</h3>
                <small>Avg Order Value</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $dailyStats['conversion_rate'] ?? 0 }}%</h3>
                <small>Conversion Rate</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Revenue Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueTrend" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ticket Sales</h5>
            </div>
            <div class="card-body">
                <canvas id="salesTrend" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Top Events</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topEvents as $index => $event)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->total_orders }}</td>
                        <td>${{ number_format($event->total_revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No data available</td>
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
    new Chart(document.getElementById('revenueTrend').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData['labels'] ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($revenueData['data'] ?? []) !!},
                borderColor: '#0d6efd',
                tension: 0.3
            }]
        }
    });
    
    new Chart(document.getElementById('salesTrend').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesData['labels'] ?? []) !!},
            datasets: [{
                label: 'Tickets Sold',
                data: {!! json_encode($salesData['data'] ?? []) !!},
                backgroundColor: '#198754'
            }]
        }
    });
</script>
@endpush