@extends('layouts.master')

@section('title', 'Order History')

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h1 class="display-5 fw-black mb-0">Order History</h1>
        <p class="text-secondary">View all your past transactions and ticket details</p>
    </div>

    <div class="row">
        <div class="col-12">
            @forelse($orders as $order)
                <div class="glass-card mb-4 overflow-hidden" style="border-radius: 20px;">
                    <div class="p-4 border-bottom border-white-10 d-flex justify-content-between align-items-center bg-white-5">
                        <div>
                            <span class="text-secondary small fw-bold text-uppercase d-block">Order Number</span>
                            <span class="fw-black h5 mb-0 text-white">{{ $order->order_number ?? 'ORD-' . $order->id }}</span>
                        </div>
                        <div class="text-center">
                            <span class="text-secondary small fw-bold text-uppercase d-block">Date</span>
                            <span class="fw-bold text-white">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="text-center">
                            <span class="text-secondary small fw-bold text-uppercase d-block">Tickets</span>
                            <span class="badge bg-primary rounded-pill px-3">{{ $order->tickets->count() }}</span>
                        </div>
                        <div class="text-end">
                            <span class="text-secondary small fw-bold text-uppercase d-block">Total Amount</span>
                            <span class="fw-black h5 mb-0 text-primary">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-4">
                            @if($order->event && $order->event->image_url)
                                <img src="{{ $order->event->image_url }}" class="rounded-3" width="100" height="100" style="object-fit: cover;">
                            @else
                                <div class="bg-white-5 rounded-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i class="bi bi-calendar-event text-secondary display-6"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h4 class="fw-bold mb-1">{{ $order->event ? $order->event->name : 'Unknown Event' }}</h4>
                                <p class="text-secondary mb-2"><i class="bi bi-geo-alt me-1"></i> {{ $order->event && $order->event->venue ? $order->event->venue->name : 'Venue' }}</p>
                                <div class="d-flex gap-2">
                                    <span class="badge badge-glass border-white-10">{{ strtoupper($order->status) }}</span>
                                    <span class="badge badge-glass border-white-10">{{ strtoupper($order->payment_status) }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('order.success', $order->id) }}" class="btn btn-modern btn-outline-glass">View Receipt</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 glass-card">
                    <i class="bi bi-receipt display-1 text-secondary opacity-20 mb-4"></i>
                    <h3>No orders found</h3>
                    <p class="text-secondary">You haven't made any purchases yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-modern btn-gradient mt-3">Start Browsing</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
