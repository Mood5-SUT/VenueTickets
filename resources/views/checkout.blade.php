@extends('layouts.master')

@section('title', 'Checkout Summary')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-black mb-4">Checkout <span class="text-gradient">Summary</span></h1>
            
            <div class="card glass-card overflow-hidden mb-4">
                <div class="card-header bg-white-5 p-4 border-bottom border-white-10">
                    <h5 class="mb-0 fw-bold">Review Your Order</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom border-white-10">
                        @if($event->image_url)
                            <img src="{{ $event->image_url }}" class="rounded-3" width="120" height="120" style="object-fit: cover;">
                        @else
                            <div class="bg-white-5 rounded-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-calendar-event text-secondary display-5"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="fw-bold mb-1">{{ $event->name }}</h3>
                            <p class="text-secondary mb-1"><i class="bi bi-geo-alt me-1"></i> {{ $event->venue ? $event->venue->name : 'Venue' }}</p>
                            <p class="text-secondary mb-0"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('l, M d, Y • g:i A') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-secondary text-uppercase fw-bold small mb-3">Ticket Details</h6>
                        <div class="p-3 bg-white-5 rounded border border-white-10">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">{{ $tier->name }} Tier</span>
                                <span class="text-primary fw-black">${{ number_format($tier->price, 2) }} / ticket</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($seats as $seat)
                                    <span class="badge bg-white-10 border border-white-10 px-3 py-2">Seat {{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-secondary text-uppercase fw-bold small mb-3">Price Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Subtotal ({{ count($seats) }} Tickets)</span>
                            <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">Promo Discount ({{ $promoCode }})</span>
                                <span class="text-success fw-bold">-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Service Fee</span>
                            <span class="fw-bold">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top border-white-10">
                            <span class="fs-4 fw-black">Total to Pay</span>
                            <span class="fs-4 fw-black text-primary">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('event.purchase', $event->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_seats" value="{{ implode(',', $seats) }}">
                        <input type="hidden" name="tier_id" value="{{ $tier->id }}">
                        <input type="hidden" name="promo_code" value="{{ $promoCode }}">
                        <input type="hidden" name="idempotency_key" value="{{ uniqid('chk_', true) }}">
                        
                        <div class="alert alert-glass border-white-10 mb-4">
                            <div class="d-flex gap-3">
                                <i class="bi bi-shield-lock-fill text-primary fs-3"></i>
                                <p class="small mb-0 text-secondary">By clicking "Confirm & Purchase", you agree to our terms of service. This is a secure transaction. Tickets will be delivered instantly to your account.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('event.show', $event->id) }}" class="btn btn-modern btn-outline-glass w-100 py-3 fw-bold">
                                    <i class="bi bi-arrow-left me-2"></i> Edit Selection
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-modern btn-gradient w-100 py-3 fs-5 fw-bold">
                                    CONFIRM & PURCHASE <i class="bi bi-check2-circle ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
