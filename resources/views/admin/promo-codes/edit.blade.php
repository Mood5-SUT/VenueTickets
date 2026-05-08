@extends('layouts.admin')

@section('title', isset($promoCode) ? 'Edit Promo Code' : 'Create Promo Code')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($promoCode) ? 'Edit Promo Code' : 'Create New Promo Code' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin_promo_codes_save', $promoCode->id ?? null) }}">
            {{ csrf_field() }}
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" class="form-control text-uppercase" 
                           value="{{ $promoCode->code ?? old('code') }}" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select" required>
                        <option value="percentage" {{ ($promoCode->type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed_amount" {{ ($promoCode->type ?? '') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount ($)</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Value *</label>
                    <input type="number" name="value" class="form-control" step="0.01" min="0"
                           value="{{ $promoCode->value ?? old('value') }}" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Max Uses</label>
                    <input type="number" name="max_uses" class="form-control" min="1"
                           value="{{ $promoCode->max_uses ?? old('max_uses') }}" placeholder="Unlimited">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Max Per User</label>
                    <input type="number" name="max_uses_per_user" class="form-control" min="1"
                           value="{{ $promoCode->max_uses_per_user ?? 1 }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Min Order Amount</label>
                    <input type="number" name="min_order_amount" class="form-control" step="0.01" min="0"
                           value="{{ $promoCode->min_order_amount ?? '' }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Max Discount</label>
                    <input type="number" name="max_discount_amount" class="form-control" step="0.01" min="0"
                           value="{{ $promoCode->max_discount_amount ?? '' }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="datetime-local" name="starts_at" class="form-control"
                           value="{{ isset($promoCode->starts_at) ? $promoCode->starts_at->format('Y-m-d\TH:i') : '' }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Expiry Date *</label>
                    <input type="datetime-local" name="expires_at" class="form-control" required
                           value="{{ isset($promoCode->expires_at) ? $promoCode->expires_at->format('Y-m-d\TH:i') : '' }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Scope</label>
                    <select name="scope" class="form-select">
                        <option value="global" {{ ($promoCode->scope ?? 'global') == 'global' ? 'selected' : '' }}>Global</option>
                        <option value="event_specific" {{ ($promoCode->scope ?? '') == 'event_specific' ? 'selected' : '' }}>Event Specific</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ $promoCode->description ?? old('description') }}</textarea>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Promo Code
                </button>
                <a href="{{ route('admin_promo_codes_list') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection