@extends('layouts.admin')

@section('title', 'Refund Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Refund Log</h4>
    <a href="{{ route('admin_finance_index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Finance
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>${{ number_format($pendingRefunds, 2) }}</h3>
                <small>Pending Refunds</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <h3>${{ number_format($totalRefunded, 2) }}</h3>
                <small>Total Refunded</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="refund_pending" {{ request('status') == 'refund_pending' ? 'selected' : '' }}>Pending</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Order number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Event</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refunds as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->event->name ?? 'N/A' }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td><small>{{ Str::limit($order->refund_reason, 30) }}</small></td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'refunded' ? 'success' : 'warning' }}">
                                {{ str_replace('_', ' ', ucfirst($order->status)) }}
                            </span>
                        </td>
                        <td>{{ $order->updated_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No refunds found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $refunds->links() }}
    </div>
</div>
@endsection