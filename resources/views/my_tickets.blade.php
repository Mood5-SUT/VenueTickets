@extends('layouts.master')

@section('title', 'My Tickets')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="display-5 fw-black mb-0">My Tickets</h1>
            <p class="text-secondary">All your upcoming and past event entries</p>
        </div>
        <div class="badge badge-glass px-4 py-3 fs-6">
            <i class="bi bi-person-circle me-2"></i> {{ auth()->user()->name }}
        </div>
    </div>

    <div class="row g-4">
        @forelse($tickets as $ticket)
            @php
                $event = $ticket->event;
                $type = strtolower($event->event_type ?? 'concert');
                $themeClass = 'theme-default';
                $iconClass = 'bi-ticket-perforated';
                
                if (str_contains($type, 'concert') || str_contains($type, 'music')) {
                    $themeClass = 'theme-concert';
                    $iconClass = 'bi-music-note-beamed';
                } elseif (str_contains($type, 'sport') || str_contains($type, 'football')) {
                    $themeClass = 'theme-football';
                    $iconClass = 'bi-trophy';
                } elseif (str_contains($type, 'theater') || str_contains($type, 'show')) {
                    $themeClass = 'theme-theater';
                    $iconClass = 'bi-masks';
                } elseif (str_contains($type, 'conference') || str_contains($type, 'tech')) {
                    $themeClass = 'theme-conference';
                    $iconClass = 'bi-mic';
                }
            @endphp
            
            <div class="col-xl-6">
                <!-- Premium Mini Physical Ticket -->
                <div class="ticket-card {{ $themeClass }} d-flex flex-row overflow-hidden shadow-lg" style="height: 240px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); backdrop-filter: blur(20px);">
                    
                    <!-- Main Section (70%) -->
                    <div class="p-4 d-flex flex-column justify-content-between" style="width: 70%; position: relative; overflow: hidden;">
                        @if($event->image_url)
                            <img src="{{ $event->image_url }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="z-index: 0; filter: brightness(0.5) contrast(1.2);">
                        @else
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(255,255,255,0.03); z-index: 0;"></div>
                        @endif
                        
                        <!-- Dark gradient for readability -->
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.6) 0%, transparent 100%); z-index: 1;"></div>

                        <div style="z-index: 2;">
                            <span class="badge badge-glass mb-2 py-1 px-2" style="font-size: 0.7rem;"><i class="bi {{ $iconClass }} me-1"></i> {{ strtoupper($event->event_type ?? 'Event') }}</span>
                            <h3 class="fw-black mb-1 text-white" style="letter-spacing: -1.5px; font-size: 1.8rem;">{{ $event->name }}</h3>
                            <p class="text-white-50 small mb-0"><i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $event->venue ? $event->venue->name : 'Main Arena' }}</p>
                            <p class="text-white-50 small mb-0"><i class="bi bi-calendar-check-fill me-1 text-secondary"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('D, M d, Y') }}</p>
                        </div>
                        
                        <div class="d-flex gap-2" style="z-index: 2;">
                            <div class="bg-black-40 p-2 rounded border border-white-10 text-center" style="min-width: 50px; backdrop-filter: blur(5px);">
                                <small class="text-white-50 d-block lh-1 small">ROW</small>
                                <strong class="text-white">{{ $ticket->row }}</strong>
                            </div>
                            <div class="bg-black-40 p-2 rounded border border-white-10 text-center" style="min-width: 50px; backdrop-filter: blur(5px);">
                                <small class="text-white-50 d-block lh-1 small">SEAT</small>
                                <strong class="text-white">{{ $ticket->seat_number }}</strong>
                            </div>
                            <div class="bg-black-40 p-2 rounded border border-white-10 text-center flex-grow-1 backdrop-filter: blur(5px);">
                                <small class="text-white-50 d-block lh-1 small">TIER</small>
                                <strong class="text-white small">{{ strtoupper($ticket->pricingTier->name ?? 'Standard') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Perforated Divider -->
                    <div style="position: relative; width: 2px; height: 100%; z-index: 3;">
                        <div style="position: absolute; height: 100%; border-left: 2px dashed rgba(255,255,255,0.2); left: 0;"></div>
                        <div style="position: absolute; top: -12px; left: -11px; width: 24px; height: 24px; background: var(--bg-color); border-radius: 50%; box-shadow: inset 0 -5px 10px rgba(0,0,0,0.5);"></div>
                        <div style="position: absolute; bottom: -12px; left: -11px; width: 24px; height: 24px; background: var(--bg-color); border-radius: 50%; box-shadow: inset 0 5px 10px rgba(0,0,0,0.5);"></div>
                    </div>

                    <!-- Stub Section (30%) -->
                    <div class="bg-white-5 p-3 d-flex flex-column align-items-center justify-content-center text-center" style="width: 30%; z-index: 2;">
                        <div class="p-2 bg-white rounded-3 mb-2 shadow-lg">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $ticket->qr_code }}" alt="QR Code" width="70" height="70" style="display: block;">
                        </div>
                        <small class="text-white-50 fw-bold" style="font-size: 0.55rem; letter-spacing: 1px;">#{{ $ticket->ticket_number }}</small>
                        <span class="badge {{ $ticket->status === 'valid' ? 'bg-success' : 'bg-danger' }} rounded-pill mt-2 py-1 px-3" style="font-size: 0.6rem;">{{ strtoupper($ticket->status) }}</span>
                        
                        @if($ticket->status === 'valid')
                            <div class="d-flex flex-column gap-1 mt-2">
                                <a href="{{ route('ticket.download', $ticket->id) }}" class="btn btn-link btn-sm text-white-50 p-0" style="text-decoration: none;">
                                    <i class="bi bi-download me-1"></i> PDF
                                </a>
                                <button type="button" class="btn btn-link btn-sm text-white-50 p-0" data-bs-toggle="modal" data-bs-target="#transferModal{{ $ticket->id }}">
                                    <i class="bi bi-send me-1"></i> Transfer
                                </button>
                            </div>

                            <!-- Transfer Modal -->
                            <div class="modal fade" id="transferModal{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content glass-card border-white-10">
                                        <div class="modal-header border-white-10">
                                            <h5 class="modal-title">Transfer Ticket</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('ticket.transfer', $ticket->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body text-start">
                                                <p class="small text-secondary mb-3">Transfer this ticket to another user by entering their email address. They must have an account on this platform.</p>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Recipient Email</label>
                                                    <input type="email" name="email" class="form-control bg-white-5 border-white-10 text-white" placeholder="friend@example.com" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-white-10">
                                                <button type="button" class="btn btn-modern btn-outline-glass" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-modern btn-gradient">Confirm Transfer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="glass-card p-5">
                    <i class="bi bi-ticket-perforated display-1 text-secondary opacity-20 mb-4"></i>
                    <h3>No tickets found</h3>
                    <p class="text-secondary">You haven't booked any experiences yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-modern btn-gradient mt-3">Browse Events</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
