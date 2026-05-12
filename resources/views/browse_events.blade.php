@extends('layouts.master')

@section('title', 'Browse Events')

@section('content')
<div class="container py-5">
    <div class="row mb-5 align-items-end">
        <div class="col-md-6">
            <h1 class="display-4 fw-black mb-0">Browse <span class="text-gradient">Events</span></h1>
            <p class="text-secondary mb-0">Discover the best experiences happening around you</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('events.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-12">
                        <div class="glass-card d-flex align-items-center px-3 mb-2" style="border-radius: 15px;">
                            <i class="bi bi-search text-muted"></i>
                            <input type="text" name="q" value="{{ $query }}" class="form-control border-0 bg-transparent shadow-none" placeholder="Search events...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="type" class="form-select glass-card border-0 text-white w-100" style="border-radius: 15px;">
                            <option value="" class="bg-dark">All Types</option>
                            <option value="concert" {{ $type == 'concert' ? 'selected' : '' }} class="bg-dark">Concerts</option>
                            <option value="sports" {{ $type == 'sports' ? 'selected' : '' }} class="bg-dark">Sports</option>
                            <option value="theater" {{ $type == 'theater' ? 'selected' : '' }} class="bg-dark">Theater</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card d-flex align-items-center px-3" style="border-radius: 15px;">
                            <i class="bi bi-geo-alt text-muted"></i>
                            <input type="text" name="location" value="{{ $location ?? '' }}" class="form-control border-0 bg-transparent shadow-none small" placeholder="City/Venue">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-modern btn-gradient w-100" style="border-radius: 15px;">Filter</button>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="small text-secondary">Date range:</span>
                            <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="form-control glass-card border-0 text-white small py-1" style="border-radius: 10px; width: auto;">
                            <span class="text-secondary small">to</span>
                            <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="form-control glass-card border-0 text-white small py-1" style="border-radius: 10px; width: auto;">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($events->count() > 0)
        <div class="row g-4">
            @foreach($events as $event)
                @php
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
                    } elseif (str_contains($type, 'cinema') || str_contains($type, 'movie')) {
                        $themeClass = 'theme-cinema';
                        $iconClass = 'bi-film';
                    } elseif (str_contains($type, 'exhibition') || str_contains($type, 'museum') || str_contains($type, 'art')) {
                        $themeClass = 'theme-exhibition';
                        $iconClass = 'bi-palette';
                    } elseif (str_contains($type, 'party') || str_contains($type, 'nightlife') || str_contains($type, 'club')) {
                        $themeClass = 'theme-nightlife';
                        $iconClass = 'bi-stars';
                    }
                @endphp
                <div class="col-md-4">
                    <div class="ticket-card {{ $themeClass }} overflow-hidden" style="height: 480px; border-radius: 28px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); backdrop-filter: blur(20px); transition: transform 0.3s ease;">
                        <div style="height: 60%; position: relative;">
                            @if($event->image_url)
                                <img src="{{ $event->image_url }}" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.7);">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-white-5">
                                    <i class="bi {{ $iconClass }} display-1 opacity-10"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 p-3">
                                <span class="badge badge-glass px-3 py-2"><i class="bi {{ $iconClass }} me-1"></i> {{ strtoupper($event->event_type ?? 'Event') }}</span>
                            </div>
                        </div>
                        <div class="p-4 d-flex flex-column justify-content-between" style="height: 40%;">
                            <div>
                                <h3 class="fw-bold text-theme mb-1 h5">{{ $event->name }}</h3>
                                <p class="text-theme-secondary small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $event->venue ? $event->venue->name : 'Venue' }}</p>
                                <p class="text-theme-secondary small mb-2"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                                @if($event->description)
                                    <p class="text-theme-secondary small mb-0 opacity-50">{{ Str::limit($event->description, 60) }}</p>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h4 fw-black text-primary mb-0">${{ number_format($event->metadata['base_price'] ?? 50, 0) }}</span>
                                <a href="{{ route('event.show', $event->id) }}" class="btn btn-modern btn-gradient-sm">Get Tickets</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5 glass-card">
            <i class="bi bi-search display-1 text-secondary opacity-20 mb-4"></i>
            <h3>No events found matching your search</h3>
            <p class="text-secondary">Try different keywords or filters</p>
            <a href="{{ route('events.index') }}" class="btn btn-modern btn-outline-glass mt-3">Clear Filters</a>
        </div>
    @endif
</div>
@endsection
