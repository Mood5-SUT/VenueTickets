@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }} fs-6">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Customer Information</h6>
                <p><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6>Order Information</h6>
                <p><strong>Event:</strong> {{ $order->event->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p><strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}</p>
                <p><strong>Fees:</strong> ${{ number_format($order->service_fee, 2) }}</p>
                <p><strong>Total:</strong> <strong>${{ number_format($order->total_amount, 2) }}</strong></p>
            </div>
        </div>

        <h6>Tickets</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>Section</th>
                        <th>Row</th>
                        <th>Seat</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->section ?? 'N/A' }}</td>
                        <td>{{ $ticket->row ?? 'N/A' }}</td>
                        <td>{{ $ticket->seat_number ?? 'N/A' }}</td>
                        <td>${{ number_format($ticket->price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $ticket->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($order->status === 'completed')
        <form method="POST" action="{{ route('admin_orders_refund', $order->id) }}" class="mt-3">
            {{ csrf_field() }}
            <div class="mb-3">
                <label class="form-label">Refund Reason</label>
                <textarea name="reason" class="form-control" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Process Refund</button>
        </form>
        @endif
    </div>
</div>
@endsection