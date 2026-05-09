@extends('layouts.master')

@section('title', 'Booking Successful')

@php
    $event = $order->event;
    $type = strtolower($event->event_type ?? 'concert');
    $themeClass = 'theme-default';
    if (str_contains($type, 'concert') || str_contains($type, 'music')) $themeClass = 'theme-concert';
    elseif (str_contains($type, 'sport') || str_contains($type, 'football')) $themeClass = 'theme-football';
    elseif (str_contains($type, 'theater') || str_contains($type, 'show')) $themeClass = 'theme-theater';
    elseif (str_contains($type, 'conference') || str_contains($type, 'tech')) $themeClass = 'theme-conference';
@endphp

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-4" style="width: 80px; height: 80px; font-size: 2.5rem;">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1 class="display-4 fw-black">Booking Confirmed!</h1>
        <p class="lead text-secondary">Thank you for your purchase. Your tickets are now available in your account.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Receipt Card -->
            <div class="card glass-card overflow-hidden">
                <div class="card-header bg-white-5 p-4 d-flex justify-content-between align-items-center border-bottom border-white-10">
                    <div>
                        <small class="text-secondary text-uppercase fw-bold">Order Number</small>
                        <h5 class="mb-0 fw-bold">{{ $order->order_number }}</h5>
                    </div>
                    <div class="text-end">
                        <small class="text-secondary text-uppercase fw-bold">Date</small>
                        <h5 class="mb-0 fw-bold">{{ $order->paid_at->format('M d, Y') }}</h5>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- Event Summary Strip -->
                    <div class="p-4 border-bottom border-white-10 {{ $themeClass }}" style="background: rgba(255,255,255,0.03);">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($event->image_url)
                                    <img src="{{ $event->image_url }}" class="img-fluid rounded shadow-sm" alt="Event">
                                @else
                                    <div class="bg-white-10 rounded d-flex align-items-center justify-content-center" style="aspect-ratio: 1; font-size: 2rem;">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h3 class="fw-bold mb-1">{{ $event->name }}</h3>
                                <p class="text-secondary mb-0">
                                    <i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $event->venue ? $event->venue->name : 'Grand Arena' }}<br>
                                    <i class="bi bi-calendar-check me-1 text-primary"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y • g:i A') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge badge-glass fs-6">{{ count($order->tickets) }} Tickets</span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="p-4">
                        <table class="table table-borderless text-white mb-0">
                            <thead>
                                <tr class="text-secondary small text-uppercase fw-bold border-bottom border-white-10">
                                    <th>Description</th>
                                    <th class="text-center">Seat</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->tickets as $ticket)
                                    <tr class="align-middle">
                                        <td class="py-3">
                                            <div class="fw-bold">{{ $ticket->pricingTier->name ?? 'Standard Entry' }}</div>
                                            <small class="text-secondary">General Admission</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-white-10 px-3 py-2 fs-6">{{ $ticket->row }}{{ $ticket->seat_number }}</span>
                                        </td>
                                        <td class="text-end fw-bold">${{ number_format($ticket->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-top border-white-10">
                                    <td colspan="2" class="pt-4 text-end text-secondary">Subtotal</td>
                                    <td class="pt-4 text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end text-secondary">Service Fee</td>
                                    <td class="text-end">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="pt-3 text-end fs-4 fw-bold">Total Paid</td>
                                    <td class="pt-3 text-end fs-4 fw-black text-primary">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white-5 p-4 border-top border-white-10 text-center">
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('my_tickets') }}" class="btn btn-modern btn-gradient px-4">
                            <i class="bi bi-ticket-detailed me-2"></i> View My Tickets
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-glass px-4">
                            <i class="bi bi-printer me-2"></i> Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
