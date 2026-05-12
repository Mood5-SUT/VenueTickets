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

@push('styles')
<style>
    .animate-in {
        animation: slideUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .success-pulse {
        animation: pulseShadow 2s infinite;
    }
    @keyframes pulseShadow {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }
    .confetti-canvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9999;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5 animate-in">
        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-4 shadow-lg success-pulse" style="width: 100px; height: 100px; font-size: 3rem; background: linear-gradient(135deg, #28a745, #20c997) !important;">
            <i class="bi bi-check2-all"></i>
        </div>
        <h1 class="display-3 fw-black mb-2" style="letter-spacing: -2px;">Booking <span class="text-gradient">Confirmed!</span></h1>
        <p class="lead text-secondary mx-auto" style="max-width: 700px;">Success! Your tickets have been secured. We've sent a confirmation to your email, and your digital tickets are ready below.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Receipt Details -->
        <div class="col-lg-8">
            <div class="card glass-card overflow-hidden mb-4 {{ $themeClass }}">
                <div class="card-header bg-white-5 p-4 d-flex justify-content-between align-items-center border-bottom border-white-10">
                    <div>
                        <small class="text-secondary text-uppercase fw-bold">Order Number</small>
                        <h5 class="mb-0 fw-bold">{{ $order->order_number }}</h5>
                    </div>
                    <div class="text-end">
                        <small class="text-secondary text-uppercase fw-bold">Total Paid</small>
                        <h5 class="mb-0 fw-black text-primary">${{ number_format($order->total_amount, 2) }}</h5>
                    </div>
                </div>
                
                <div class="card-body p-4 text-center border-bottom border-white-10">
                    <h5 class="fw-bold mb-4">What would you like to do next?</h5>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('order.download_receipt', $order->id) }}" class="btn btn-modern btn-gradient px-4 py-3 fs-6">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Download Receipt (PDF)
                        </a>
                        <a href="{{ route('order.download_tickets', $order->id) }}" class="btn btn-modern btn-outline-glass px-4 py-3 fs-6">
                            <i class="bi bi-ticket-perforated me-2"></i> Download All Tickets (PDF)
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="p-4 border-bottom border-white-10 {{ $themeClass }}" style="background: rgba(255,255,255,0.03);">
                        <div class="row align-items-center text-center text-md-start">
                            <div class="col-md-2 mb-3 mb-md-0">
                                @if($event->image_url)
                                    <img src="{{ $event->image_url }}" class="img-fluid rounded shadow-sm" alt="Event">
                                @else
                                    <div class="bg-white-10 rounded d-flex align-items-center justify-content-center mx-auto" style="aspect-ratio: 1; width: 80px; font-size: 2rem;">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <h3 class="fw-bold mb-1">{{ $event->name }}</h3>
                                <p class="text-secondary mb-0">
                                    <i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $event->venue ? $event->venue->name : 'Grand Arena' }}<br>
                                    <i class="bi bi-calendar-check me-1 text-primary"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y @ g:i A') }}
                                </p>
                            </div>
                            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-glass">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Tickets Section -->
            <h4 class="fw-black mb-4"><i class="bi bi-ticket-detailed me-2"></i> Your Digital Tickets</h4>
            <div class="row g-4">
                @forelse($order->tickets as $ticket)
                    @php
                        $iconClass = 'bi-ticket-perforated';
                        if (str_contains($type, 'concert') || str_contains($type, 'music')) $iconClass = 'bi-music-note-beamed';
                        elseif (str_contains($type, 'sport') || str_contains($type, 'football')) $iconClass = 'bi-trophy';
                        elseif (str_contains($type, 'theater') || str_contains($type, 'show')) $iconClass = 'bi-masks';
                        elseif (str_contains($type, 'conference') || str_contains($type, 'tech')) $iconClass = 'bi-mic';
                        elseif (str_contains($type, 'cinema') || str_contains($type, 'movie')) $iconClass = 'bi-film';
                        elseif (str_contains($type, 'exhibition') || str_contains($type, 'museum') || str_contains($type, 'art')) $iconClass = 'bi-palette';
                        elseif (str_contains($type, 'party') || str_contains($type, 'nightlife') || str_contains($type, 'club')) $iconClass = 'bi-stars';
                    @endphp
                    <div class="col-12">
                        <div class="ticket-card {{ $themeClass }} d-flex flex-column flex-md-row overflow-hidden shadow-lg" style="min-height: 200px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); backdrop-filter: blur(20px);">
                            
                            <!-- Main Section -->
                            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1" style="position: relative; overflow: hidden;">
                                @if($event->image_url)
                                    <img src="{{ $event->image_url }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="z-index: 0; filter: brightness(0.4) contrast(1.2);">
                                @endif
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, transparent 100%); z-index: 1;"></div>

                                <div style="z-index: 2;">
                                    <span class="badge badge-glass mb-2 py-1 px-2" style="font-size: 0.7rem;"><i class="bi {{ $iconClass }} me-1"></i> {{ strtoupper($event->event_type ?? 'Event') }}</span>
                                    <h3 class="fw-black mb-1 text-white" style="letter-spacing: -1px;">{{ $event->name }}</h3>
                                    <p class="text-white-50 small mb-0"><i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $event->venue ? $event->venue->name : 'Main Arena' }}</p>
                                </div>
                                
                                <div class="d-flex gap-2 mt-3" style="z-index: 2;">
                                    <div class="bg-black-40 p-2 rounded border border-white-10 text-center" style="min-width: 60px;">
                                        <small class="text-white-50 d-block lh-1 small">ROW</small>
                                        <strong class="text-white">{{ $ticket->row }}</strong>
                                    </div>
                                    <div class="bg-black-40 p-2 rounded border border-white-10 text-center" style="min-width: 60px;">
                                        <small class="text-white-50 d-block lh-1 small">SEAT</small>
                                        <strong class="text-white">{{ $ticket->seat_number }}</strong>
                                    </div>
                                    <div class="bg-black-40 p-2 rounded border border-white-10 text-center px-3">
                                        <small class="text-white-50 d-block lh-1 small">TIER</small>
                                        <strong class="text-white small">{{ strtoupper($ticket->pricingTier->name ?? 'Standard') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider (Only on MD+) -->
                            <div class="d-none d-md-block" style="position: relative; width: 2px; height: 100%; z-index: 3;">
                                <div style="position: absolute; height: 100%; border-left: 2px dashed rgba(255,255,255,0.2); left: 0;"></div>
                                <div style="position: absolute; top: -12px; left: -11px; width: 24px; height: 24px; background: var(--bg-color); border-radius: 50%;"></div>
                                <div style="position: absolute; bottom: -12px; left: -11px; width: 24px; height: 24px; background: var(--bg-color); border-radius: 50%;"></div>
                            </div>

                            <!-- Stub Section -->
                            <div class="bg-white-5 p-4 d-flex flex-column align-items-center justify-content-center text-center" style="min-width: 200px; z-index: 2;">
                                <div class="p-2 bg-white rounded-3 mb-2 shadow-lg">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $ticket->qr_code }}" alt="QR Code" width="80" height="80">
                                </div>
                                <small class="text-white-50 fw-bold" style="font-size: 0.6rem;">#{{ $ticket->ticket_number }}</small>
                                <a href="{{ route('ticket.download', $ticket->id) }}" class="btn btn-sm btn-link text-white-50 mt-2 p-0" style="text-decoration: none;">
                                    <i class="bi bi-download me-1"></i> PDF Ticket
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning">
                            No tickets found for this order. If you believe this is an error, please contact support.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
