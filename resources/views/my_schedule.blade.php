@extends('layouts.master')

@section('title', 'My Schedule')

@push('styles')
<style>
    .schedule-timeline {
        position: relative;
        padding-left: 50px;
    }
    .schedule-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--glass-border);
    }
    .timeline-dot {
        position: absolute;
        left: 11px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--theme-primary);
        border: 4px solid var(--bg-color);
        z-index: 2;
        box-shadow: 0 0 10px var(--theme-primary);
    }
    .schedule-card {
        transition: all 0.3s ease;
        border: 1px solid var(--glass-border);
    }
    .schedule-card:hover {
        transform: translateX(10px);
        border-color: var(--theme-primary);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h1 class="display-5 fw-black mb-0">My Event Schedule</h1>
        <p class="text-secondary">Keep track of your upcoming experiences</p>
    </div>

    <div class="row">
        <div class="col-lg-10">
            @forelse($tickets as $date => $dayTickets)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="badge bg-primary px-3 py-2 me-3 fs-6">
                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                        </div>
                        <div class="flex-grow-1 border-bottom border-white-10"></div>
                    </div>

                    <div class="schedule-timeline">
                        @foreach($dayTickets as $ticket)
                            @php
                                $event = $ticket->event;
                                $type = strtolower($event->event_type ?? 'concert');
                                $iconClass = 'bi-ticket-perforated';
                                if (str_contains($type, 'concert')) $iconClass = 'bi-music-note-beamed';
                                elseif (str_contains($type, 'sport')) $iconClass = 'bi-trophy';
                                elseif (str_contains($type, 'theater')) $iconClass = 'bi-masks';
                                elseif (str_contains($type, 'conference')) $iconClass = 'bi-mic';
                            @endphp
                            
                            <div class="position-relative mb-4">
                                <div class="timeline-dot"></div>
                                <div class="card glass-card schedule-card">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 text-center text-md-start">
                                                <h3 class="fw-black mb-0">{{ \Carbon\Carbon::parse($event->event_date)->format('g:i') }}</h3>
                                                <small class="text-secondary text-uppercase">{{ \Carbon\Carbon::parse($event->event_date)->format('A') }}</small>
                                            </div>
                                            <div class="col-md-7 border-start border-white-10 ps-md-4">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="badge badge-glass small"><i class="bi {{ $iconClass }} me-1"></i> {{ ucfirst($event->event_type) }}</span>
                                                    <span class="text-secondary small">Seat: {{ $ticket->row }}{{ $ticket->seat_number }}</span>
                                                </div>
                                                <h4 class="fw-bold mb-1">{{ $event->name }}</h4>
                                                <p class="text-secondary mb-0 small"><i class="bi bi-geo-alt me-1"></i> {{ $event->venue ? $event->venue->name : 'Grand Arena' }}</p>
                                            </div>
                                            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                                <a href="{{ route('my_tickets') }}" class="btn btn-sm btn-outline-glass">
                                                    View Ticket <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-5 glass-card p-5">
                    <i class="bi bi-calendar-x display-1 text-secondary opacity-20 mb-4"></i>
                    <h3>No upcoming events</h3>
                    <p class="text-secondary">You don't have any events scheduled soon.</p>
                    <a href="{{ route('home') }}" class="btn btn-modern btn-gradient mt-3">Explore Events</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
