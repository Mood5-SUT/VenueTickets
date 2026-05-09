@extends('layouts.admin')

@section('title', 'Promo Code Usage')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Promo Code: <span class="text-primary">{{ $promoCode->code }}</span></h4>
        <p class="text-muted mb-0">
            {{ $promoCode->description ?? 'No description' }} | 
            Used {{ $promoCode->used_count }}/{{ $promoCode->max_uses ?? '∞' }} times
        </p>
    </div>
    <a href="{{ route('admin_promo_codes_list') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Promo Codes
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $promoCode->used_count }}</h3>
                <small>Total Uses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $promoCode->type === 'percentage' ? $promoCode->value . '%' : '$' . number_format($promoCode->value, 2) }}</h3>
                <small>Discount Value</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $promoCode->max_uses ?? '∞' }}</h3>
                <small>Max Uses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-{{ $promoCode->isValid() ? 'success' : 'danger' }} text-white">
            <div class="card-body text-center">
                <h3>{{ $promoCode->isValid() ? 'Active' : 'Inactive' }}</h3>
                <small>Status</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Usage History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Order</th>
                        <th>Discount</th>
                        <th>Used At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usage as $index => $use)
                    <tr>
                        <td>{{ $usage->firstItem() + $index }}</td>
                        <td>{{ $use->user->name ?? 'N/A' }}<br><small class="text-muted">{{ $use->user->email ?? '' }}</small></td>
                        <td>{{ $use->order->order_number ?? 'N/A' }}</td>
                        <td>${{ number_format($use->discount_amount, 2) }}</td>
                        <td>{{ $use->used_at ? $use->used_at->format('M d, Y H:i') : $use->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">This promo code hasn't been used yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $usage->links() }}
    </div>
</div>
@endsection