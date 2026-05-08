@extends('layouts.admin')

@section('title', 'Event Sales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $event->name }} - Sales Summary</h4>
        <p class="text-muted mb-0">{{ $event->event_date->format('M d, Y') }} | {{ $event->venue->name ?? 'N/A' }}</p>
    </div>
    <div>
        <a href="{{ route('admin_events_list') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Events
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <h3>${{ number_format($totalSales, 2) }}</h3>
                <small>Total Sales</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <h3>{{ $totalTicketsSold }}</h3>
                <small>Tickets Sold</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <h3>{{ $totalCapacity }}</h3>
                <small>Total Capacity</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body text-center">
                <h3>{{ $totalCapacity > 0 ? round(($totalTicketsSold / $totalCapacity) * 100, 1) : 0 }}%</h3>
                <small>Fill Rate</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales by Pricing Tier</h5>
            </div>
            <div class="card-body">
                @if($event->pricingTiers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tier</th>
                                    <th>Price</th>
                                    <th>Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->pricingTiers as $tier)
                                <tr>
                                    <td>{{ $tier->name }}</td>
                                    <td>${{ number_format($tier->price, 2) }}</td>
                                    <td>{{ $tier->sold_count }}/{{ $tier->quantity ?? '∞' }}</td>
                                    <td>${{ number_format($tier->sold_count * $tier->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No pricing tiers configured</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($event->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->orders->take(10) as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No orders yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Tickets</h5>
        <span class="badge bg-primary">{{ $event->tickets->count() }} tickets</span>
    </div>
    <div class="card-body">
        @if($event->tickets->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Buyer</th>
                            <th>Seat</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->tickets->take(20) as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}</td>
                            <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->seat_number ?? 'N/A' }}</td>
                            <td>${{ number_format($ticket->price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->status === 'active' ? 'success' : ($ticket->status === 'used' ? 'primary' : 'danger') }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center py-3">No tickets sold yet</p>
        @endif
    </div>
</div>
@endsection