@extends('layouts.master')

@section('title', $event->name)

@php
    $type = strtolower($event->event_type ?? 'concert');
    $themeClass = 'theme-default';
    $iconClass = 'bi-ticket-perforated';
    $layoutType = 'theater';
    
    if (str_contains($type, 'concert') || str_contains($type, 'music')) {
        $themeClass = 'theme-concert';
        $iconClass = 'bi-music-note-beamed';
        $layoutType = 'concert';
    } elseif (str_contains($type, 'sport') || str_contains($type, 'football')) {
        $themeClass = 'theme-football';
        $iconClass = 'bi-trophy';
        $layoutType = 'stadium';
    } elseif (str_contains($type, 'theater') || str_contains($type, 'show')) {
        $themeClass = 'theme-theater';
        $iconClass = 'bi-masks';
        $layoutType = 'theater';
    }
@endphp

@push('styles')
<style>
    .event-header { border-radius: 24px; overflow: hidden; position: relative; min-height: 400px; display: flex; align-items: flex-end; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
    .event-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, var(--bg-color) 0%, rgba(0,0,0,0.2) 100%); z-index: 1; }
    .event-content { position: relative; z-index: 2; width: 100%; padding: 3rem !important; }
    
    .seat-map-container { background: rgba(255, 255, 255, 0.03); border-radius: 30px; padding: 40px; border: 1px solid var(--glass-border); position: relative; min-height: 450px; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    
    .seat { width: 30px; height: 30px; border-radius: 6px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; color: rgba(255,255,255,0.3); }
    .seat:hover:not(.taken):not(.disabled) { transform: scale(1.2); background: rgba(255,255,255,0.15); border-color: var(--theme-primary); }
    .seat.selected { background: var(--theme-primary) !important; border-color: white !important; color: white !important; box-shadow: 0 0 10px var(--theme-primary); }
    .seat.taken { background: rgba(255,255,255,0.02); color: transparent; cursor: not-allowed; border: none; }
    .seat.disabled { opacity: 0.15; cursor: not-allowed; filter: grayscale(1); }

    /* Fix Stadium Layout */
    .stadium-layout { position: relative; width: 320px; height: 320px; margin: 0 auto; border-radius: 50%; border: 12px solid rgba(255,255,255,0.03); }
    .stadium-seat { position: absolute; width: 20px; height: 20px; border-radius: 3px; transform-origin: 160px 160px; font-size: 0.5rem; }
    
    /* Concert Layout */
    .zone-box { width: 100%; height: 100px; border-radius: 15px; border: 2px dashed rgba(255,255,255,0.1); display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; margin-bottom: 12px; background: rgba(255,255,255,0.02); }
    .zone-box:hover:not(.disabled) { background: rgba(255,255,255,0.08); border-color: var(--theme-primary); }
    .zone-box.selected { background: var(--theme-gradient) !important; border-style: solid; border-color: white; box-shadow: 0 0 20px var(--theme-primary); }
    .zone-box.disabled { opacity: 0.3; cursor: not-allowed; }

    .screen-indicator { width: 50%; height: 4px; background: var(--theme-gradient); margin: 0 auto 30px; border-radius: 10px; filter: blur(1px); position: relative; }
    .screen-indicator::after { content: 'STAGE / FIELD'; position: absolute; bottom: -18px; left: 50%; transform: translateX(-50%); font-size: 0.55rem; letter-spacing: 3px; color: rgba(255,255,255,0.3); }

    .tier-selector { cursor: pointer; transition: all 0.3s ease; }
    .tier-selector:hover { border-color: var(--theme-primary) !important; }
</style>
@endpush

@section('content')
<div class="container-xl py-4">
    <div class="row g-4">
        <!-- Event Header -->
        <div class="col-12">
            <div class="event-header glass-card {{ $themeClass }}">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="position-absolute w-100 h-100 object-fit-cover" style="z-index: 0;">
                @endif
                <div class="event-overlay"></div>
                <div class="event-content text-white">
                    <span class="badge badge-glass mb-3 py-2 px-3"><i class="bi {{ $iconClass }} me-1"></i> {{ strtoupper($event->event_type) }}</span>
                    <h1 class="display-4 fw-black mb-2" style="letter-spacing: -2px;">{{ $event->name }}</h1>
                    <div class="d-flex gap-4 opacity-75">
                        <span><i class="bi bi-geo-alt me-1 text-primary"></i> {{ $event->venue ? $event->venue->name : 'Arena' }}</span>
                        <span><i class="bi bi-calendar3 me-1 text-secondary"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seat Selection -->
        <div class="col-lg-8">
            <div class="card glass-card h-100">
                <div class="card-header p-4 border-bottom border-white-10">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i> Choose Your Space</h4>
                </div>
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                    
                    @if($layoutType === 'concert')
                        <div class="w-100 px-md-5">
                            <div class="screen-indicator mb-5"></div>
                            <div class="zone-box" data-zone="VVIP" data-row="A">
                                <h5 class="fw-black mb-0">VVIP ZONE</h5>
                                <small class="text-secondary">FRONT ROW ACCESS</small>
                            </div>
                            <div class="zone-box" data-zone="VIP" data-row="B">
                                <h5 class="fw-black mb-0">VIP ZONE</h5>
                                <small class="text-secondary">MID FIELD ACCESS</small>
                            </div>
                            <div class="zone-box" data-zone="Standard" data-row="C,D,E">
                                <h5 class="fw-black mb-0">STANDARD ZONE</h5>
                                <small class="text-secondary">GENERAL ADMISSION</small>
                            </div>
                        </div>

                    @elseif($layoutType === 'stadium')
                        <div class="screen-indicator mb-5" style="width: 80px;"></div>
                        <div class="stadium-layout">
                            @php $totalSeats = 48; @endphp
                            @for($i = 0; $i < $totalSeats; $i++)
                                @php
                                    $angle = ($i / $totalSeats) * 360;
                                    $row = ($i < 12) ? 'A' : (($i < 30) ? 'B' : 'C');
                                    $num = $i + 1;
                                    $seatId = $row . '-' . $num;
                                    $isTaken = in_array($seatId, $takenSeats);
                                @endphp
                                <div class="seat stadium-seat {{ $isTaken ? 'taken' : '' }}" 
                                     style="transform: rotate({{ $angle }}deg) translate(130px) rotate(-{{ $angle }}deg);"
                                     data-seat="{{ $seatId }}" data-row="{{ $row }}">
                                    {{ $num }}
                                </div>
                            @endfor
                            <div class="position-absolute top-50 start-50 translate-middle text-center opacity-10">
                                <i class="bi bi-trophy-fill display-4"></i>
                            </div>
                        </div>

                    @else
                        <div class="w-100">
                            <div class="screen-indicator"></div>
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $row)
                                <div class="d-flex justify-content-center gap-2 mb-2">
                                    <div class="text-primary fw-bold me-2 small" style="width: 15px;">{{ $row }}</div>
                                    @for($i = 1; $i <= 10; $i++)
                                        @php
                                            $seatId = $row . '-' . $i;
                                            $isTaken = in_array($seatId, $takenSeats);
                                        @endphp
                                        <div class="seat {{ $isTaken ? 'taken' : '' }}" 
                                             data-seat="{{ $seatId }}" data-row="{{ $row }}">
                                            {{ $i }}
                                        </div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex gap-4 mt-5">
                        <div class="small text-secondary"><span class="d-inline-block rounded-1 bg-white-10 me-1" style="width:10px; height:10px;"></span> Free</div>
                        <div class="small text-secondary"><span class="d-inline-block rounded-1 bg-primary me-1" style="width:10px; height:10px;"></span> Yours</div>
                        <div class="small text-secondary"><span class="d-inline-block rounded-1 opacity-20 bg-white me-1" style="width:10px; height:10px;"></span> Taken</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card glass-card position-sticky" style="top: 100px;">
                <div class="card-header p-4 border-bottom border-white-10">
                    <h4 class="mb-0 fw-bold">Order Details</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('event.checkout', $event->id) }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                        
                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold text-uppercase">1. Ticket Tier</label>
                            @foreach($event->pricingTiers as $tier)
                                @php
                                    $rowAccess = 'C,D,E';
                                    if(str_contains(strtoupper($tier->name), 'VVIP')) $rowAccess = 'A';
                                    elseif(str_contains(strtoupper($tier->name), 'VIP')) $rowAccess = 'B';
                                @endphp
                                <div class="form-check tier-selector p-3 mb-2 rounded border border-white-10">
                                    <input class="form-check-input" type="radio" name="tier_id" id="tier{{ $tier->id }}" value="{{ $tier->id }}" data-price="{{ $tier->price }}" data-rows="{{ $rowAccess }}" {{ $loop->first ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="tier{{ $tier->id }}">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">{{ $tier->name }}</span>
                                            <span class="text-primary fw-black">${{ number_format($tier->price, 0) }}</span>
                                        </div>
                                        <small class="text-secondary">Row {{ $rowAccess }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Quantity Selector for Concerts -->
                        <div class="mb-4" id="quantityContainer" style="display: {{ $layoutType === 'concert' ? 'block' : 'none' }}">
                            <label class="form-label text-secondary small fw-bold text-uppercase">2. How Many Tickets?</label>
                            <select class="form-select bg-white-5 border-white-10 text-white" id="ticketQuantity">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" class="bg-dark">{{ $i }} Ticket{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold text-uppercase">{{ $layoutType === 'concert' ? '3.' : '2.' }} Selection</label>
                            <div id="selectionSummary" class="p-3 bg-white-5 rounded border border-white-10 text-center">
                                <span class="text-secondary italic">Select tier & seats</span>
                            </div>
                        </div>

                        <div class="border-top border-white-10 pt-4 mt-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-4 fw-bold">Total</span>
                                <span id="totalDisplay" class="fs-4 fw-black text-primary">$0</span>
                            </div>
                            <button type="submit" id="checkoutBtn" class="btn btn-modern btn-gradient w-100 py-3 fs-5 fw-bold" disabled>
                                BOOK NOW <i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isConcert = {{ $layoutType === 'concert' ? 'true' : 'false' }};
    const tierRadios = document.querySelectorAll('input[name="tier_id"]');
    const seats = document.querySelectorAll('.seat, .zone-box');
    const qtySelect = document.getElementById('ticketQuantity');
    const selectedInput = document.getElementById('selectedSeatsInput');
    const summary = document.getElementById('selectionSummary');
    const totalDisplay = document.getElementById('totalDisplay');
    const checkoutBtn = document.getElementById('checkoutBtn');

    let selected = [];

    function updateView() {
        const checkedTier = document.querySelector('input[name="tier_id"]:checked');
        if (!checkedTier) return;

        const allowedRows = checkedTier.dataset.rows.split(',');
        
        seats.forEach(seat => {
            const row = seat.dataset.row;
            let canAccess = false;
            allowedRows.forEach(r => { if(row.includes(r)) canAccess = true; });

            if (canAccess) {
                seat.classList.remove('disabled');
            } else {
                seat.classList.add('disabled');
                seat.classList.remove('selected');
                selected = selected.filter(id => id !== (seat.dataset.seat || seat.dataset.zone));
            }
        });

        // Update Summary & Price
        if (selected.length === 0) {
            summary.innerHTML = '<span class="text-secondary italic">Pick your spots</span>';
            totalDisplay.textContent = '$0';
            checkoutBtn.disabled = true;
        } else {
            const qty = isConcert ? parseInt(qtySelect.value) : selected.length;
            const price = parseFloat(checkedTier.dataset.price);
            
            if(isConcert) {
                summary.innerHTML = `<span class="badge bg-primary px-3 py-2">${selected[0]} Zone x ${qty}</span>`;
                // For concert, we mock seat IDs as "ZONE-1, ZONE-2..."
                let mockSeats = [];
                for(let i=1; i<=qty; i++) mockSeats.push(`${selected[0]}-${i}`);
                selectedInput.value = mockSeats.join(',');
            } else {
                summary.innerHTML = `<div class="d-flex flex-wrap gap-1 justify-content-center">${selected.map(s => `<span class="badge bg-primary p-1" style="font-size:0.6rem;">${s}</span>`).join('')}</div>`;
                selectedInput.value = selected.join(',');
            }
            
            totalDisplay.textContent = '$' + (price * qty);
            checkoutBtn.disabled = false;
        }
    }

    tierRadios.forEach(radio => radio.addEventListener('change', () => {
        if(!isConcert) { selected = []; seats.forEach(s => s.classList.remove('selected')); }
        updateView();
    }));

    if(qtySelect) qtySelect.addEventListener('change', updateView);

    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            if (this.classList.contains('disabled') || this.classList.contains('taken')) return;

            const id = this.dataset.seat || this.dataset.zone;
            
            if(isConcert) {
                // Concert: Only one zone can be selected at a time
                seats.forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                selected = [id];
            } else {
                if (selected.includes(id)) {
                    selected = selected.filter(s => s !== id);
                    this.classList.remove('selected');
                } else {
                    selected.push(id);
                    this.classList.add('selected');
                }
            }
            updateView();
        });
    });

    updateView();
});
</script>
@endpush
@endsection
