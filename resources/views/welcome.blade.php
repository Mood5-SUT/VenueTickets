@extends('layouts.master')

@section('title', 'Discover Events')

@section('content')
<!-- Hero Section -->
<div class="position-relative overflow-hidden text-center" style="padding: 100px 0 80px 0;">
    <div class="container position-relative z-index-1">
        <h1 class="display-3 fw-black mb-4" style="font-weight: 900; letter-spacing: -2px;">
            Experience The <span style="background: var(--theme-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Extraordinary</span>
        </h1>
        <p class="lead mb-5 mx-auto text-secondary" style="max-width: 600px; font-size: 1.25rem;">
            Secure your spot at the most anticipated concerts, thrilling matches, and exclusive theater performances.
        </p>
        
        <form action="{{ route('admin_search') }}" method="GET" class="mx-auto" style="max-width: 650px;">
            <div class="glass-card p-2 d-flex align-items-center" style="border-radius: 50px;">
                <i class="bi bi-search ms-3 text-muted" style="font-size: 1.2rem;"></i>
                <input type="text" name="q" class="form-control border-0 bg-transparent shadow-none ps-3" placeholder="Search for artists, teams, or venues..." style="height: 50px; font-size: 1.1rem; color: var(--text-primary);">
                <button type="submit" class="btn btn-modern btn-gradient" style="height: 50px; padding: 0 30px;">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Trending Events Section -->
<div class="container-xl py-5">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1">Trending Near You</h2>
            <p class="text-secondary mb-0">Don't miss out on these upcoming experiences</p>
        </div>
        <a href="#" class="btn btn-outline-glass btn-modern">View All <i class="bi bi-arrow-right ms-2"></i></a>
    </div>

    @if(isset($events) && $events->count() > 0)
        <div class="row g-4">
            @foreach($events as $event)
                @php
                    // Determine theme class based on event_type
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
                
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="ticket-card {{ $themeClass }} overflow-hidden" style="height: 520px; border-radius: 32px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); backdrop-filter: blur(20px); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative;">
                        <!-- Glow effect -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 50% -20%, var(--theme-primary), transparent 60%); opacity: 0.15; z-index: 0;"></div>
                        
                        <!-- Top Section (65%) -->
                        <div style="height: 65%; position: relative; overflow: hidden;">
                            @if($event->image_url)
                                <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="w-100 h-100 object-fit-cover" style="z-index: 0; filter: brightness(0.8);">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.03);">
                                    <i class="bi {{ $iconClass }}" style="font-size: 8rem; opacity: 0.1; color: var(--theme-primary);"></i>
                                </div>
                            @endif
                            
                            <!-- Ultra-modern Gradients for readability -->
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, transparent 40%, transparent 60%, var(--bg-color) 100%); z-index: 1;"></div>
                            
                            <!-- Badges and Price -->
                            <div class="position-absolute top-0 start-0 w-100 p-4 d-flex justify-content-between align-items-center" style="z-index: 2;">
                                <span class="badge badge-glass px-3 py-2" style="background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);"><i class="bi {{ $iconClass }} me-1"></i> {{ strtoupper($event->event_type ?? 'Event') }}</span>
                                <div class="glass-pill" style="background: var(--theme-gradient); padding: 6px 15px; border-radius: 50px; font-weight: 900; letter-spacing: -1px; box-shadow: 0 4px 15px rgba(0,0,0,0.4);">
                                    ${{ number_format($event->metadata['base_price'] ?? 50, 0) }}
                                </div>
                            </div>

                            <!-- Title and Info -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-4 pb-1" style="z-index: 2;">
                                <h2 class="fw-black text-white mb-2" style="font-size: 2.5rem; line-height: 0.9; letter-spacing: -2.5px; text-shadow: 0 2px 15px rgba(0,0,0,0.8);">{{ $event->name }}</h2>
                                <div class="d-flex align-items-center gap-3 text-white-50">
                                    <span style="font-size: 0.85rem;"><i class="bi bi-calendar3 me-1 text-primary"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}</span>
                                    <span style="font-size: 0.85rem;"><i class="bi bi-geo-alt me-1 text-secondary"></i> {{ $event->venue ? $event->venue->name : 'Main Arena' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Modern Perforated Divider -->
                        <div style="position: relative; height: 1px; z-index: 3;">
                            <div style="position: absolute; width: 100%; border-top: 1px dashed rgba(255,255,255,0.2); top: 0;"></div>
                            <div style="position: absolute; left: -12px; top: -12px; width: 24px; height: 24px; background: var(--bg-color); border-radius: 50%; box-shadow: inset -5px 0 10px rgba(0,0,0,0.5);"></div>
                            <div style="position: absolute; right: -12px; top: -12px; width: 24px; height: 24px; background: var(--bg-color); border-radius: 50%; box-shadow: inset 5px 0 10px rgba(0,0,0,0.5);"></div>
                        </div>

                        <!-- Bottom Section (35%) -->
                        <div class="d-flex flex-column align-items-center justify-content-center p-4 text-center position-relative" style="height: 35%; z-index: 2;">
                            <div class="w-100 mb-4 opacity-25" style="height: 40px; background: repeating-linear-gradient(90deg, #fff, #fff 1px, transparent 1px, transparent 4px, #fff 4px, #fff 6px, transparent 6px, transparent 10px);"></div>
                            <a href="{{ route('event.show', $event->id) }}" class="btn btn-modern btn-gradient w-100 py-3 fs-5 fw-black shadow-lg" style="border-radius: 20px; letter-spacing: 1px;">
                                GET YOUR TICKETS <i class="bi bi-arrow-right-short ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 glass-card" style="border-radius: 20px;">
            <i class="bi bi-calendar-x text-secondary mb-3" style="font-size: 4rem;"></i>
            <h3>No Events Found</h3>
            <p class="text-muted">Check back later for exciting upcoming events!</p>
        </div>
    @endif
</div>

<!-- Features Section -->
<div class="container py-5 mt-4">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="p-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 80px; height: 80px; background: rgba(67, 97, 238, 0.1); border: 1px solid rgba(67, 97, 238, 0.3);">
                    <i class="bi bi-shield-check text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bold">100% Secure</h4>
                <p class="text-secondary">Your tickets are verified and guaranteed. Advanced locking prevents double booking.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 80px; height: 80px; background: rgba(247, 37, 133, 0.1); border: 1px solid rgba(247, 37, 133, 0.3);">
                    <i class="bi bi-lightning text-danger" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bold">Instant Delivery</h4>
                <p class="text-secondary">Get your digital tickets instantly in your account. Ready to scan at the venue.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 80px; height: 80px; background: rgba(0, 180, 216, 0.1); border: 1px solid rgba(0, 180, 216, 0.3);">
                    <i class="bi bi-headset text-info" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bold">24/7 Support</h4>
                <p class="text-secondary">Our dedicated team is here to help you anytime, anywhere.</p>
            </div>
        </div>
    </div>
</div>
@endsection